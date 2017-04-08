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
use Magento\Email\Model\Source\Variables as EmailVariables;
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
     * @var EmailVariables
     */
    private $emailVariables;

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
     * @param EmailVariables $emailVariables
     * @param EmailBackendTemplate $emailBackendTemplate
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $emailConfig,
        JsonFactory $resultJsonFactory,
        JsonHelperData $jsonHelperData,
        VariableModel $variableModel,
        EmailVariables $emailVariables,
        EmailBackendTemplate $emailBackendTemplate
    ) {

        $this->emailConfig = $emailConfig;
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelperData = $jsonHelperData;
        $this->variableModel = $variableModel;
        $this->emailVariables = $emailVariables;
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
                'Magento\Email\Block\Adminhtml\Template\Edit'
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
        $storeContactVariables = $this->emailVariables->toOptionArray(true);
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
