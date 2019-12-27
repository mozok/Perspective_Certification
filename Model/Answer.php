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
use Perspective\Certification\Api\Data\AnswerInterface;
use Perspective\Certification\Api\Data\AnswerInterfaceFactory;

class Answer extends \Magento\Framework\Model\AbstractModel
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var string */
    protected $_eventPrefix = 'perspective_certification_answer';

    /** @var AnswerInterfaceFactory */
    protected $answerDataFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param AnswerInterfaceFactory $answerDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Perspective\Certification\Model\ResourceModel\Answer $resource
     * @param \Perspective\Certification\Model\ResourceModel\Answer\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        AnswerInterfaceFactory $answerDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Perspective\Certification\Model\ResourceModel\Answer $resource,
        \Perspective\Certification\Model\ResourceModel\Answer\Collection $resourceCollection,
        array $data = []
    ) {
        $this->answerDataFactory = $answerDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve answer model with answer data
     * @return AnswerInterface
     */
    public function getDataModel()
    {
        $answerData = $this->getData();

        $answerDataObject = $this->answerDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $answerDataObject,
            $answerData,
            AnswerInterface::class
        );

        return $answerDataObject;
    }
}
