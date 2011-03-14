<?php

class InvoiceSubtotals extends Zend_Db_Table_Abstract {

	/**
	 * Table name
	 * @var string
	 */
	protected $_name = 'invoice_subtotals';

	/**
	 * Primary key
	 * @var int
	 */
	protected $_primary = 'invoice_id';

	protected $_referenceMap = array(
		'Invoice' => array(
			'columns' => 'invoice_id', 
			'refTableClass' => 'Invoices', 
			'refColumns' => 'invoice_id'));
}