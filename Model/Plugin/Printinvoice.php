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

use Eadesigndev\Pdfgenerator\Helper\Data;

class Printinvoice
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $_urlInterface;

    /**
     * @var \Magento\Framework\Registry
     */
    private $_coreRegistry;

    /**
     * @var Data
     */
    private $_dataHelper;

    /**
     * Printinvoice constructor.
     * @param \Magento\Framework\Registry $_coreRegistry
     * @param \Magento\Backend\Model\UrlInterface $_urlInterface
     * @param Data $_dataHelper
     */
    public function __construct(
        \Magento\Framework\Registry $_coreRegistry,
        \Magento\Backend\Model\UrlInterface $_urlInterface,
        Data $_dataHelper

    )
    {
        $this->_coreRegistry = $_coreRegistry;
        $this->_urlInterface = $_urlInterface;
        $this->_dataHelper = $_dataHelper;
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
     * Check and see if and what we can print;
     *
     * @param $subject
     * @param $result
     * @return string
     */
    public function afterGetPrintUrl($subject, $result)
    {

        if (!$this->_dataHelper->isEnable()) {
            return $result;
        }

        $lastItem = $this->_dataHelper->getTemplateStatus($this->getInvoice());

        if (empty($lastItem->getId())) {
            return $result;
        }

        return $this->_print($lastItem);


    }

    /**
     * @param $lastItem
     * @return string
     */
    private function _print($lastItem)
    {
        return $this->_urlInterface->getUrl(
            'pdfgenerator/*/printpdf',
            [
                'template_id' => $lastItem->getId(),
                'order_id' => $this->getInvoice()->getOrder()->getId(),
                'invoice_id' => $this->getInvoice()->getId()
            ]);
    }
}
