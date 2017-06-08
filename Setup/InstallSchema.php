<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_ProductSaleIndexer
 * @copyright   Copyright (c) 2017 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\ProductSaleIndexer\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Faonni_ProductSaleIndexer InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module Faonni_ProductSaleIndexer
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        /**
         * Create table 'faonni_catalog_product_index_sale'
         */
        if (!$installer->tableExists('faonni_catalog_product_index_sale')) {
			$table = $connection
				->newTable($installer->getTable('faonni_catalog_product_index_sale'))
				->addColumn(
					'entity_id',
					Table::TYPE_INTEGER,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Entity Id'
				)
				->addColumn(
					'website_id',
					Table::TYPE_SMALLINT,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Website ID'
				)				
				->addIndex(
					$installer->getIdxName('faonni_catalog_product_index_sale', ['website_id']),
					['website_id']
				)				
				->addForeignKey(
					$installer->getFkName('faonni_catalog_product_index_sale', 'website_id', 'store_website', 'website_id'),
					'website_id',
					$installer->getTable('store_website'),
					'website_id',
					Table::ACTION_CASCADE
				)
				->addForeignKey(
					$installer->getFkName('faonni_catalog_product_index_sale', 'entity_id', 'catalog_product_entity', 'entity_id'),
					'entity_id',
					$installer->getTable('catalog_product_entity'),
					'entity_id',
					Table::ACTION_CASCADE
				)
				->setComment('Catalog Product Sale Index Table');

			$connection->createTable($table);		
		} 

        $installer->endSetup();
    }
}
