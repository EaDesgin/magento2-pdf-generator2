<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model;

use Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterface;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator as PdfgeneratorClass;
use Magento\Framework\Model\AbstractModel;

class Pdfgenerator extends AbstractModel implements TemplatesInterface
{

    /**
     * Init resource model for the templates
     * @return void
     */
    //@codingStandardsIgnoreLine
    public function _construct()
    {
        $this->_init(
            PdfgeneratorClass::class
        );
    }
}
