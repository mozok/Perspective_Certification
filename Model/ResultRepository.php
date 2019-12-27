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
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Perspective\Certification\Model\ResourceModel\Result\CollectionFactory as ResultCollectionFactory;
use Perspective\Certification\Api\ResultRepositoryInterface;
use Perspective\Certification\Api\Data\ResultInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Perspective\Certification\Api\Data\ResultSearchResultsInterfaceFactory;
use Perspective\Certification\Model\ResourceModel\Result as ResourceResult;

class ResultRepository implements ResultRepositoryInterface
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var ResultFactory */
    protected $resultFactory;

    /** @var ResultCollectionFactory */
    protected $resultCollectionFactory;

    /** @var ResultSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ResourceResult */
    protected $resource;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    /** @var ResultInterfaceFactory */
    protected $dataResultFactory;


    /**
     * @param ResourceResult $resource
     * @param ResultFactory $resultFactory
     * @param ResultInterfaceFactory $dataResultFactory
     * @param ResultCollectionFactory $resultCollectionFactory
     * @param ResultSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceResult $resource,
        ResultFactory $resultFactory,
        ResultInterfaceFactory $dataResultFactory,
        ResultCollectionFactory $resultCollectionFactory,
        ResultSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->resultFactory = $resultFactory;
        $this->resultCollectionFactory = $resultCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataResultFactory = $dataResultFactory;
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
        \Perspective\Certification\Api\Data\ResultInterface $result
    ) {
        /* if (empty($result->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $result->setStoreId($storeId);
        } */

        $resultData = $this->extensibleDataObjectConverter->toNestedArray(
            $result,
            [],
            \Perspective\Certification\Api\Data\ResultInterface::class
        );

        $resultModel = $this->resultFactory->create()->setData($resultData);

        try {
            $this->resource->save($resultModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the result: %1',
                $exception->getMessage()
            ));
        }
        return $resultModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($resultId)
    {
        $result = $this->resultFactory->create();
        $this->resource->load($result, $resultId);
        if (!$result->getId()) {
            throw new NoSuchEntityException(__('Result with id "%1" does not exist.', $resultId));
        }
        return $result->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->resultCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Perspective\Certification\Api\Data\ResultInterface::class
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
        \Perspective\Certification\Api\Data\ResultInterface $result
    ) {
        try {
            $resultModel = $this->resultFactory->create();
            $this->resource->load($resultModel, $result->getResultId());
            $this->resource->delete($resultModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Result: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($resultId)
    {
        return $this->delete($this->get($resultId));
    }
}
