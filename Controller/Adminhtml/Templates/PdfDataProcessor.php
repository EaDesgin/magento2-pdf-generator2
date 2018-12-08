<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
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
