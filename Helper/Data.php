<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Helper;

use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory as TemplateCollectionFactory;
use Eadesigndev\Pdfgenerator\Model\Source\AbstractSource;
use Eadesigndev\Pdfgenerator\Model\Source\TemplateActive;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Invoice;
use Magento\Store\Model\ScopeInterface;
use Mpdf\Mpdf;

/**
 * Handles the config and other settings
 *
 * Class Data
 * @package Eadesigndev\Pdfgenerator\Helper
 */
class Data extends AbstractHelper
{

    const ENABLE = 'eadesign_pdfgenerator/general/enabled';
    const EMAIL = 'eadesign_pdfgenerator/general/email';

    /**
     * @var ScopeConfigInterface
     */
    public $config;

    /**
     * @var Collection
     */
    public $templateCollection;

    /**
     * Data constructor.
     * @param Context $context
     * @param templateCollectionFactory $templateCollection
     */
    public function __construct(
        Context $context,
        TemplateCollectionFactory $templateCollection
    ) {
        $this->templateCollection = $templateCollection;
        $this->config = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * Check if module will send email on new invoice or invoice update
     *
     * @return boolean
     */
    public function isEmail()
    {
        if ($this->isEnable()) {
            return $this->hasConfig(self::EMAIL);
        }
        return false;
    }

    /**
     * Check if module is enable
     *
     * @return boolean
     */
    public function isEnable()
    {
        if (!$this->mPDFExists() || !$this->collection()->getSize()) {
            return false;
        }

        return $this->hasConfig(self::ENABLE);
    }

    /**
     * @return bool
     */
    private function mPDFExists()
    {
        if (class_exists(Mpdf::class)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $configPath
     * @return bool
     */
    public function hasConfig($configPath)
    {
        return $this->config->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the active template
     *
     * @param $invoice
     * @return \Magento\Framework\DataObject
     */
    public function getTemplateStatus(Invoice $invoice)
    {
        $invoiceStore = $invoice->getOrder()->getStoreId();
        $collection = $this->collection();
        $collection->addStoreFilter($invoiceStore);
        $collection->addFieldToFilter('is_active', TemplateActive::STATUS_ENABLED);
        $collection->addFieldToFilter('template_default', AbstractSource::IS_DEFAULT);

        return $collection->getLastItem();
    }

    /**
     * @return Collection
     */
    private function collection()
    {
        $collection = $this->templateCollection->create();
        return $collection;
    }
}
