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

namespace Perspective\Certification\Controller\Adminhtml\Question;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Perspective\Certification\Model\AnswerRepository;
use Perspective\Certification\Model\QuestionRepository;

class Save extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\App\Request\DataPersistorInterface */
    protected $dataPersistor;

    /** @var QuestionRepository */
    protected $questionRepository;

    /** @var AnswerRepository */
    protected $answerRepository;

    /** @var \Perspective\Certification\Api\Data\QuestionInterfaceFactory */
    protected $questionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param QuestionRepository $questionRepository
     * @param AnswerRepository $answerRepository
     * @param \Perspective\Certification\Api\Data\QuestionInterfaceFactory $questionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        \Perspective\Certification\Api\Data\QuestionInterfaceFactory $questionFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->questionFactory = $questionFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('question_id');

            if ($id) {
                try {
                    $model = $this->questionRepository->get($id);
                } catch (NoSuchEntityException $exception) {
                    $this->messageManager->addErrorMessage(__('This Question no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                } catch (\Exception $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $model = $this->questionFactory->create();
            }

            $this->setModelData($model, $data);

            try {
                $model = $this->questionRepository->save($model);

                $this->answerRepository->updateForQuestion($model->getId(), $data['answers']);

                $this->messageManager->addSuccessMessage(__('You saved the Question.'));
                $this->dataPersistor->clear('perspective_certification_question');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['question_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Question.'));
            }

            $this->dataPersistor->set('perspective_certification_question', $data);
            return $resultRedirect->setPath('*/*/edit', ['question_id' => $this->getRequest()->getParam('question_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param \Perspective\Certification\Api\Data\QuestionInterface $model
     * @param array $data
     */
    protected function setModelData($model, $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'answers') {
                continue;
            }

            $model->setData($key, $value);
        }
    }

    protected function saveAnswers($answersArray)
    {
        foreach ($answersArray as $answer) {

        }
    }
}
