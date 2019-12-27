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

namespace Perspective\Certification\Model\Question;

use Perspective\Certification\Model\ResourceModel\Question\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Perspective\Certification\Api\Data\QuestionInterface;
use Perspective\Certification\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Perspective\Certification\Api\Data\AnswerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /** @var \Perspective\Certification\Model\ResourceModel\Question\Collection */
    protected $collection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var array|null */
    protected $loadedData;

    /** @var AnswerCollectionFactory */
    protected $answerCollectionFactory;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        AnswerCollectionFactory $answerCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->answerCollectionFactory = $answerCollectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        $answers = $this->getAnswers($this->collection);

        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            if (array_key_exists($model->getId(), $answers)) {
                $this->loadedData[$model->getId()]['answers'] = $answers[$model->getId()];
            }
        }
        $data = $this->dataPersistor->get('perspective_certification_question');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('perspective_certification_question');
        }

        return $this->loadedData;
    }

    /**
     * @param \Perspective\Certification\Model\ResourceModel\Question\Collection $questionCollection
     * @return array
     */
    protected function getAnswers($questionCollection)
    {
        $questionIds = $questionCollection->getColumnValues(QuestionInterface::QUESTION_ID);
        /** @var \Perspective\Certification\Model\ResourceModel\Answer\Collection $answersCollection */
        $answersCollection = $this->answerCollectionFactory->create();
        $answersCollection->addFieldToFilter(AnswerInterface::QUESTION_ID, ['in' => $questionIds]);

        $result = [];
        /** @var AnswerInterface $answer */
        foreach ($answersCollection as $answer) {
            $result[$answer->getQuestionId()][] = $answer->getData();
        }

        return $result;
    }
}
