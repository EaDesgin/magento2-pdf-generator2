<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model\Plugin;

use Eadesigndev\Pdfgenerator\Helper\Data;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Registry;

class Printinvoice
{

    /**
     * @var UrlInterface
     */
    private $urlInterface;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * Printinvoice constructor.
     * @param Registry $coreRegistry
     * @param UrlInterface $urlInterface
     * @param Data $dataHelper
     */
    public function __construct(
        Registry $coreRegistry,
        UrlInterface $urlInterface,
        Data $dataHelper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->urlInterface = $urlInterface;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->coreRegistry->registry('current_invoice');
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     * @SuppressWarnings("unused")
     */
    //@codingStandardsIgnoreLine
    public function afterGetPrintUrl($subject, $result)
    {
        if (!$this->dataHelper->isEnable()) {
            return $result;
        }

        $lastItem = $this->dataHelper->getTemplateStatus($this->getInvoice());

        if (empty($lastItem->getId())) {
            return $result;
        }

        return $this->printPDF($lastItem);
    }

    /**
     * @param $lastItem
     * @return string
     */
    public function printPDF($lastItem)
    {
        return $this->urlInterface->getUrl(
            'pdfgenerator/*/printpdf',
            [
                'template_id' => $lastItem->getId(),
                'order_id' => $this->getInvoice()->getOrder()->getId(),
                'invoice_id' => $this->getInvoice()->getId()
            ]
        );
    }
}
