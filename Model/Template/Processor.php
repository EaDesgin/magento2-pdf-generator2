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

namespace Eadesigndev\Pdfgenerator\Model\Template;

use Magento\Email\Model\Template;
use Magento\Framework\DataObject;

/**
 * Class Processor
 * @package Eadesigndev\Pdfgenerator\Model\Template
 */
class Processor extends Template
{

    /**
     * Configuration of design package for template
     *
     * @var DataObject
     */
    protected $designConfig;

    /**
     * @var $area ;
     */
    protected $area = 'frontend';


    /**
     * @return mixed
     * get the pdf template body
     */
    public function getTemplateBody()
    {
        return $this->getTemplate()->getTemplateBody();
    }

    /**
     * Get processed template
     *
     * @return string
     * @throws \Magento\Framework\Exception\MailException
     */
    public function processTemplate()
    {
        // Support theme fallback for email templates
        $isDesignApplied = $this->applyDesignConfig();


        $processor = $this->getTemplateFilter()
            ->setUseSessionInUrl(false)
            ->setPlainTemplateMode($this->isPlain())
            ->setIsChildTemplate($this->isChildTemplate())
            ->setTemplateProcessor([$this, 'getTemplateContent']);


        $processor->setVariables($this->getVariables());

        $this->setUseAbsoluteLinks(true);
        $text = $processor
            ->setStoreId(1)
            ->setDesignParams(array(0))
            ->filter(__($this->getTemplateBody()));

        if ($isDesignApplied) {
            $this->cancelDesignConfig();
        }

        return $text;
    }


    /**
     * Get design configuration data
     *
     * @return DataObject
     */
    public function getDesignConfig()
    {

        //todo look at the template design based on the select;


        $templates = $this->getTemplate()->getData('store_id');
        $store = $templates[0];

        if ($this->designConfig === null) {
            $this->designConfig = new DataObject(
                ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store]
            );
        }
        return $this->designConfig;
    }

}
