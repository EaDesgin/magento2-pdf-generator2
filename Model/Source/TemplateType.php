<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model\Source;

use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

/**
 * Class PageLayout
 */
class TemplateType extends AbstractSource
{
    
    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    private $pageLayoutBuilder;

    /**
     * Constructor
     *
     * @param BuilderInterface $pageLayoutBuilder
     */
    public function __construct(BuilderInterface $pageLayoutBuilder)
    {
        $this->pageLayoutBuilder = $pageLayoutBuilder;
    }

    /**
     * Types
     */
    const TYPE_INVOICE = 1;

    /**
     * Prepare post's statuses.
     *
     * @return array
     */
    public function getAvailable()
    {
        return [self::TYPE_INVOICE => __('Invoice')];
    }
}
