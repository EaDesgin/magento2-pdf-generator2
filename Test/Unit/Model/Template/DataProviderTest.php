<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Model\Template;

use Eadesigndev\Pdfgenerator\Model\Pdfgenerator;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory;
use Eadesigndev\Pdfgenerator\Model\Template\DataProvider;
use Magento\Framework\App\Request\DataPersistor;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    private $subject;

    private $collection;

    private $dataPersistorMock;

    public function setUp()
    {
        $name = 'template_form_data_source';
        $primaryFieldName = 'template_id';
        $requestFieldName = 'template_id';

        $this->dataPersistorMock = $this->getMockBuilder(DataPersistor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $templateCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $templateCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems', 'getNewEmptyItem'])
            ->getMock();

        $templateCollectionFactory->expects($this->once())
            ->method('create')->willReturn($templateCollection);

        $this->collection = $templateCollection;

        $this->subject = new DataProvider(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $templateCollectionFactory,
            $this->dataPersistorMock
        );
    }

    public function testGetData()
    {

        $modelData = ['template_id' => 1, 'template_name' => 'Pdf template'];

        $templateModel = $this->getMockBuilder(Pdfgenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $templateModel->expects($this->atLeastOnce())
            ->method('getId')->willReturn(1);

        $templateModel->expects($this->atLeastOnce())
            ->method('getData')->willReturn($modelData);

        $this->collection->expects($this->exactly(1))
            ->method('getItems')->willReturn([$templateModel]);

        $this->collection->expects($this->once())
            ->method('getNewEmptyItem')->willReturn($templateModel);

        $this->dataPersistorMock->expects($this->once())
            ->method('get')
            ->with('pdfgenerator_template')
            ->willReturn($modelData);

        $data = $this->subject->getData();

        $this->assertEquals([1 => $modelData], $data);
    }
}
