<?php

/**
 * Invoices!
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

class Invoices extends Zend_Db_Table_Abstract {

	/**
	 * Table name
	 * @var string
	 */
	protected $_name = 'invoices';

	/**
	 * Primary key
	 * @var int
	 */
	protected $_primary = 'invoice_id';

	protected $_referenceMap = array('Customer' => array('columns' => 'customer_id', 'refTableClass' => 'Customers', 'refColumns' => 'customer_id'));
}