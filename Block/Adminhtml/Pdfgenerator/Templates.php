<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Block\Adminhtml\Pdfgenerator;

use Magento\Backend\Block\Widget\Grid\Container;

class Templates extends Container
{

    /**
     * @return void;
     */
    //@codingStandardsIgnoreLine
    public function _construct()
    {

        $this->_controller = 'adminhtml_pdfgenerator';
        $this->_blockGroup = 'Eadesigndev_Pdfgenerator';

        $this->_headerText = __('PDF Templates');
        $this->_addButtonLabel = __('Add New Template');
        parent::_construct();
        $this->buttonList->add(
            'template_apply',
            [
                'label' => __('Template'),
                'onclick' => "location.href='" . $this->getUrl('pdfgenerator/*/template') . "'",
                'class' => 'apply'
            ]
        );
    }

    /**
     * @param $resourceId
     * @return bool
     */
    //@codingStandardsIgnoreLine
    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
