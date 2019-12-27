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

namespace Perspective\Certification\Controller\Adminhtml\Type;

use Magento\Framework\Exception\NoSuchEntityException;
use Perspective\Certification\Model\TypeRepository;

class Edit extends \Perspective\Certification\Controller\Adminhtml\Type
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var \Perspective\Certification\Api\Data\TypeInterfaceFactory */
    protected $typeFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param TypeRepository $typeRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Perspective\Certification\Api\Data\TypeInterfaceFactory $typeFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Perspective\Certification\Model\TypeRepository $typeRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Perspective\Certification\Api\Data\TypeInterfaceFactory $typeFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->typeFactory = $typeFactory;
        parent::__construct($context, $coreRegistry, $typeRepository);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('type_id');

        // 2. Initial checking
        /** @var \Perspective\Certification\Api\Data\TypeInterface $model */
        if ($id) {
            try {
                $model = $this->typeRepository->get($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Type no longer exists.'));
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
            $model = $this->typeFactory->create();
        }
        $this->_coreRegistry->register('perspective_certification_type', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Type') : __('New Type'),
            $id ? __('Edit Type') : __('New Type')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Types'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Type %1', $model->getId()) : __('New Type'));
        return $resultPage;
    }
}
