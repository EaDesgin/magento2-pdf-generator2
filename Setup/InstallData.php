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

namespace Eadesigndev\Pdfgenerator\Setup;

use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperForm;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;

/**
 * Class InstallData
 * @package Eadesigndev\Pdfgenerator\Setup
 * Adds the templates default on module install
 */
class InstallData implements InstallDataInterface
{

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var PdfgeneratorFactory
     */
    private $templateFactory;

    /**
     * @var TemplateRepository
     */
    private $templateRepository;

    /**
     * InstallData constructor.
     * @param StoreManagerInterface $storeManager
     * @param PdfgeneratorFactory $templateFactory
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        PdfgeneratorFactory $templateFactory,
        TemplateRepository $templateRepository
    ) {
        $this->storeManager = $storeManager;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings("unused")
     * @SuppressWarnings("ExcessiveMethodLength")
     */
    //@codingStandardsIgnoreLine
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $storeId = $this->storeManager->getStore()->getId();

        $templates = [
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Invoice Template Portrait!',
                'template_description' => 'The template for invoice default',
                'template_default' => 1,
                'template_type' => 1,
                // @codingStandardsIgnoreStart
                'template_body' => '
                                                    <div>
                                        <div class="body"
                                             style="position: fixed; width: 100%; height: 29.7cm; margin: 0 auto; color: #555555; background: #FFFFFF; font-family: Arial; font-size: 14px;">
                                            <div class="clearfix header" style="padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #AAAAAA;">
                                                <div id="company" style="float: right; text-align: right; width: 49%;">
                                                    <h2 class="name"
                                                        style="font-size: 1.4em; font-weight: normal; margin: 0;">
                                                        EaDesign Web Development
                                                    </h2>
                                                    <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>
                                                    <div>0232 272221</div>
                                                    <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>
                                                </div>
                                            </div>
                                            <div class="main">
                                                <div id="details" class="clearfix" style="margin-bottom: 50px;">
                                                    <div id="client" style="padding-left: 6px; border-left: 6px solid #000; float: left; width: 49%;">
                                                        <div class="to" style="color: #777777;">INVOICE TO:</div>
                                                        <div class="name">{{var formattedBillingAddress|raw}}</div>
                                                    </div>
                                                    <div id="invoice" style="float: right; text-align: right; width: 49%;"><h1
                                                            style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                                                        INVOICE {{var invoice.increment_id}}</h1>
                                                        <div class="date" style="font-size: 1.1em; color: #777777;">
                                                            Date of Invoice:{{var invoice.created_at}}
                                                        </div>
                                                        <div class="date" style="font-size: 1.1em; color: #777777;">Status: {{var order.status}}</div>
                                                    </div>
                                                </div>
                                                {{layout area="frontend" handle="sales_email_order_invoice_items" invoice=$invoice order=$order}}
                                                <div id="thanks" style="font-size: 2em; margin-bottom: 50px;">Thank you!</div>
                                            </div>
                                        </div>
                                    </div>
',
                'template_header' => 'This is the header',
                'template_footer' => '
                                                    <h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>\', \'
                                    <div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is
                                        valid without the signature and seal.
                                    </div>
                                    <div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>\'

',
                'template_css' => '
                @font-face {  font-family: Arial;}.clearfix:after {  content: "";  display: table;  clear: both;}a {  color: #000;  text-decoration: none;}table {  width: 100%;  border-collapse: collapse;  border-spacing: 0;  margin-bottom: 20px;}table th, table td{  background: #EEEEEE;  text-align: center;  border-bottom: 1px solid #FFFFFF;}table thead th{font-weight: normal;text-transform: uppercase;padding-top: 5px;padding-bottom: 5px;}table thead th.item-info, table thead th.item-subtotal {  border: none;  background: #ff8b00;  color: #fff;}table thead th.item-info{text-align: left;padding-left: 10px;}table thead th.item-qty{padding-left: 10px;padding-right: 10px;}table thead th.item-subtotal{text-align: right;padding-right: 10px;}table tfoot {background: #FFFFFF;text-align: right;}table tfoot td, table tfoot th {  padding: 10px 20px;  background: #FFFFFF;  border-bottom: none;  font-size: 1em;  white-space: nowrap;  border-top: 1px solid #AAAAAA;  text-align: right;  text-transform: uppercase;  font-weight: normal;}table tbody tr td:nth-child(1) {  text-align: left;  background: #ff8b00;  color: #000;  padding-left: 10px;  border-top: 1px solid #AAAAAA;  padding-top: 5px;  padding-bottom: 5px;}table tbody tr td:nth-child(3) {  text-align: right;  background: #ff8b00;  color: #fff;  padding-right: 10px;  border-top: 1px solid #AAAAAA;  padding-top: 5px;  padding-bottom: 5px;}
                ',
                'template_file_name' => 'invoice {{var ea_invoice_id}} {{var ea_invoice_date}} {{var ea_invoice_status}} ',
                // @codingStandardsIgnoreEnd
                'template_paper_form' => TemplatePaperForm::TEMAPLATE_PAPER_FORM_A4,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 15,
                'template_custom_b' => 15,
                'template_custom_l' => 15,
                'template_custom_r' => 15,
                'template_paper_ori' => 1,
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 0,
                'template_name' => 'Invoice Template Landscape!',
                'template_description' => 'The template for invoice default',
                'template_default' => 0,
                'template_type' => 1,
                // @codingStandardsIgnoreStart
                'template_body' => '
<div><div class="body" style="position: fixed; width: 100%; height: 29.7cm; margin: 0 auto; color: #555555; background: #FFFFFF; font-family: Arial; font-size: 14px;"><div class="clearfix header" style="padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #AAAAAA;"><div id="company" style="float: right; text-align: right; width: 49%;"><h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2><div>Lascar Catargi nr.10, et.1,Iasi,Romania</div><div>0232 272221</div><div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div></div></div><div class="main"><div id="details" class="clearfix" style="margin-bottom: 50px;"><div id="client" style="padding-left: 6px; border-left: 6px solid #000; float: left; width: 49%;"><div class="to" style="color: #777777;">INVOICE TO:</div><div class="name">{{var formattedBillingAddress|raw}}</div></div><div id="invoice" style="float: right; text-align: right; width: 49%;"><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">INVOICE {{var invoice.increment_id}}</h1><div class="date" style="font-size: 1.1em; color: #777777;">Date of Invoice:{{var invoice.created_at}}</div><div class="date" style="font-size: 1.1em; color: #777777;">Status: {{var order.status}}</div></div></div>  {{layout area="frontend" handle="sales_email_order_invoice_items" invoice=$invoice order=$order}}<div id="thanks" style="font-size: 2em; margin-bottom: 50px;">Thank you!</div></div></div></div>'
                ,
                'template_header' => 'This is the header',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '
                @font-face {  font-family: Arial;}.clearfix:after {  content: "";  display: table;  clear: both;}a {  color: #000;  text-decoration: none;}table {  width: 100%;  border-collapse: collapse;  border-spacing: 0;  margin-bottom: 20px;}table th, table td{  background: #EEEEEE;  text-align: center;  border-bottom: 1px solid #FFFFFF;}table thead th{font-weight: normal;text-transform: uppercase;padding-top: 5px;padding-bottom: 5px;}table thead th.item-info, table thead th.item-subtotal {  border: none;  background: #ff8b00;  color: #fff;}table thead th.item-info{text-align: left;padding-left: 10px;}table thead th.item-subtotal{text-align: right;padding-right: 10px;}table tfoot {background: #FFFFFF;text-align: right;}table tfoot td, table tfoot th {  padding: 10px 20px;  background: #FFFFFF;  border-bottom: none;  font-size: 1em;  white-space: nowrap;  border-top: 1px solid #AAAAAA;  text-align: right;  text-transform: uppercase;  font-weight: normal;}table tbody tr td:nth-child(1) {  text-align: left;  background: #ff8b00;  color: #000;  padding-left: 10px;  border-top: 1px solid #AAAAAA;  padding-top: 5px;  padding-bottom: 5px;}table tbody tr td:nth-child(3) {  text-align: right;  background: #ff8b00;  color: #fff;  padding-right: 10px;  border-top: 1px solid #AAAAAA;  padding-top: 5px;  padding-bottom: 5px;}',
                'template_file_name' => 'invoice {{var ea_invoice_id}} {{var ea_invoice_date}} {{var ea_invoice_status}} ',
                // @codingStandardsIgnoreEnd
                'template_paper_form' => 2,
                'template_custom_form' => 0,
                'template_custom_h' => 10,
                'template_custom_w' => 25,
                'template_custom_t' => 10,
                'template_custom_b' => 10,
                'template_custom_l' => 15,
                'template_custom_r' => 15,
                'template_paper_ori' => 1,
                'creation_time' => time(),
                'update_time' => time(),
            ]
        ];

        foreach ($templates as $template) {
            $tmpl = $this->templateFactory->create();
            $tmpl->setData($template);
            //@codingStandardsIgnoreLine
            $this->templateRepository->save($tmpl);
        }
    }
}
