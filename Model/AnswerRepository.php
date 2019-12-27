<?php
/**
 * Copyright (c) 2019
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Perspective\Certification\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Perspective\Certification\Api\AnswerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Perspective\Certification\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Perspective\Certification\Model\ResourceModel\Answer as ResourceAnswer;
use Perspective\Certification\Api\Data\AnswerSearchResultsInterfaceFactory;
use Perspective\Certification\Api\Data\AnswerInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Perspective\Certification\Api\Data\AnswerInterface;

class AnswerRepository implements AnswerRepositoryInterface
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var AnswerFactory */
    protected $answerFactory;

    /** @var AnswerSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var AnswerInterfaceFactory */
    protected $dataAnswerFactory;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ResourceAnswer */
    protected $resource;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    /** @var AnswerCollectionFactory */
    protected $answerCollectionFactory;


    /**
     * @param ResourceAnswer $resource
     * @param AnswerFactory $answerFactory
     * @param AnswerInterfaceFactory $dataAnswerFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param AnswerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceAnswer $resource,
        AnswerFactory $answerFactory,
        AnswerInterfaceFactory $dataAnswerFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        AnswerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->answerFactory = $answerFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAnswerFactory = $dataAnswerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Perspective\Certification\Api\Data\AnswerInterface $answer
    ) {
        /* if (empty($answer->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $answer->setStoreId($storeId);
        } */

        $answerData = $this->extensibleDataObjectConverter->toNestedArray(
            $answer,
            [],
            \Perspective\Certification\Api\Data\AnswerInterface::class
        );

        $answerModel = $this->answerFactory->create()->setData($answerData);

        try {
            $this->resource->save($answerModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the answer: %1',
                $exception->getMessage()
            ));
        }
        return $answerModel->getDataModel();
    }

    /**
     * @param int $questionId
     * @param array $answersArray
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateForQuestion($questionId, $answersArray)
    {
        $this->checkAndDeleteOldAnswers($questionId, $answersArray);

        foreach ($answersArray as $answerData) {
            if ($answerData[AnswerInterface::ANSWER_ID] === '') {
                $answerData[AnswerInterface::ANSWER_ID] = null;
            }
            $answerModel = $this->answerFactory->create()
                ->setData($answerData)
                ->setQuestionId($questionId);

            $this->save($answerModel->getDataModel());
        }
    }

    /**
     * @param int $questionId
     * @param array $answersArray
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function checkAndDeleteOldAnswers($questionId, $answersArray)
    {
        $newAnswersIds = [];
        foreach ($answersArray as $answer) {
            if ($answer[AnswerInterface::ANSWER_ID]) {
                $newAnswersIds[] = $answer[AnswerInterface::ANSWER_ID];
            }
        }

        if (!empty($newAnswersIds)) {
            $collection = $this->answerCollectionFactory->create();
            $collection->addFieldToFilter(AnswerInterface::QUESTION_ID, $questionId)
                ->addFieldToFilter(AnswerInterface::ANSWER_ID, array('nin' => $newAnswersIds));
            foreach ($collection as $answerItem) {
                $this->delete($answerItem->getDataModel());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($answerId)
    {
        $answer = $this->answerFactory->create();
        $this->resource->load($answer, $answerId);
        if (!$answer->getId()) {
            throw new NoSuchEntityException(__('Answer with id "%1" does not exist.', $answerId));
        }
        return $answer->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->answerCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Perspective\Certification\Api\Data\AnswerInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Perspective\Certification\Api\Data\AnswerInterface $answer
    ) {
        try {
            $answerModel = $this->answerFactory->create();
            $this->resource->load($answerModel, $answer->getAnswerId());
            $this->resource->delete($answerModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Answer: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($answerId)
    {
        return $this->delete($this->get($answerId));
    }
}
