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

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Invoice;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Abstractpdf;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Address\Renderer;
use Eadesigndev\Pdfgenerator\Model\Template\Processor;


class Printpdf extends Abstractpdf
{

    /**
     * @var IdentityInterface
     */
    protected $identityContainer;

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var TemplateFactory
     */
    private $processor;

    /**
     * @var Renderer
     */
    protected $addressRenderer;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Email\Model\Template\Config $emailConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        PaymentHelper $paymentHelper,
        InvoiceIdentity $identityContainer,
        Renderer $addressRenderer,
        Processor $_templateFactory

    )
    {
        $this->identityContainer = $identityContainer;
        $this->processor = $_templateFactory;
        $this->paymentHelper = $paymentHelper;
        $this->addressRenderer = $addressRenderer;
        parent::__construct($context, $coreRegistry, $emailConfig, $resultJsonFactory);
    }


    public function execute()
    {

        //TODO remove and add load by constructor;

        $templateModel = $this->_objectManager->create('Eadesigndev\Pdfgenerator\Model\Pdfgenerator');
        $templateModel->load(2);

        $invoiceId = $this->getRequest()->getParam('invoice_id');
        if (!$invoiceId) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $invoice = $this->_objectManager->create('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
        if (!$invoice) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $order = $invoice->getOrder();

        $transport = [
            'order' => $order,
            'invoice' => $invoice,
            'comment' => $invoice->getCustomerNoteNotify() ? $invoice->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order)
        ];

        $processor = $this->processor;

        $processor->setVariables($transport);
        $processor->setTemplate($templateModel);


        $text = $processor->processTemplate();
        echo $text;
//        exit();
    }

    /**
     * Return payment info block as html
     *
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    protected function getPaymentHtml(\Magento\Sales\Model\Order $order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $this->identityContainer->getStore()->getStoreId()
        );
    }

    /**
     * @param Order $order
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * @param Order $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }


}
