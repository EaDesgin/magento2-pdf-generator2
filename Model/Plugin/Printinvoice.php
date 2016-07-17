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
 * @category    custom_ext_code
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Pdfgenerator\Model\Plugin;

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
     * Config constructor.
     * @param \Magento\Framework\Registry $_coreRegistry
     */

    public function __construct(
        \Magento\Framework\Registry $_coreRegistry,
        \Magento\Backend\Model\UrlInterface $_urlInterface

    )
    {
        $this->_coreRegistry = $_coreRegistry;
        $this->_urlInterface = $_urlInterface;
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
     * @param $subject
     * @param $result
     * @return string
     */

    public function afterGetPrintUrl($subject, $result)
    {

        //TODO add sysem config to enable
        return $this->_urlInterface->getUrl(
            'pdfgenerator/order_invoice/printpdf',
            [
                'order_id' => $this->getInvoice()->getOrder()->getId(),
                'invoice_id' => $this->getInvoice()->getId()
            ]);

        return $result;

    }
}
