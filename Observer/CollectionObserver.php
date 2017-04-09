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
 * @package     Faonni_ProductSale
 * @copyright   Copyright (c) 2017 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\ProductSale\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * ProductSale collection observer
 */
class CollectionObserver implements ObserverInterface
{	
    /**
     * Add Attribute
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {		
		$collection = $observer->getEvent()->getCollection();
		
		if ($collection instanceof ProductCollection) {
			$select = $collection->getSelect();	
			$connection = $select->getConnection();		
			$select->joinLeft(
				['sale_index' => $collection->getTable('faonni_catalog_product_index_sale')],
				'e.entity_id = sale_index.entity_id AND price_index.website_id = sale_index.website_id',
				['is_sale' => $connection->getCheckSql('sale_index.entity_id IS NULL', '0', '1')]
			);
		}
        return $this;
    }
}  
