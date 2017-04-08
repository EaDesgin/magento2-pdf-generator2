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
namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;

use Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor;

class PdfDataProcessor extends PostDataProcessor
{

    /**
     * @param array $data
     * @return array
     */
    //@codingStandardsIgnoreLine
    public function validateRequireEntry(array $data)
    {

        $requiredFields = [
            'template_name' => __('Template Name'),
            'template_description' => __('Template description'),
            'store_id' => __('Store View'),
            'template_file_name' => __('Template File Name'),
            'template_paper_ori' => __('Template Paper Orientation'),
            'template_paper_form' => __('Template Paper Form'),
            'is_active' => __('Status')
        ];

        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }

        return $data;
    }
}
