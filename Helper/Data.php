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

namespace Eadesigndev\Pdfgenerator\Helper;

use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory as templateCollectionFactory;
use Eadesigndev\Pdfgenerator\Model\Source\AbstractSource;
use Eadesigndev\Pdfgenerator\Model\Source\TemplateActive;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Invoice;
use Magento\Store\Model\ScopeInterface;

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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var Collection
     */
    protected $templateCollection;

    /**
     * Data constructor.
     * @param Context $context
     * @param templateCollectionFactory $_templateCollection
     */
    public function __construct(
        Context $context,
        templateCollectionFactory $_templateCollection
    ) {
        $this->templateCollection = $_templateCollection;
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
            return $this->getConfig(self::EMAIL);
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

        if (!class_exists('mPDF')) {
            return false;
        }

        // @codingStandardsIgnoreLine
        if (!$this->collection()->count()) {
            return false;
        }

        return $this->getConfig(self::ENABLE);
    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return string
     */
    public function getConfig($configPath)
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
    public function collection()
    {
        $collection = $this->templateCollection->create();
        return $collection;
    }
}
