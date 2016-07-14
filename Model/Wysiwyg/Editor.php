<?php
namespace Eadesigndev\Pdfgenerator\Model\Wysiwyg;

use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;

class Editor extends \Magento\Framework\Data\Form\Element\Editor
{

    /**
     * Return Wysiwyg config as \Magento\Framework\DataObject
     *
     * Config options description:
     *
     * enabled:                 Enabled Visual Editor or not
     * hidden:                  Show Visual Editor on page load or not
     * use_container:           Wrap Editor contents into div or not
     * no_display:              Hide Editor container or not (related to use_container)
     * translator:              Helper to translate phrases in lib
     * files_browser_*:         Files Browser (media, images) settings
     * encode_directives:       Encode template directives with JS or not
     *
     * @param array|\Magento\Framework\DataObject $data Object constructor params to override default config values
     * @return \Magento\Framework\DataObject
     */
    public function getConfig($data = [])
    {
        $config = new \Magento\Framework\DataObject();

        $config->setData(
            [
                'enabled' => $this->isEnabled(),
                'hidden' => $this->isHidden(),
                'use_container' => false,
                'add_variables' => false,
                'add_widgets' => true,
                'no_display' => false,
                'encode_directives' => true,

                'width' => '100%',
                'height' => '500px',
                'plugins' => [],
            ]
        );


//        $this->setConfig($config);
//        return $config;
    }


}