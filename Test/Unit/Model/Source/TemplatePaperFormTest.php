<?php
/**
 * Created by PhpStorm.
 * User: euser
 * Date: 1/14/19
 * Time: 12:36 PM
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Model\Source;

use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperForm;
use PHPUnit\Framework\TestCase;

class TemplatePaperFormTest extends TestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = new TemplatePaperForm();
    }

    public function testGetAvailable()
    {
        $availableValues = $this->subject->getAvailable();
        $this->assertEquals(6,count($availableValues));

    }
}
