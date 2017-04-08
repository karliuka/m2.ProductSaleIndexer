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
namespace Faonni\ProductSale\Model\Indexer\Product;

use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Faonni\ProductSale\Model\ResourceModel\Indexer\Product\SaleIndexer as IndexerResource;

/**
 * ProductSale Index model
 */
class SaleIndexer implements ActionInterface, MviewActionInterface
{
    /**
     * Indexer resource model
     *
     * @var \Faonni\ProductSale\Model\ResourceModel\Indexer\Product\SaleIndexer
     */
    protected $_indexerResource;
	
    /**
	 * Initialize indexer
	 *	
     * @param IndexerResource $indexerResource
     */
    public function __construct(
        IndexerResource $indexerResource
    ) {
        $this->_indexerResource = $indexerResource;
    }
	
    /**
     * Execute full indexation
     *
     * @return void
     */
    public function executeFull()
    {
		$this->_indexerResource->reindexAll();
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param int[] $ids
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function executeList(array $ids)
    {
		$this->_indexerResource->reindexEntity($ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function executeRow($id)
    {
		$this->_indexerResource->reindexEntity([$id]);			
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($ids)
    {
		$this->_indexerResource->reindexEntity($ids);	
    }
}
