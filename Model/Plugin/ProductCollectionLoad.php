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
namespace Faonni\ProductSaleIndexer\Model\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * ProductCollection plugin
 */
class ProductCollectionLoad
{
    /**
     * Load collection data into object items
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param bool $printQuery
     * @param bool $logQuery
     * @return null
     */     
    public function beforeLoad($collection, $printQuery=false, $logQuery=false)
    {
		if (!$collection->isLoaded()) {
			$select = $collection->getSelect();	
			$connection = $select->getConnection();
			
			$select->joinLeft(
				['sale_flag' => $collection->getTable('faonni_catalog_product_index_sale')],
				'e.entity_id = sale_flag.entity_id AND price_index.website_id = sale_flag.website_id',
				['is_sale' => $connection->getCheckSql('sale_flag.entity_id IS NULL', '0', '1')]
			);
		}
        return null;
    }
}  