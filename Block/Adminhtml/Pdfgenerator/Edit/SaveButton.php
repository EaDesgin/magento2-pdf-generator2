<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Block\Adminhtml\Pdfgenerator\Edit;

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];

        if ($this->_isAllowedAction(Templates::ADMIN_RESOURCE_SAVE)) {
            return $data = [
                'label' => __('Save Template'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save']],
                    'form-role' => 'save',
                ],
                'sort_order' => 90,
            ];
        }

        return $data;
    }
}
