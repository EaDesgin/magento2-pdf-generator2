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

namespace Eadesigndev\Opicmsppdfgenerator\Block\Sales\Shipment;

use Eadesigndev\Opicmsppdfgenerator\Helper\Data;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

class Items extends \Magento\Shipping\Block\Items
{

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    private $lastitem;

    /**
     * Items constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->helper = $helper;
    }

    /**
     * @param $source
     * @return bool
     */
    public function addPDFLink($source)
    {
        $helper = $this->helper;

        if ($helper->isEnable()) {
            $lastItem = $helper->getTemplateStatus(
                $source,
                TemplateType::TYPE_SHIPMENT
            );

            if (!empty($lastItem->getId())) {
                $this->lastitem = $lastItem;
                return true;
            }
        }

        return false;
    }

    /**
     * @param $source
     * @return string
     */
    public function getPrintPDFUrl($source)
    {
        return $this->getUrl('opicmsppdfgenerator/index/index', [
            'template_id' => $this->lastitem->getId(),
            'order_id' => $source->getOrder()->getId(),
            'source_id' => $source->getId()
        ]);
    }
}
