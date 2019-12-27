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

use Magento\Framework\Exception\NoSuchEntityException;
use function GuzzleHttp\Psr7\modify_request;

class Edit extends \Perspective\Certification\Controller\Adminhtml\Question
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var \Perspective\Certification\Api\Data\QuestionInterface */
    protected $questionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Perspective\Certification\Model\QuestionRepository $questionRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Perspective\Certification\Api\Data\QuestionInterfaceFactory $questionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Perspective\Certification\Model\QuestionRepository $questionRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Perspective\Certification\Api\Data\QuestionInterfaceFactory $questionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->questionFactory = $questionFactory;
        parent::__construct($context, $coreRegistry, $questionRepository);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('question_id');

        // 2. Initial checking
        /** @var \Perspective\Certification\Api\Data\QuestionInterface $model */
        if ($id) {
            try {
                $model = $this->questionRepository->get($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Question no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->questionFactory->create();
        }
        $this->_coreRegistry->register('perspective_certification_question', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Question') : __('New Question'),
            $id ? __('Edit Question') : __('New Question')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Questions'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Question %1', $model->getId()) : __('New Question'));
        return $resultPage;
    }
}
