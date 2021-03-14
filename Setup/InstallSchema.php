<?php

/**
 * UNLICENSED
 *
 * @package     Delmer_Offer
 * @subpackage  Setup
 * @author      Gaël Delmer <gael.delmer@gmail.com>
 */

namespace Delmer\Offer\Setup;

use \Exception;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema
     *
     * @param SchemaSetupInterface $setup Magento schema setup instance
     * @param ModuleContextInterface $context Magento module context instance
     *
     * @throws Exception
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // create table delmer_offer
        $tableName = $installer->getTable('delmer_offer');
        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    TABLE::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true,
                    ],
                    'Id'
                )->addColumn(
                    'label',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Label'
                )->addColumn(
                    'content',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => true,
                    ],
                    'Content'
                )->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Image Link'
                )->addColumn(
                    'redirect_link',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Redirect Link'
                )->addColumn(
                    'button_text',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Button Text'
                )->addColumn(
                    'begin_date',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Begin Date'
                )->addColumn(
                    'end_date',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'End Date'
                );

            $installer->getConnection()->createTable($table);
        }

        // create table delmer_offer_category
        $tableName = $installer->getTable('delmer_offer_category');
        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'offer_id',
                    TABLE::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                    ],
                    'Offer Id'
                )->addColumn(
                    'category_id',
                    TABLE::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                    ],
                    'Category Id'
                )->addForeignKey(
                    $installer->getFkName(
                        'delmer_offer_category',
                        'offer_id',
                        'delmer_offer',
                        'id'
                    ),
                    'offer_id',
                    $installer->getTable('delmer_offer'),
                    'id',
                    Table::ACTION_CASCADE
                )
            ;

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
