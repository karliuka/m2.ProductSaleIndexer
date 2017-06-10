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
namespace Faonni\ProductSaleIndexer\Model\ResourceModel\Indexer\Product;

use Magento\Catalog\Model\ResourceModel\Product\Indexer\AbstractIndexer;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

/**
 * ProductSaleIndexer index resource model
 */
class SaleIndexer extends AbstractIndexer
{
    /**
     * Define main index table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
			'faonni_catalog_product_index_sale', 
			'entity_id'
		);
    }
	
    /**
     * Reindex data for all products
     *
     * @return SaleIndexer
     * @throws \Exception
     */
    public function reindexAll()
    {
        $this->tableStrategy
			->setUseIdxTable(false);
		
        $this->beginTransaction();
        try {
            $this->reindex();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        return $this;
    }
	
    /**
     * Reindex data for defined product(s)
     *
     * @param int|array $entityIds
     * @return SaleIndexer
     */
    public function reindexEntity($entityIds)
    {
        $this->reindex($entityIds);
        return $this;
    }
	
    /**	
     * Reindex data
     *	
     * @param null|int|array $entityIds
     * @return SaleIndexer
     */
    protected function reindex($entityIds = null)
    {
        if ($entityIds !== null) {
            $this->cleanByIds($entityIds);
        } else {
			$this->cleanAll();
		}

		$connection = $this->getConnection();				      
        $select = $connection->select()->from(
            ['e' => $this->getTable('catalog_product_entity')],
            ['entity_id']
        )
        ->join(
            ['cw' => $this->getTable('store_website')],
            '',
            ['website_id']
        )
        ->join(
            ['cwd' => $this->getTable('catalog_product_index_website')],
            'cw.website_id = cwd.website_id',
            []
        )
        ->join(
            ['csg' => $this->getTable('store_group')],
            'csg.website_id = cw.website_id AND cw.default_group_id = csg.group_id',
            []
        )
        ->join(
            ['cs' => $this->getTable('store')],
            'csg.default_store_id = cs.store_id AND cs.store_id != 0',
            []
        )
        ->join(
            ['pw' => $this->getTable('catalog_product_website')],
            'pw.product_id = e.entity_id AND pw.website_id = cw.website_id',
            []
        );

        // add enable products limitation
        $statusCond = $connection->quoteInto('=?', Status::STATUS_ENABLED);       
        $this->_addAttributeToSelect(
            $select,
            'status',
            'e.entity_id',
            'cs.store_id',
            $statusCond,
            true
        );        
        
        $specialPrice = $this->_addAttributeToSelect(
            $select,
            'special_price',
            'e.entity_id',
            'cs.store_id',
            null,
            true
        );
        
        $specialFrom = $this->_addAttributeToSelect(
            $select,
            'special_from_date',
            'e.entity_id',
            'cs.store_id'
        );
        
        $specialTo = $this->_addAttributeToSelect(
            $select,
            'special_to_date',
            'e.entity_id',
            'cs.store_id'
        );        

        $currentDate = $connection->getDatePartSql('cwd.website_date');
        $specialFromDate = $connection->getDatePartSql($specialFrom);
        $specialToDate = $connection->getDatePartSql($specialTo);
               
        $select->where("{$specialToDate} >= {$currentDate}");
        $select->where("{$specialFromDate} <= {$currentDate}");
        
        if ($entityIds !== null) {
            $select->where('e.entity_id IN(?)', $entityIds);
        }
        
        $this->getConnection()->query(
			$select->insertFromSelect($this->getMainTable(), [], false)
		);
		
        return $this;
    }
	
    /**
     * Clean all data
     *
     * @param string $table
     * @return void
     */
    protected function cleanAll()
    {
        $this->getConnection()->delete(
			$this->getMainTable()
		);
    }
	
    /**
     * Clean data by entity ids
     *
     * @param array $entityIds
     * @return void
     */
    protected function cleanByIds($entityIds)
    {
		$this->getConnection()->delete(
			$this->getMainTable(),
			['entity_id IN (?)' => $entityIds]
		); 	       
    }	
}
