<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Model;

use \Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterface;
use \Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator as TemplateResource;
use \Eadesigndev\Pdfgenerator\Api\TemplatesRepositoryInterface;
use Exception;
use Magento\Framework\Message\ManagerInterface;

class PdfgeneratorRepository implements TemplatesRepositoryInterface
{

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var TemplateResource
     */
    private $resource;

    /**
     * @var TemplatesInterface
     */
    private $templatesInterface;

    /**
     * @var \Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory
     */
    private $pdfgeneratorFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * PdfgeneratorRepository constructor.
     * @param TemplateResource $resource
     * @param TemplatesInterface $templatesInterface
     * @param PdfgeneratorFactory $pdfgeneratorFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        TemplateResource $resource,
        TemplatesInterface $templatesInterface,
        PdfgeneratorFactory $pdfgeneratorFactory,
        ManagerInterface $messageManager
    ) {
        $this->resource = $resource;
        $this->templatesInterface = $templatesInterface;
        $this->pdfgeneratorFactory = $pdfgeneratorFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param TemplatesInterface|Pdfgenerator $template
     * @return TemplatesInterface
     */
    public function save(TemplatesInterface $template)
    {
        try {
            $this->resource->save($template);
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, 'There was a error');
        }

        return $template;
    }

    /**
     * @param int $templateId
     * @return mixed
     */
    public function getById($templateId)
    {
        if (!isset($this->instances[$templateId])) {
            $template = $this->pdfgeneratorFactory->create();
            $this->resource->load($template, $templateId);

            $this->instances[$templateId] = $template;
        }

        return $this->instances[$templateId];
    }

    /**
     * @param TemplatesInterface|Pdfgenerator $template
     * @return bool
     */
    public function delete(TemplatesInterface $template)
    {
        $id = $template->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($template);
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, 'There was a error');
        }

        unset($this->instances[$id]);

        return true;
    }

    /**
     * @param int $templateId
     * @return bool
     */
    public function deleteById($templateId)
    {
        $template = $this->getById($templateId);
        return $this->delete($template);
    }
}
