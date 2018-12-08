<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order;

use \Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;

/**
 * Class Abstractpdf
 * @package Eadesigndev\Pdfgenerator\Controller\Adminhtml\Order
 * @SuppressWarnings("FoundProtected")
 */
abstract class Abstractpdf extends Action
{

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var Config
     */
    private $emailConfig;

    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * Abstractpdf constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $emailConfig
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $emailConfig,
        JsonFactory $resultJsonFactory
    ) {

        $this->emailConfig = $emailConfig;
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
    }
}
