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
}
