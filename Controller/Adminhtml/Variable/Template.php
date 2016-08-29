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

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Variable;

use Magento\Framework\App\Action\Action;

class Template extends Action
{

    CONST INVOICE_TMEPLTE_ID = 'sales_email_invoice_template';
    CONST ADMIN_RESOURCE_VIEW = 'Eadesigndev_Pdfgenerator::templates';

    private $_coreRegistry;
    /**
     * @var \Magento\Email\Model\Template\Config
     */

    /**
     * @var \Magento\Email\Model\Template\Config
     */
    private $emailConfig;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Template constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Email\Model\Template\Config $emailConfig
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Email\Model\Template\Config $emailConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {

        $this->emailConfig = $emailConfig;
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * WYSIWYG Plugin Action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {

        $template = $this->_initTemplate();

        try {
            $parts = $this->emailConfig->parseTemplateIdParts(self::INVOICE_TMEPLTE_ID);
            $templateId = $parts['templateId'];
            $theme = $parts['theme'];

            if ($theme) {
                $template->setForcedTheme($templateId, $theme);
            }
            $template->setForcedArea($templateId);

            $template->loadDefault($templateId);
            $template->setData('orig_template_code', $templateId);
            $template->setData('template_variables', \Zend_Json::encode($template->getVariablesOptionArray(true)));

            $templateBlock = $this->_view->getLayout()->createBlock('Magento\Email\Block\Adminhtml\Template\Edit');
            $template->setData('orig_template_currently_used_for', $templateBlock->getCurrentlyUsedForPaths(false));

            $this->getResponse()->representJson(
                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($template->getData())
            );
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

        $customVariables = $this->_objectManager->create('Magento\Variable\Model\Variable')
            ->getVariablesOptionArray(true);
        $storeContactVariables = $this->_objectManager->create(
            'Magento\Email\Model\Source\Variables'
        )->toOptionArray(
            true
        );
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([$storeContactVariables, $customVariables, $template->getVariablesOptionArray(true)]);
    }

    /**
     * Load email template from request
     *
     * @return \Magento\Email\Model\BackendTemplate $model
     */
    protected function _initTemplate()
    {

        $model = $this->_objectManager->create('Magento\Email\Model\BackendTemplate');

        if (!$this->_coreRegistry->registry('email_template')) {
            $this->_coreRegistry->register('email_template', $model);
        }
        if (!$this->_coreRegistry->registry('current_email_template')) {
            $this->_coreRegistry->register('current_email_template', $model);
        }
        return $model;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(\Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates::ADMIN_RESOURCE_VIEW);
    }
}
