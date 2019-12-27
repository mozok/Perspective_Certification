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

use Magento\Framework\Api\DataObjectHelper;
use Perspective\Certification\Api\Data\QuestionInterfaceFactory;
use Perspective\Certification\Api\Data\QuestionInterface;

class Question extends \Magento\Framework\Model\AbstractModel
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var string */
    protected $_eventPrefix = 'perspective_certification_question';

    /** @var QuestionInterfaceFactory */
    protected $questionDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param QuestionInterfaceFactory $questionDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Perspective\Certification\Model\ResourceModel\Question $resource
     * @param \Perspective\Certification\Model\ResourceModel\Question\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        QuestionInterfaceFactory $questionDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Perspective\Certification\Model\ResourceModel\Question $resource,
        \Perspective\Certification\Model\ResourceModel\Question\Collection $resourceCollection,
        array $data = []
    ) {
        $this->questionDataFactory = $questionDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve question model with question data
     * @return QuestionInterface
     */
    public function getDataModel()
    {
        $questionData = $this->getData();

        $questionDataObject = $this->questionDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $questionDataObject,
            $questionData,
            QuestionInterface::class
        );

        return $questionDataObject;
    }
}
