<?php
/**
 * Copyright © 2017 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Helper;

use Eadesigndev\Pdfgenerator\Helper\Data as DataHelper;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory as PdfGeneratorCollectionFactory;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection as PdfGeneratorCollection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Test
 * @package Eadesigndev\Pdfgenerator\Test\Unit\Helper
 */
class TestData extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigInterface;

    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    private $context;
    /**
     * @var PdfGeneratorCollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pdfGeneratorCollectionFactory;

    /**
     * @var PdfGeneratorCollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pdfGeneratorCollection;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var DataHelper
     */
    private $dataHelper;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->context = $this->getMockBuilder(Context::class)
            ->setMethods(['getScopeConfig'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->setMethods(['getValue', 'isSetFlag'])
            ->getMockForAbstractClass();

        $this->context
            ->expects($this->atLeastOnce())
            ->method('getScopeConfig')
            ->will($this->returnValue($this->scopeConfigInterface));

        $this->pdfGeneratorCollectionFactory = $this->getMockBuilder(PdfGeneratorCollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdfGeneratorCollection = $this->getMockBuilder(PdfGeneratorCollection::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->pdfGeneratorCollectionFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->pdfGeneratorCollection));

        $this->dataHelper = new DataHelper(
            $this->context,
            $this->pdfGeneratorCollectionFactory
        );
    }

    public function testIsNotEnable()
    {
        if (!class_exists('mPDF')) {
            $this->fail('The class mMPDF must be available');
        }

        $this->pdfGeneratorCollection->expects($this->once())->method('count')->will($this->returnValue(0));
        $this->assertFalse($this->dataHelper->isEnable());
    }

    public function testIsEnable()
    {
        $this->pdfGeneratorCollection->expects($this->once())->method('count')->will($this->returnValue(122));
        $this->scopeConfigInterface->expects($this->once())->method('getValue')->willReturn(true);
        $this->assertTrue($this->dataHelper->isEnable());
    }
}
