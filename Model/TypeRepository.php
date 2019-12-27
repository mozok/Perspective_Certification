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

use Perspective\Certification\Api\TypeRepositoryInterface;
use Perspective\Certification\Api\Data\TypeSearchResultsInterfaceFactory;
use Perspective\Certification\Api\Data\TypeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Perspective\Certification\Model\ResourceModel\Type as ResourceType;
use Perspective\Certification\Model\ResourceModel\Type\CollectionFactory as TypeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class TypeRepository implements TypeRepositoryInterface
{
    /** @var ResourceType */
    protected $resource;

    /** @var TypeFactory */
    protected $typeFactory;

    /** @var TypeCollectionFactory */
    protected $typeCollectionFactory;

    /** @var TypeSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var TypeInterfaceFactory */
    protected $dataTypeFactory;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceType $resource
     * @param TypeFactory $typeFactory
     * @param TypeInterfaceFactory $dataTypeFactory
     * @param TypeCollectionFactory $typeCollectionFactory
     * @param TypeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceType $resource,
        TypeFactory $typeFactory,
        TypeInterfaceFactory $dataTypeFactory,
        TypeCollectionFactory $typeCollectionFactory,
        TypeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->typeFactory = $typeFactory;
        $this->typeCollectionFactory = $typeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTypeFactory = $dataTypeFactory;
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
        \Perspective\Certification\Api\Data\TypeInterface $type
    ) {
        /* if (empty($type->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $type->setStoreId($storeId);
        } */

        $typeData = $this->extensibleDataObjectConverter->toNestedArray(
            $type,
            [],
            \Perspective\Certification\Api\Data\TypeInterface::class
        );

        $typeModel = $this->typeFactory->create()->setData($typeData);

        try {
            $this->resource->save($typeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the type: %1',
                $exception->getMessage()
            ));
        }
        return $typeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($typeId)
    {
        $type = $this->typeFactory->create();
        $this->resource->load($type, $typeId);
        if (!$type->getId()) {
            throw new NoSuchEntityException(__('Type with id "%1" does not exist.', $typeId));
        }
        return $type->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->typeCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Perspective\Certification\Api\Data\TypeInterface::class
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
        \Perspective\Certification\Api\Data\TypeInterface $type
    ) {
        try {
            $typeModel = $this->typeFactory->create();
            $this->resource->load($typeModel, $type->getTypeId());
            $this->resource->delete($typeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Type: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($typeId)
    {
        return $this->delete($this->get($typeId));
    }
}
