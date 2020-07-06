<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model\Template;

use Magento\Email\Model\Template;
use Magento\Framework\App\Area;
use Magento\Framework\DataObject;

/**
 * Class Processor
 * @package Eadesigndev\Pdfgenerator\Model\Template
 */
class Processor extends Template
{

    /**
     * @var store id;
     */
    private $storeId;

    /**
     * Configuration of design package for template
     *
     * @var DataObject
     */
    private $designConfig;

    /**
     * @return mixed
     * get the pdf template body
     */
    public function getTemplateBody()
    {
        return $this->getTemplate()->getTemplateBody();
    }

    /**
     * @return mixed
     */
    public function getTemplateHeader()
    {
        return $this->getTemplate()->getTemplateHeader();
    }

    /**
     * @return mixed
     */
    public function getTemplateFooter()
    {
        return $this->getTemplate()->getTemplateFooter();
    }

    /**
     * @return mixed
     */
    public function getTemplateFileName()
    {
        return $this->getTemplate()->getTemplateFileName();
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
            ->setPlainTemplateMode($this->isPlain())
            ->setIsChildTemplate($this->isChildTemplate())
            ->setTemplateProcessor([$this, 'getTemplateContent']);

        $processor->setVariables($this->getVariables());
        $this->setUseAbsoluteLinks(true);
        $html = $this->html($processor);

        if ($isDesignApplied) {
            $this->cancelDesignConfig();
        }

        return $html;
    }

    /**
     * @param $processor
     * @param $area
     * @return mixed
     */
    private function processArea($processor, $area)
    {
        $textProcessor = $processor
            ->setStoreId($this->storeId)
            ->setDesignParams([0])
            ->filter(__($area));

        return $textProcessor;
    }

    /**
     * @param $processor
     * @return array
     */
    private function html($processor)
    {
        $html = [
            'body' => $this->processArea($processor, $this->getTemplateBody()),
            'header' => $this->processArea($processor, $this->getTemplateHeader()),
            'footer' => $this->processArea($processor, $this->getTemplateFooter()),
            'filename' => $this->processArea($processor, $this->getTemplateFileName()),
        ];

        return $html;
    }

    /**
     * Get design configuration data
     *
     * @return DataObject
     */
    public function getDesignConfig()
    {
        $templates = $this->getTemplate()->getData('store_id');
        $this->storeId = $templates[0];

        if ($this->designConfig === null) {
            //@codingStandardsIgnoreLine
            $this->designConfig = new DataObject(
                ['area' => Area::AREA_FRONTEND, 'store' => $this->storeId]
            );
        }

        return $this->designConfig;
    }
}
