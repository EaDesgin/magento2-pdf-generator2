<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    custom_ext_code
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Pdfgenerator\Helper;

use mPDF;


class Eapdf extends mPDF
{

    CONST PAPER_ORI = [
        1 => 'P',
        2 => 'L'
    ];

    /**
     * @var string
     */
    public $mode = '';

    /**
     * @var string
     */
    public $format = 'A4';

    /**
     * @var int
     */
    public $defaultFontSize = 0;

    /**
     * @var string
     */
    public $defaultFont = '';

    public $mgl = 150;

    public $mgr = 15;

    public $mgt = 16;

    public $mgb = 15;

    public $mgh = 9;

    public $mgf = 9;

    public $orientation = 'L';

    public $test;



    public function __construct(
        $mode = '',
        $format = 'A4',
        $default_font_size = 0,
        $default_font = '',
        $mgl,
        $mgr = 15,
        $mgt = 16,
        $mgb = 16,
        $mgh = 9,
        $mgf = 9,
        $orientation = 'L'
    )
    {

    }

    public function setMgl($mgl)
    {
        $this->mgl = $mgl;
        return $this;
    }


    public function getMgl()
    {
        return $this->mgl;
    }

}
