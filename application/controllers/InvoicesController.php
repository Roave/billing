<?php

/**
 * Manage invoices
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

require_once "../application/models/Invoices.php";
require_once "../application/models/Customers.php";
require_once "../application/models/InvoiceDetails.php";
require_once "../application/models/InvoiceSubtotals.php";

class InvoicesController extends Zend_Controller_Action {

	/**
	 * View existing invoices
	 */
	public function indexAction()
	{
		$invoicesTable = new Invoices();
		$invoices = $invoicesTable->fetchAll();
		if ($invoices) {
			foreach ($invoices as $i) {
				$customerData = $i->findParentRow('Customers');
				$lineItems = $i->findDependentRowset('InvoiceDetails');
				$subtotals = $i->findDependentRowset('InvoiceSubtotals')
					->current();
				
				$invoiceData[] = array_merge($i->toArray(), $customerData->toArray(), $lineItems->toArray(), $subtotals->toArray());
			}
		}
		
		$this->view->invoices = $invoiceData;
	}

	public function viewAction()
	{
		$invoicesTable = new Invoices();
		$id = $this->_getParam('id');
		
		$invoice = $invoicesTable->find($id)
			->current();
		
		$this->view->invoice = $invoice;
		$this->view->customer = $invoice->findParentRow('Customers');
		$this->view->details = $invoice->findDependentRowset('InvoiceDetails');
		$this->view->subtotal = $invoice->findDependentRowset('InvoiceSubtotals')
			->current();
	}

	public function newAction()
	{
	
	}
}
