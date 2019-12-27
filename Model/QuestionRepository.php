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

use Perspective\Certification\Model\ResourceModel\Question as ResourceQuestion;
use Perspective\Certification\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Perspective\Certification\Api\QuestionRepositoryInterface;
use Perspective\Certification\Api\Data\QuestionInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Perspective\Certification\Api\Data\QuestionSearchResultsInterfaceFactory;

class QuestionRepository implements QuestionRepositoryInterface
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var QuestionSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var QuestionInterfaceFactory */
    protected $dataQuestionFactory;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ResourceQuestion */
    protected $resource;

    /** @var QuestionFactory */
    protected $questionFactory;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    /** @var QuestionCollectionFactory */
    protected $questionCollectionFactory;


    /**
     * @param ResourceQuestion $resource
     * @param QuestionFactory $questionFactory
     * @param QuestionInterfaceFactory $dataQuestionFactory
     * @param QuestionCollectionFactory $questionCollectionFactory
     * @param QuestionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceQuestion $resource,
        QuestionFactory $questionFactory,
        QuestionInterfaceFactory $dataQuestionFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        QuestionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->questionFactory = $questionFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQuestionFactory = $dataQuestionFactory;
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
        \Perspective\Certification\Api\Data\QuestionInterface $question
    ) {
        /* if (empty($question->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $question->setStoreId($storeId);
        } */

        $questionData = $this->extensibleDataObjectConverter->toNestedArray(
            $question,
            [],
            \Perspective\Certification\Api\Data\QuestionInterface::class
        );

        $questionModel = $this->questionFactory->create()->setData($questionData);

        try {
            $this->resource->save($questionModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the question: %1',
                $exception->getMessage()
            ));
        }
        return $questionModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($questionId)
    {
        $question = $this->questionFactory->create();
        $this->resource->load($question, $questionId);
        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }
        return $question->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->questionCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Perspective\Certification\Api\Data\QuestionInterface::class
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
        \Perspective\Certification\Api\Data\QuestionInterface $question
    ) {
        try {
            $questionModel = $this->questionFactory->create();
            $this->resource->load($questionModel, $question->getQuestionId());
            $this->resource->delete($questionModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Question: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionId)
    {
        return $this->delete($this->get($questionId));
    }
}
