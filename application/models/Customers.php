<?php

/**
 * Customer data!
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */
class Customers extends Zend_Db_Table_Abstract {	
	
	protected $_name = 'customers';
	
	protected $_primary = 'customer_id';
	
}