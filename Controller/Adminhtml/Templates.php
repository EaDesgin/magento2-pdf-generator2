<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class Templates extends Action
{

    const ADMIN_RESOURCE_VIEW = 'Eadesigndev_Pdfgenerator::templates';
    const ADMIN_RESOURCE_SAVE = 'Eadesigndev_Pdfgenerator::save';

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Eadesigndev_Pdfgenerator::template_list')
            ->addBreadcrumb(__('EaDesign PDF Generator Templates'), __('EaDesign PDF Generator Templates'));

        return $resultPage;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE_VIEW);
    }
}
