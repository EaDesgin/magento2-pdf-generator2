<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;


class Edit extends \Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var TemplateRepository
     */
    protected $templateRepository;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        TemplateRepository $templateRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->templateRepository = $templateRepository;
        parent::__construct($context,$registry);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
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
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
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
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('pdfgenerator_template', $model);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Template') : __('New Template'),
            $id ? __('Edit Template') : __('New Template')
        );
        
        $resultPage->getConfig()->getTitle()->prepend(__('Template'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getData('template_id') ? __('Template ') . $model->getTemplateName() : __('New Template'));

        return $resultPage;
    }
}
