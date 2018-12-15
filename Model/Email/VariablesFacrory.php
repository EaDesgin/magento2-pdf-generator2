<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model\Email;

use Eadesigndev\Pdfgenerator\Model\FactoryInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class VariablesFacrory
 * @package Eadesigndev\Pdfgenerator\Model\Email
 * @deprecated
 */
class VariablesFacrory implements FactoryInterface
{
    private $objectManager = null;

    private $instanceName = null;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
        if (class_exists(\Magento\Email\Model\Source\Variables::class)) {
            $this->instanceName = \Magento\Email\Model\Source\Variables::class;
        }

        if (class_exists(\Magento\Variable\Model\Source\Variables::class)) {
            $this->instanceName = \Magento\Variable\Model\Source\Variables::class;
        }
    }

    public function create(array $data = [])
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}
