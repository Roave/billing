<?php

/**
 * Manage invoices
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

require_once "../application/models/Invoices.php";
require_once "../application/models/Customers.php";
require_once "../application/models/InvoiceDetails.php";

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
				$invoiceData[] = array_merge($i->toArray(), $customerData->toArray(), $lineItems->toArray());
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
		
		$details = $invoice->findDependentRowset('InvoiceDetails');
		$this->view->details = $details;
		
		$subtotal = 0;
		foreach ($details as $d) {
			$subtotal += $d->unit_price * $d->quantity;
		}
		
		$this->view->subtotal = $subtotal;
	}
}
