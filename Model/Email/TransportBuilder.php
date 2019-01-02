<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model\Email;

use Magento\Framework\Mail\Template\TransportBuilder as TransportBuilderParent;

class TransportBuilder extends TransportBuilderParent
{

    public function addAttachment($content, $fileName)
    {
        $this->message->setBodyAttachment($content, $fileName);
        return $this;
    }
}
