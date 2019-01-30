<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Variable;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\Json\Helper\Data as JsonHelperData;
use Magento\Variable\Model\Variable as VariableModel;
use Eadesigndev\Pdfgenerator\Model\Email\VariablesFacrory;
use Magento\Email\Model\BackendTemplate as EmailBackendTemplate;
use Zend_Json;

/**
 * Class Template
 * @package Eadesigndev\Pdfgenerator\Controller\Adminhtml\Variable
 * @SuppressWarnings("CouplingBetweenObjects")
 */
class Template extends Action
{

    const INVOICE_TMEPLTE_ID = 'sales_email_invoice_template';
    const ADMIN_RESOURCE_VIEW = 'Eadesigndev_Pdfgenerator::templates';

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var Config
     */
    private $emailConfig;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

    /**
     * @var JsonHelperData
     */
    private $jsonHelperData;

    /**
     * @var VariableModel
     */
    private $variableModel;

    /**
     * @var VariablesFacrory
     */
    private $variablesFacrory;

    /**
     * @var EmailBackendTemplate
     */
    private $emailBackendTemplate;

    /**
     * Template constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param JsonHelperData $jsonHelperData
     * @param VariableModel $variableModel
     * @param VariablesFacrory $variablesFacrory
     * @param EmailBackendTemplate $emailBackendTemplate
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $emailConfig,
        JsonFactory $resultJsonFactory,
        JsonHelperData $jsonHelperData,
        VariableModel $variableModel,
        VariablesFacrory $variablesFacrory,
        EmailBackendTemplate $emailBackendTemplate
    ) {

        $this->emailConfig = $emailConfig;
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelperData = $jsonHelperData;
        $this->variableModel = $variableModel;
        $this->variablesFacrory = $variablesFacrory;
        $this->emailBackendTemplate = $emailBackendTemplate;
    }

    /**
     * WYSIWYG Plugin Action
     *
     * @return Json
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
            $template->setData(
                'template_variables',
                Zend_Json::encode($template->getVariablesOptionArray(true))
            );

            $templateBlock = $this->_view->getLayout()->createBlock(
                \Magento\Email\Block\Adminhtml\Template\Edit::class
            );
            $template->setData(
                'orig_template_currently_used_for',
                $templateBlock->getCurrentlyUsedForPaths(false)
            );

            $this->getResponse()->representJson(
                $this->jsonHelperData
                    ->jsonEncode($template->getData())
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e, 'There was a problem:' . $e->getMessage());
        }

        $customVariables = $this->variableModel
            ->getVariablesOptionArray(true);
        $storeContactVariables = $this->variablesFacrory->create()->toOptionArray(true);
        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            $storeContactVariables,
            $customVariables,
            $template->getVariablesOptionArray(true)
        ]);
    }

    /**
     * Load email template from request
     *
     * @return \Magento\Email\Model\BackendTemplate $model
     */
    //@codingStandardsIgnoreLine
    protected function _initTemplate()
    {

        $model = $this->emailBackendTemplate;

        if (!$this->coreRegistry->registry('email_template')) {
            $this->coreRegistry->register('email_template', $model);
        }

        if (!$this->coreRegistry->registry('current_email_template')) {
            $this->coreRegistry->register('current_email_template', $model);
        }

        return $model;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed(
            Templates::ADMIN_RESOURCE_VIEW
        );
    }
}
