<?php
/**
 * Created by PhpStorm.
 * User: euser
 * Date: 1/14/19
 * Time: 11:33 AM
 */

namespace Eadesigndev\Pdfgenerator\Test\Unit\Model\Source;

use Eadesigndev\Pdfgenerator\Model\Source\TemplateActive;
use PHPUnit\Framework\TestCase;

class TemplateActiveTest extends TestCase
{

    private $subject;

    private $available = [0 => 'test0', 1 => 'test1'];

    public function setUp()
    {
        $this->subject = new TemplateActive();
    }

    public function testGetAvailable()
    {
        $availableValues = $this->subject->getAvailable();
        foreach ($availableValues as $key => $value) {
            $this->assertTrue(in_array($key, array_keys($this->available)));
        }
    }
}
