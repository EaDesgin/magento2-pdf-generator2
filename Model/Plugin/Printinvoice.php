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

namespace Eadesigndev\Pdfgenerator\Model\Plugin;

use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory as templateCollectionFactory;

class Printinvoice
{

    /**
     * @var \Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection
     */
    protected $_templateCollection;


    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $_urlInterface;

    /**
     * @var \Magento\Framework\Registry
     */
    private $_coreRegistry;

    /**
     * Config constructor.
     * @param \Magento\Framework\Registry $_coreRegistry
     */

    public function __construct(
        \Magento\Framework\Registry $_coreRegistry,
        \Magento\Backend\Model\UrlInterface $_urlInterface,
        templateCollectionFactory $_templateCollection

    )
    {
        $this->_coreRegistry = $_coreRegistry;
        $this->_urlInterface = $_urlInterface;
        $this->_templateCollection = $_templateCollection;
    }

    /**
     * Retrieve invoice model instance
     *
     * @return \Magento\Sales\Model\Order\Invoice
     */
    public function getInvoice()
    {
        return $this->_coreRegistry->registry('current_invoice');
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    private function _getTemplateStatus(){

        $invoiceStore = $this->getInvoice()->getOrder()->getStoreId();

        $collection = $this->_templateCollection->create();
        $collection->addStoreFilter($invoiceStore);
        $collection->addFieldToFilter('is_active', \Eadesigndev\Pdfgenerator\Model\Source\TemplateActive::STATUS_ENABLED);
        $collection->addFieldToFilter('template_default',  \Eadesigndev\Pdfgenerator\Model\Source\AbstractSource::IS_DEFAULT);

        return $collection->getLastItem();
    }

    public function afterGetPrintUrl($subject, $result)
    {

        $lastItem = $this->_getTemplateStatus();

        if(empty($lastItem->getId())){
            return $result;
        }

        //TODO add sysem config to enable
        return $this->_urlInterface->getUrl(
            'pdfgenerator/*/printpdf',
            [
                'template_id' => $lastItem->getId(),
                'order_id' => $this->getInvoice()->getOrder()->getId(),
                'invoice_id' => $this->getInvoice()->getId()
            ]);



    }
}
