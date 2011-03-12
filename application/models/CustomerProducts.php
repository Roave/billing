<?php

/**
 * A customer product is an actual product that has had some property
 * overridden for a particular customer (e.g. a special discounted rate
 * or similar)
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com> 
 */

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
	protected $_primary = array('product_id', 'customer_id');
	
	/**
	 * The PK is not a sequence
	 */
	protected $_sequence = false;
	
	protected $_referenceMap = array(
		'Customer' => array(
			'columns'		=>	'customer_id',
			'refTableClass'	=>	'Customers',
			'refColumns'	=>	'customer_id'
		),
		'Product' => array(
			'columns'		=>	'product_id',
			'refTableClass'	=>	'Products',
			'refColumns'	=>	'product_id'
		)
	);

}
