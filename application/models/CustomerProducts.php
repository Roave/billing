<?php

/**
 * A customer product is an actual product that has had some property
 * overridden for a particular customer (e.g. a special discounted rate
 * or similar)
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com> 
 */

require_once "../application/models/Products.php";

class CustomerProducts extends Zend_Db_Table_Abstract {

	/**
	 * Table name
	 * @var string
	 */
	protected $_name = 'customer_products';

	/**
	 * Primary key
	 * @var array
	 */
	protected $_primary = array(
		'product_id', 
		'customer_id');

	/**
	 * The PK is not a sequence
	 */
	protected $_sequence = false;

	protected $_referenceMap = array(
		'Customer' => array(
			'columns' => 'customer_id', 
			'refTableClass' => 'Customers', 
			'refColumns' => 'customer_id'), 
		'Product' => array(
			'columns' => 'product_id', 
			'refTableClass' => 'Products', 
			'refColumns' => 'product_id'));

	/**
	 * @param int $customerId
	 */
	public function findProductsCoalesced($customerId)
	{
		$productsTable = new Products();
		$products = $productsTable->fetchAll();
		
		$productsCoalesced = array();
		if ($products->count() > 0) {
			foreach ($products as $p) {
				$cp = $this->find($p->product_id, $customerId)
					->current();
				
				if ($cp) {
					$productsCoalesced[] = array(
						'product_id' => $cp->product_id, 
						'sku' => $cp->sku ?  : $p->sku, 
						'name' => $cp->name ?  : $p->name, 
						'description' => $cp->description ?  : $p->description, 
						'unit_price' => $cp->unit_price ?  : $p->unit_price);
				} else {
					$productsCoalesced[] = array(
						'product_id' => $p->product_id, 
						'sku' => $p->sku, 
						'name' => $p->name, 
						'description' => $p->description, 
						'unit_price' => $p->unit_price);
				}
			}
		}
		
		return $productsCoalesced;
	}

	/**
	 * @param int $customerId
	 * @param int $productId
	 */
	public function findProductCoalesced($customerId, $productId)
	{
		$productsTable = new Products();
		$p = $productsTable->find($productId)
			->current();
		
		$cp = $this->find($productId, $customerId)
			->current();
		

		if ($cp) {
			$productCoalesced= array(
				'product_id' => $cp->product_id, 
				'sku' => $cp->sku ?  : $p->sku, 
				'name' => $cp->name ?  : $p->name, 
				'description' => $cp->description ?  : $p->description, 
				'unit_price' => $cp->unit_price ?  : $p->unit_price);
		} else {
			$productCoalesced = array(
				'product_id' => $p->product_id, 
				'sku' => $p->sku, 
				'name' => $p->name, 
				'description' => $p->description, 
				'unit_price' => $p->unit_price);
		}
		
		return $productCoalesced;
	}

	public function updateCustomerProduct(array $data)
	{
		$record = $this->find($data['product_id'], $data['customer_id'])->current();
		if ($record) {
			$record->sku = $data['sku'];
			$record->unit_price = $data['unit_price'];
			$record->save();
			
			return;
		} else {
			$this->insert($data);
			return;
		}
	}

}
