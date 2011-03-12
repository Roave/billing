<?php

/**
 * InvoiceDetails
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */


class InvoiceDetails extends Zend_Db_Table_Abstract {

	/**
	 * The default table name 
	 */
	protected $_name = 'invoice_details';
	
	protected $_primary = 'detail_id';
	
	protected $_referenceMap = array(
		'Invoice'	=>	array(
			'columns'		=>	'invoice_id',
			'refTableClass'	=>	'Invoices',
			'refColumns'	=>	'invoice_id'
		),
		'CustomerProduct'	=>	array(
			'columns'		=>	'customer_product_id',
			'refTableClass'	=>	'CustomerProducts',
			'refColumns'	=>	'product_id'
		)
	);

}
