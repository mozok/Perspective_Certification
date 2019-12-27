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

use Perspective\Certification\Api\Data\TypeInterface;
use Perspective\Certification\Api\Data\TypeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Type extends \Magento\Framework\Model\AbstractModel
{
    /** @var TypeInterfaceFactory */
    protected $typeDataFactory;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var string */
    protected $_eventPrefix = 'perspective_certification_type';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TypeInterfaceFactory $typeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Perspective\Certification\Model\ResourceModel\Type $resource
     * @param \Perspective\Certification\Model\ResourceModel\Type\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TypeInterfaceFactory $typeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Perspective\Certification\Model\ResourceModel\Type $resource,
        \Perspective\Certification\Model\ResourceModel\Type\Collection $resourceCollection,
        array $data = []
    ) {
        $this->typeDataFactory = $typeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve type model with type data
     * @return TypeInterface
     */
    public function getDataModel()
    {
        $typeData = $this->getData();

        $typeDataObject = $this->typeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $typeDataObject,
            $typeData,
            TypeInterface::class
        );

        return $typeDataObject;
    }
}
