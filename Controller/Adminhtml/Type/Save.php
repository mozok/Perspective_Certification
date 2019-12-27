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

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\App\Request\DataPersistorInterface */
    protected $dataPersistor;

    /** @var \Perspective\Certification\Model\TypeRepository */
    protected $typeRepository;

    /** @var \Perspective\Certification\Api\Data\TypeInterfaceFactory */
    protected $typeFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Perspective\Certification\Model\TypeRepository $typeRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Perspective\Certification\Model\TypeRepository $typeRepository,
        \Perspective\Certification\Api\Data\TypeInterfaceFactory $typeFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->typeRepository = $typeRepository;
        $this->typeFactory = $typeFactory;
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
            $id = $this->getRequest()->getParam('type_id');

            if ($id) {
                try {
                    $model = $this->typeRepository->get($id);
                } catch (NoSuchEntityException $exception) {
                    $this->messageManager->addErrorMessage(__('This Type no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                } catch (\Exception $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $model = $this->typeFactory->create();
            }

            $this->setModelData($model, $data);

            try {
                $model = $this->typeRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the Type.'));
                $this->dataPersistor->clear('perspective_certification_type');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['type_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Type.'));
            }

            $this->dataPersistor->set('perspective_certification_type', $data);
            return $resultRedirect->setPath('*/*/edit', ['type_id' => $this->getRequest()->getParam('type_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param \Perspective\Certification\Api\Data\TypeInterface $model
     * @param array $data
     */
    protected function setModelData($model, $data)
    {
        foreach ($data as $key => $value) {
            $model->setData($key, $value);
        }
    }
}
