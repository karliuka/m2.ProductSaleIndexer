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
namespace Faonni\ProductSale\Model\Plugin;

use Faonni\ProductSale\Model\Indexer\Product\SaleIndexer;

/**
 * ProductSale plugin
 */
class IndexerSaleHandler
{
    /**
     * Sale Indexer instance
     *
     * @var \Faonni\ProductSale\Model\Indexer\Product\SaleIndexer
     */
    protected $_indexer;         
    
    /**
	 * Initialize plugin
	 *	
     * @param SaleIndexer $indexer
     */
    public function __construct(
        SaleIndexer $indexer
    ) {
        $this->_indexer = $indexer;
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param \Magento\Catalog\Model\Indexer\Product\Price $price
     * @param int[] $ids
     * @return int[] $ids
     */     
    public function beforeExecuteList($price, array $ids)
    {
		$this->_indexer->executeList($ids);
        return $ids;
    }
}  
