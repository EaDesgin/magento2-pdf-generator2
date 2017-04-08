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
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


namespace Eadesigndev\Pdfgenerator\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for the module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings("unused")
     * @SuppressWarnings("ExcessiveMethodLength")
     */
    //@codingStandardsIgnoreLine
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if ($installer->tableExists('eadesign_pdf_templates')) {
            $installer->endSetup();
            return;
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable('eadesign_pdf_templates'))
            ->addColumn(
                'template_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Template Id'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Template active?'
            )
            ->addColumn(
                'template_name',
                Table::TYPE_TEXT,
                100,
                ['nullable' => false],
                'Template name'
            )
            ->addColumn(
                'template_description',
                Table::TYPE_TEXT,
                500,
                ['nullable' => false],
                'Template description'
            )
            ->addColumn(
                'template_default',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => '0'],
                'Template default'
            )
            ->addColumn(
                'template_type',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Template type'
            )
            ->addColumn('template_body', Table::TYPE_TEXT, '2M', [], 'Template body')
            ->addColumn('template_header', Table::TYPE_TEXT, '2M', [], 'Template header')
            ->addColumn('template_footer', Table::TYPE_TEXT, '2M', [], 'Template footer')
            ->addColumn('template_css', Table::TYPE_TEXT, 500, ['nullable' => false], 'Template css')
            ->addColumn('template_file_name', Table::TYPE_TEXT, 100, ['nullable' => false], 'Template file name')
            ->addColumn(
                'template_paper_form',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Paper format'
            )
            ->addColumn(
                'template_custom_form',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Paper custom format'
            )
            ->addColumn(
                'template_custom_h',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template height'
            )
            ->addColumn(
                'template_custom_w',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template width'
            )
            ->addColumn(
                'template_custom_t',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template top margin'
            )
            ->addColumn(
                'template_custom_b',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template bottom margin'
            )
            ->addColumn(
                'template_custom_l',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template left margin'
            )
            ->addColumn(
                'template_custom_r',
                Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'default' => '1'],
                'Custom template right margin'
            )
            ->addColumn(
                'template_paper_ori',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Paper orientation'
            )
            ->addColumn('creation_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false], 'Creation Time')
            ->addColumn('update_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false], 'Update Time')
            ->addIndex($installer->getIdxName('template_id', ['template_id']), ['template_id'])
            ->setComment('Eadesign PDF Generator Installer');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('eadesign_pdf_store')
        )->addColumn(
            'template_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Template ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('eadesign_pdf_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('eadesign_pdf_store', 'template_id', 'eadesign_pdf_templates', 'template_id'),
            'template_id',
            $installer->getTable('eadesign_pdf_templates'),
            'template_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('eadesign_pdf_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'PDF Generator To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
