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

namespace Eadesigndev\Pdfgenerator\Model\Email;

use Eadesigndev\Pdfgenerator\Model\Pdfgenerator;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Eadesigndev\Pdfgenerator\Helper\Pdf;
use Eadesigndev\Pdfgenerator\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order\Invoice;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{

    /**
     * @var Pdf
     */
    private $helper;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * SenderBuilder constructor.
     * @param Template $templateContainer
     * @param IdentityInterface $identityContainer
     * @param TransportBuilder $transportBuilder
     * @param Pdf $helper
     * @param Data $dataHelper
     * @param DateTime $dateTime
     */
    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        TransportBuilder $transportBuilder,
        Pdf $helper,
        Data $dataHelper,
        DateTime $dateTime
    ) {
        $this->helper = $helper;
        $this->dataHelper = $dataHelper;
        $this->dateTime = $dateTime;
        parent::__construct($templateContainer, $identityContainer, $transportBuilder);
    }

    /**
     * Add attachment to the main mail
     */
    public function send()
    {
        $vars = $this->templateContainer->getTemplateVars();
        $this->_checkInvoice($vars);

        parent::send();
    }

    /**
     * Add attachment to the css/bcc mail
     */
    public function sendCopyTo()
    {
        $vars = $this->templateContainer->getTemplateVars();
        $this->_checkInvoice($vars);
        parent::sendCopyTo();
    }

    /**
     *
     * Check if we need to send the invoice email
     *
     * @param $vars
     * @return $this
     */
    private function _checkInvoice($vars)
    {
        if (!$this->dataHelper->isEmail()) {
            return $this;
        }

        if (!array_key_exists('invoice', $vars)) {
            return $this;
        }

        if ($vars['invoice'] instanceof Invoice) {
            $invoice = $vars['invoice'];
            $helper = $this->helper;

            $helper->setInvoice($invoice);

            /** @var Pdfgenerator $template */
            $template = $this->dataHelper->getTemplateStatus($invoice);

            if (empty($template->getId())) {
                return $this;
            }

            $helper->setTemplate($template);

            $pdfFileData = $helper->template2Pdf();

            $date = $this->dateTime->date('Y-m-d_H-i-s');

            $this->transportBuilder->addAttachment(
                $pdfFileData['filestream'],
                \Zend_Mime::TYPE_OCTETSTREAM,
                \Zend_Mime::DISPOSITION_ATTACHMENT,
                \Zend_Mime::ENCODING_BASE64,
                $pdfFileData['filename'] . $date . '.pdf'
            );
        }

        return $this;
    }
}
