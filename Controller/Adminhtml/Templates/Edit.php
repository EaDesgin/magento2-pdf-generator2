<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\Session;

/**
 * Class Edit
 * @package Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates
 */
class Edit extends Templates
{
    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry = null;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var TemplateRepository
     */
    private $templateRepository;

    /**
     * @var PdfgeneratorFactory
     */
    private $pdfgeneratorFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param TemplateRepository $templateRepository
     * @param PdfgeneratorFactory $pdfgeneratorFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        TemplateRepository $templateRepository,
        PdfgeneratorFactory $pdfgeneratorFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->templateRepository = $templateRepository;
        $this->pdfgeneratorFactory = $pdfgeneratorFactory;
        $this->session = $context->getSession();
        parent::__construct($context, $registry);
    }

    /**
     * @return bool
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Init actions
     *
     * @return object
     */
    //@codingStandardsIgnoreLine
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Eadesigndev_Pdfgenerator::template_list')
            ->addBreadcrumb(__('PDF Template'), __('PDF Template'))
            ->addBreadcrumb(__('Manage Template'), __('Manage Template'));

        return $resultPage;
    }

    /**
     * Edit PDF Templates
     *
     * @return \Magento\Framework\Controller\Result\Redirect|object
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('template_id');

        if ($id) {
            $model = $this->templateRepository->getById($id);

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->pdfgeneratorFactory->create();
        }

        /** @var Session $data */
        $data = $this->session->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('pdfgenerator_template', $model);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Template') : __('New Template'),
            $id ? __('Edit Template') : __('New Template')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Template'));
        $resultPage->getConfig()->getTitle()
            ->prepend(
                $model->getData('template_id') ? __('Template ') . $model->getTemplateName() : __('New Template')
            );

        return $resultPage;
    }
}
