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

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Registry;

class Config
{
    /**
     * Config constructor.
     * @param UrlInterface $url
     * @param Registry $registry
     */
    public function __construct(
        UrlInterface $url,
        Registry $registry
    ) {
        $this->_url = $url;
        $this->registry = $registry;
    }

    /**
     * @param $subject
     * @param $result
     * @return string
     * @SuppressWarnings("unused")
     */
    //@codingStandardsIgnoreLine
    public function afterGetVariablesWysiwygActionUrl($subject, $result)
    {

        if ($this->registry->registry('pdfgenerator_template')) {
            return $this->getUrl();
        }

        return $result;
    }

    /**
     * Returns the variable url
     * @return string
     */
    public function getUrl()
    {
        return $this->_url->getUrl('*/variable/template');
    }
}
