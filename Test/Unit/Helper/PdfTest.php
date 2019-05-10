<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Helper;

use Eadesigndev\Pdfgenerator\Helper\Pdf;
use Eadesigndev\Pdfgenerator\Model\MpdfFactory;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Model\Order\Address;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use Eadesigndev\Pdfgenerator\Model\Pdfgenerator;
use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperOrientation;
use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperForm;
use Eadesigndev\Pdfgenerator\Model\Template\Processor;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Invoice;
use Magento\Framework\App\Filesystem\DirectoryList;
use Mpdf\Mpdf;

class PdfTest extends TestCase
{

    const PARTS = [
        'header' => '<h1>Header</h1>',
        'footer' => '<h1>Footer</h1>',
        'body' => '<h1>Body</h1>',
        'file_name',
    ];

    const CONFIG = [
        'config' => [
            'mode' => '',
            'format' => [
                0 => null,
                1 => null
            ],
            'default_font_size' => '',
            'default_font' => '',
            'margin_left' => null,
            'margin_right' => null,
            'margin_top' => null,
            'margin_bottom' => null,
            'margin_header' => 0,
            'margin_footer' => 0,
            'tempDir' => null,
        ]
    ];

    /**
     * @var Pdf
     */
    private $subject;

    private $context;

    private $identityContainerMock;

    private $storeMock;

    private $paymentHelperMock;

    private $addressRenderer;

    private $processor;

    private $templatePaperForm;

    private $templatePaperOrientation;

    private $directoryList;

    private $addressMock;

    private $orderMock;

    private $pdfGeneratorMock;

    private $invoiceMock;

    private $mpdfFactoryfMock;

    private $mpdfMock;

    private $payment;

    private $paymentInstance;

    public function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->identityContainerMock = $this->getMockBuilder(InvoiceIdentity::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMock();

        $this->storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStoreId'])
            ->getMock();

        $this->identityContainerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->storeMock->expects($this->once())
            ->method('getStoreId')
            ->willReturn(1);

        $this->orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->addressRenderer = $this->getMockBuilder(Renderer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentHelperMock = $this->getMockBuilder(PaymentHelper::class)
            ->setMethods(['getInfoBlockHtml'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentInstance = $this->getMockForAbstractClass(MethodInterface::class);

        $this->payment = $this->createPartialMock(Payment::class, ['getMethodInstance']);

        $this->orderMock->expects($this->exactly(2))
            ->method('getPayment')
            ->willReturn($this->payment);

        $this->payment->expects($this->once())
            ->method('getMethodInstance')
            ->willReturn($this->paymentInstance);

        $this->paymentHelperMock->expects($this->once())
            ->method('getInfoBlockHtml')
            ->with($this->payment, 1)
            ->willReturn('string');

        $this->processor = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->directoryList = $this->getMockBuilder(DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatePaperForm = $this->getMockBuilder(TemplatePaperForm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->templatePaperOrientation = $this->getMockBuilder(TemplatePaperOrientation::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->addressMock = $this->getMockBuilder(Address::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock->expects($this->any())
            ->method('getBillingAddress')
            ->willReturn($this->addressMock);

        $this->orderMock->expects($this->any())
            ->method('getShippingAddress')
            ->willReturn($this->addressMock);

        $this->mpdfFactoryfMock = $this->getMockBuilder(MpdfFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->mpdfMock = $this->getMockBuilder(Mpdf::class)
            ->disableOriginalConstructor()
            ->setMethods(['SetHTMLHeader', 'SetHTMLFooter', 'WriteHTML', 'Output'])
            ->getMock();

        $this->mpdfFactoryfMock->expects($this->once())
            ->method('create')
            ->with(self::CONFIG)
            ->willReturn($this->mpdfMock);

        $this->subject = new Pdf(
            $this->context,
            $this->addressRenderer,
            $this->paymentHelperMock,
            $this->identityContainerMock,
            $this->processor,
            $this->directoryList,
            $this->templatePaperForm,
            $this->templatePaperOrientation,
            $this->mpdfFactoryfMock
        );
    }

    public function testTemplate2Pdf()
    {

        $this->pdfGeneratorMock = $this->getMockBuilder(Pdfgenerator::class)
            ->setMethods(['getTemplateCustomForm'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdfGeneratorMock->expects($this->once())
            ->method('getTemplateCustomForm')
            ->will($this->returnValue(1));

        $this->subject->setTemplate($this->pdfGeneratorMock);

        $this->invoiceMock = $this->getMockBuilder(Invoice::Class)
            ->setMethods(['getOrder'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->invoiceMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->subject->setInvoice($this->invoiceMock);

        $this->mpdfMock->expects($this->once())
            ->method('SetHTMLHeader')
            ->willReturnSelf();

        $this->mpdfMock->expects($this->once())
            ->method('SetHTMLFooter')
            ->willReturnSelf();

        $this->mpdfMock->expects($this->exactly(2))
            ->method('WriteHTML')
            ->willReturnSelf();

        $parts = implode('', self::PARTS);
        $result = 'pdf' . $parts;

        $this->mpdfMock->expects($this->once())
            ->method('Output')
            ->will($this->returnValue($result));

        $pdfOutput = $this->subject->template2Pdf();

        $this->assertArrayHasKey('filestream', $pdfOutput);
        $this->assertArrayHasKey('filename', $pdfOutput);

        $expected = 'pdf<h1>Header</h1><h1>Footer</h1><h1>Body</h1>file_name';

        $this->assertEquals($expected, $pdfOutput['filestream']);
        $this->assertEquals('', $pdfOutput['filename']);
    }
}
