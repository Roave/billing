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
	
	/* (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::insert()
	 */
	public function insert(array $data) {
		throw new InvalidMethodException('Cannot insert into a view');
	}
	
	/* (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::createRow()
	 */
	public function createRow(array $data = array(), $defaultSource = null)
	{
		throw new InvalidMethodException('Cannot insert into a view');
	}

	/* (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::delete()
	 */
	public function delete($where)
	{
		throw new InvalidMethodException('Cannot delete from a view');
	}

	/* (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::update()
	 */
	public function update(array $data, $where)
	{
		throw new InvalidMethodException('Cannot update a view');
	}

	
	
}