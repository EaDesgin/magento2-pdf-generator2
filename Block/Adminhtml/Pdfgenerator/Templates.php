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

namespace Eadesigndev\Pdfgenerator\Block\Adminhtml\Pdfgenerator;

use Magento\Backend\Block\Widget\Grid\Container;

class Templates extends Container
{

    /**
     * @return void;
     */
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
    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
