<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Block\Adminhtml\Pdfgenerator\Edit;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->_isAllowedAction(Templates::ADMIN_RESOURCE_SAVE)) {
            $data = [];
            if ($this->getTemplateId()) {
                $data = [
                    'label' => __('Delete Template'),
                    'class' => 'delete',
                    'on_click' => 'deleteConfirm(\'' .
                        __(
                            'Are you sure you want to do this?'
                        ) .
                        '\', \'' .
                        $this->getDeleteUrl() .
                        '\')',
                    'sort_order' => 20,
                ];
            }
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['template_id' => $this->getTemplateId()]);
    }
}
