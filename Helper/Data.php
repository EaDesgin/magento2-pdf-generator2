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

/**
 * Handles the config and other settings
 *
 * Class Data
 * @package Eadesigndev\Pdfgenerator\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const ENABLE = 'eadesign_pdfgenerator/general/enabled';
    const EMAIL = 'eadesign_pdfgenerator/general/email';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_config;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    )
    {

        $this->_config = $context->getScopeConfig();
        parent::__construct($context);

    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return string
     */
    public function getConfig($configPath)
    {
        return $this->_config->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if module is enable
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->getConfig(self::ENABLE);
    }


    /**
     * Check if module will send email on new invoice or invoice update
     *
     * @return boolean
     */
    public function isEmail()
    {
        if ($this->isEnable()) {
            return $this->getConfig(self::EMAIL);
        }
        return false;

    }

}
