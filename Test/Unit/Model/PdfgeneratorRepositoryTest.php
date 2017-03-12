<?php
/**
 * Copyright © 2017 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Model;

use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator as PdfgeneratorResourceModel;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory;
use Eadesigndev\Pdfgenerator\Model\Pdfgenerator as PdfgeneratorModel;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Message\ManagerInterface;

/**
 * Test for \Pdfgenerator\Model\PdfgeneratorRepository
 * Class PdfgeneratorRepositoryTest
 * @package Eadesigndev\Pdfgenerator\Test\Integration
 */
class PdfgeneratorRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var /Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
     */
    public $objectManager;

    /**
     * @var PdfgeneratorRepository
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator
     */
    private $pdfGeneratorResourceModel;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory
     */
    private $pdfGeneratorFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Eadesigndev\Pdfgenerator\Api\Data\TemplatesInterface;
     */
    private $pdfGenerator;

    public function setUp()
    {

        $this->objectManager = new ObjectManager($this);

        $this->pdfGeneratorResourceModel = $this->getMockBuilder(PdfgeneratorResourceModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdfGeneratorFactory = $this->getMockBuilder(PdfgeneratorFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        /** @var PdfgeneratorModel pdfGenerator */
        $this->pdfGenerator = $this->objectManager->getObject(PdfgeneratorModel::class);

        $this->pdfGeneratorFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->pdfGenerator);

        $messageManager = $this->getMockBuilder(ManagerInterface::class)->getMock();

        $this->repository = new PdfgeneratorRepository(
            $this->pdfGeneratorResourceModel,
            $this->pdfGenerator,
            $this->pdfGeneratorFactory,
            $messageManager
        );
    }

    public function testSave()
    {
        $this->pdfGeneratorResourceModel
            ->expects($this->once())
            ->method('save')
            ->with($this->pdfGenerator)
            ->willReturnSelf();

        $this->assertEquals($this->pdfGenerator, $this->repository->save($this->pdfGenerator));
    }

    public function testGetById()
    {
        $id = 1;
        $this->pdfGeneratorResourceModel
            ->expects($this->once())
            ->method('load')
            ->with($this->pdfGenerator->setEntityId($id))
            ->willReturnSelf();

        $this->assertEquals($this->pdfGenerator, $this->repository->getById($id));
    }

    public function testDelete()
    {

        $this->pdfGeneratorResourceModel
            ->expects($this->once())
            ->method('delete')
            ->with($this->pdfGenerator)
            ->willReturnSelf();

        $this->assertTrue($this->repository->delete($this->pdfGenerator));
    }

    public function testDeleteById()
    {
        $id = 1;

        $this->pdfGeneratorResourceModel
            ->expects($this->once())
            ->method('load')
            ->with($this->pdfGenerator->setEntityId($id))
            ->willReturnSelf();

        $this->assertTrue($this->repository->deleteById($id));
    }
}
