<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Invoice;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Abstractpdf;
use Eadesigndev\Pdfgenerator\Helper\Pdf;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Email\Model\Template\Config;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository;
use Magento\Sales\Model\Order\InvoiceRepository;

/**
 * Class Printpdf
 * @package Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order\Invoice
 * @SuppressWarnings("CouplingBetweenObjects")
 * @SuppressWarnings("ExcessiveParameterList")
 */
class Printpdf extends Abstractpdf
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::sales_invoice';

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var FileFactory
     */

    private $fileFactory;
    /**
     * @var ForwardFactory
     */

    private $resultForwardFactory;

    /**
     * @var Pdf
     */
    private $helper;

    /**
     * @var PdfgeneratorRepository
     */
    private $pdfGeneratorRepository;

    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * Printpdf constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param Pdf $helper
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param ForwardFactory $resultForwardFactory
     * @param PdfgeneratorRepository $pdfGeneratorRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $emailConfig,
        JsonFactory $resultJsonFactory,
        Pdf $helper,
        DateTime $dateTime,
        FileFactory $fileFactory,
        ForwardFactory $resultForwardFactory,
        PdfgeneratorRepository $pdfGeneratorRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->fileFactory = $fileFactory;
        $this->helper = $helper;
        parent::__construct($context, $coreRegistry, $emailConfig, $resultJsonFactory);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->dateTime = $dateTime;
        $this->pdfGeneratorRepository = $pdfGeneratorRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @return object
     */
    public function execute()
    {

        $templateId = $this->getRequest()->getParam('template_id');

        if (!$templateId) {
            return $this->returnNoRoute();
        }

        $templateModel = $this->pdfGeneratorRepository
            ->getById($templateId);

        if (!$templateModel) {
            return $this->returnNoRoute();
        }

        $invoiceId = $this->getRequest()->getParam('invoice_id');
        if (!$invoiceId) {
            return $this->returnNoRoute();
        }

        $invoice = $this->invoiceRepository
            ->get($invoiceId);
        if (!$invoice) {
            return $this->returnNoRoute();
        }

        $helper = $this->helper;

        $helper->setInvoice($invoice);
        $helper->setTemplate($templateModel);

        $pdfFileData = $helper->template2Pdf();

        $date = $this->dateTime->date('Y-m-d_H-i-s');

        $fileName = $pdfFileData['filename'] . $date . '.pdf';

        return $this->fileFactory->create(
            $fileName,
            $pdfFileData['filestream'],
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }

    /**
     * @return $this
     */
    private function returnNoRoute()
    {
        return $this->resultForwardFactory->create()->forward('noroute');
    }
}
