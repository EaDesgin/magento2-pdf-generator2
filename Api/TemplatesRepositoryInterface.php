<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Api;

use \Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterface;

interface TemplatesRepositoryInterface
{

    /**
     * @param TemplatesInterface $templates
     * @return mixed
     */
    public function save(TemplatesInterface $templates);

    /**
     * @param $value the template id
     * @return mixed
     */
    public function getById($value);

    /**
     * @param TemplatesInterface $templates
     * @return mixed
     */
    public function delete(TemplatesInterface $templates);

    /**
     * @param $value the template id
     * @return mixed
     */
    public function deleteById($value);
}
