<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
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
