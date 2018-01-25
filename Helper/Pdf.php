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

use Eadesigndev\Pdfgenerator\Model\Pdfgenerator;
use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperOrientation;
use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperForm;
use Eadesigndev\Pdfgenerator\Model\Template\Processor;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order\Invoice;
use Magento\Framework\App\Filesystem\DirectoryList;
use Mpdf\Mpdf;

/**
 * Class Pdf
 * @package Eadesigndev\Pdfgenerator\Helper
 * @SuppressWarnings("CouplingBetweenObjects")
 */
class Pdf extends AbstractHelper
{
    /**
     * Paper orientation
     */
    const PAPER_ORI = [
        1 => 'P',
        2 => 'L'
    ];

    /**
     * Paper size
     */
    const PAPER_SIZE = [
        1 => 'A4-',
        2 => 'A3-',
        3 => 'A5-',
        4 => 'A6-',
        5 => 'LETTER-',
        6 => 'LEGAL-'
    ];

    public $order;

    /**
     * @var invoice;
     */
    public $invoice;

    /**
     * @var template
     */
    public $template;

    /**
     * @var IdentityInterface
     */
    public $identityContainer;

    /**
     * @var
     */
    public $mPDF;

    /**
     * @var PaymentHelper
     */
    public $paymentHelper;

    /**
     * @var Renderer
     */
    public $addressRenderer;

    /**
     * @var Processor
     */
    public $processor;

    /**
     * @var TemplatePaperForm
     */
    private $templatePaperForm;
    /**
     * @var TemplatePaperOrientation
     */
    private $templatePaperOrientation;

    private $directoryList;

    /**
     * Pdf constructor.
     * @param Context $context
     * @param Renderer $addressRenderer
     * @param PaymentHelper $paymentHelper
     * @param InvoiceIdentity $identityContainer
     * @param Processor $templateFactory
     */
    public function __construct(
        Context $context,
        Renderer $addressRenderer,
        PaymentHelper $paymentHelper,
        InvoiceIdentity $identityContainer,
        Processor $templateFactory,
        DirectoryList $directoryList,
        TemplatePaperForm $templatePaperForm,
        TemplatePaperOrientation $templatePaperOrientation
    ) {
        $this->processor                = $templateFactory;
        $this->paymentHelper            = $paymentHelper;
        $this->identityContainer        = $identityContainer;
        $this->addressRenderer          = $addressRenderer;
        $this->directoryList            = $directoryList;
        $this->templatePaperForm        = $templatePaperForm;
        $this->templatePaperOrientation = $templatePaperOrientation;
        parent::__construct($context);
    }

    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->setOrder($invoice->getOrder());
        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param Pdfgenerator $template
     * @return $this
     */
    public function setTemplate(Pdfgenerator $template)
    {
        $this->template = $template;
        $this->processor->setPDFTemplate($template);
        return $this;
    }

    /**
     * Filename of the pdf and the stream to sent to the download
     *
     * @return array
     */
    public function template2Pdf()
    {
        /**transport use to get the variables $order object, $invoice object and the template model object*/
        $parts = $this->_transport();

        /** instantiate the mPDF class and add the processed html to get the pdf*/
        $applySettings = $this-> _eaPDFSettings($parts);

        $fileParts = [
            'filestream' => $applySettings,
            'filename' => filter_var($parts['filename'], FILTER_SANITIZE_URL)
        ];

        return $fileParts;
    }

    /**
     *
     * This will proces the template and the variables from the entity's
     *
     * @return string
     */
    public function _transport()
    {

        $invoice = $this->invoice;
        $order = $this->order;
        $paymentTitle= $order->getPayment()->getMethodInstance()->getTitle();
        $transport = [
            'order' => $order,
            'invoice' => $invoice,
            'comment' => $invoice->getCustomerNoteNotify() ? $invoice->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'paymentInfo' => $paymentTitle,
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order)
        ];

        $processor = $this->processor;
        $processor->setVariables($transport);
        $processor->setTemplate($this->template);
        $parts = $processor->processTemplate();

        return $parts;
    }

    /**
     * @param $parts
     * @return string
     */
    public function _eaPDFSettings($parts)
    {

        $templateModel = $this->template;

        $oldErrorReporting = error_reporting();
        error_reporting(0);

        if (!$templateModel->getTemplateCustomForm()) {
            /** @var mPDF $pdf */
            //@codingStandardsIgnoreLine
            $pdf = new Mpdf($this->config($templateModel));
        }

        if ($templateModel->getTemplateCustomForm()) {
            //@codingStandardsIgnoreLine
            $pdf = new Mpdf($this->config($templateModel));
        }

        $pdf->SetHTMLHeader($parts['header']);
        $pdf->SetHTMLFooter($parts['footer']);

        $pdf->WriteHTML($templateModel->getTemplateCss(), 1);

        //@codingStandardsIgnoreLine
        $pdf->WriteHTML('<body>' . html_entity_decode($parts['body']) . '</body>');
        $pdfToOutput = $pdf->Output('', 'S');

        error_reporting($oldErrorReporting);

        return $pdfToOutput;
    }

    /**
     * @param Pdfgenerator $templateModel
     * @return array
     */
    private function config($templateModel)
    {
        $ori = $templateModel->getTemplatePaperOri();
        $orientation = $this->templatePaperOrientation->getAvailable();
        $finalOri = $orientation[$ori][0];
        $marginTop = $templateModel->getTemplateCustomT();
        $marginBottom = $templateModel->getTemplateCustomB();
        $paperForms = $this->templatePaperForm->getAvailable();
        $templatePaperForm = $templateModel->getTemplatePaperForm();
        if (!$templatePaperForm) {
            $templatePaperForm = 1;
        }
        $form = $paperForms[$templatePaperForm];
        if ($ori == TemplatePaperOrientation::TEMAPLATE_PAPER_LANDSCAPE) {
            $form = $paperForms[$templateModel->getTemplatePaperForm()] . '-' . $finalOri;
        }

        $config = [
            'mode' => '',
            'format' => $form,
            'default_font_size' => '',
            'default_font' => '',
            'margin_left' => $templateModel->getTemplateCustomL(),
            'margin_right' => $templateModel->getTemplateCustomR(),
            'margin_top' => $marginTop,
            'margin_bottom' => $marginBottom,
            'margin_header' => 0,
            'margin_footer' => 0,
            'tempDir' => $this->directoryList->getPath('tmp')
        ];

        if ($templateModel->getTemplateCustomForm()) {
            $config = [
                'mode' => '',
                'format' => [
                    $templateModel->getTemplateCustomW(),
                    $templateModel->getTemplateCustomH()
                ],
                'default_font_size' => '',
                'default_font' => '',
                'margin_left' => $templateModel->getTemplateCustomL(),
                'margin_right' => $templateModel->getTemplateCustomR(),
                'margin_top' => $marginTop,
                'margin_bottom' => $marginBottom,
                'margin_header' => 0,
                'margin_footer' => 0,
                'tempDir' => $this->directoryList->getPath('tmp')
            ];
        }

        return $config;
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function getPaymentHtml(Order $order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $this->identityContainer->getStore()->getStoreId()
        );
    }

    /**
     * @param Order $order
     * @return null
     */
    public function getFormattedShippingAddress(Order $order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * @param Order $order
     * @return null|string
     */
    public function getFormattedBillingAddress(Order $order)
    {
        /** @var \Magento\Sales\Model\Order\Address $billing */
        $billing = $order->getBillingAddress();
        $address = $this->addressRenderer->format($billing, 'html');
        return $address;
    }
}
