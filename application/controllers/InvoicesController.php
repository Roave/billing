<?php

/**
 * Manage invoices
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

require_once APPLICATION_PATH . '/models/Invoices.php';
require_once APPLICATION_PATH . '/models/Products.php';
require_once APPLICATION_PATH . '/models/Customers.php';
require_once APPLICATION_PATH . '/models/InvoiceDetails.php';
require_once APPLICATION_PATH . '/models/InvoiceSubtotals.php';
require_once APPLICATION_PATH . '/models/CustomerProducts.php';

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
		
		$customerProductsTable = new CustomerProducts();
		$products = $customerProductsTable->findProductsCoalesced($invoice->customer_id);
		
		$productNames = array(
			0 => '');
		$productData = array();
		if ($products) {
			foreach ($products as $p) {
				$productNames[] = $p['name'];
				$productData[$p['product_id']] = $p;
			}
		}
		
		$this->view->products = $productNames;
		$this->view->productData = $productData;
	}

	public function newAction()
	{
		$form = $this->getNewInvoiceForm();
		echo $form;
	}

	public function saveAction()
	{
		$invoicesTable = new Invoices();
		$form = $this->getNewInvoiceForm();
		
		if ($form->isValid($_POST)) {
			$values = $form->getValues();
			
			$data = array(
				'customer_id' => $values['customer'], 
				'invoice_date' => $values['invoice_date'], 
				'due_date' => $values['due_date'], 
				'notes' => $values['notes']);
			
			$id = $invoicesTable->insert($data);
			
			$this->_redirect('/invoices/view/id/' . $id);
		} else {
			echo $form;
		}
	}

	public function updateAction()
	{
		switch ($this->_getParam('do')) {
			case 'add-line-item' :
				$this->addLineItem();
				break;
			case 'remove-line-item':
				$this->removeLineItem();
				break;
			default :
				throw new Exception();
				break;
		}
	}

	private function addLineItem()
	{
		$invoiceId = $this->_getParam('id');
		$sku = $this->_getParam('sku');
		$productId = $this->_getParam('product');
		$unitPrice = $this->_getParam('unit_price');
		$quantity = $this->_getParam('quantity');
		
		if (!isset($productId) || $productId == 0) {
			$this->_redirect('/invoices/view/id/' . $invoiceId);
			return;
		}
		
		$invoicesTable = new Invoices();
		$invoice = $invoicesTable->find($invoiceId)
			->current();
		
		$customerProductsTable = new CustomerProducts();
		$product = $customerProductsTable->findProductCoalesced($invoice->customer_id, $productId);
		
		$data = array(
			'invoice_id' => $invoice->invoice_id, 
			'sku' => $sku, 
			'name' => $product['name'], 
			'unit_price' => $unitPrice, 
			'quantity' => $quantity);
		
		$detailsTable = new InvoiceDetails();
		$detailsTable->insert($data);
		
		unset($data['invoice_id']);
		unset($data['quantity']);
		unset($data['name']);
		$data['product_id'] = $productId;
		$data['customer_id'] = $invoice->customer_id;
		
		$customerProductsTable->updateCustomerProduct($data);
		
		$this->_redirect('/invoices/view/id/' . $invoiceId);
	}
	
	private function removeLineItem()
	{
		$invoiceId = $this->_getParam('id');
		$lineItem = $this->_getParam('detail');
		
		$invoiceDetailsTable = new InvoiceDetails();
		$detail = $invoiceDetailsTable->find($lineItem)->current();
		
		if ($detail->invoice_id == $invoiceId) {
			$detail->delete();
		}
		
		$this->_redirect('/invoices/view/id/' . $invoiceId);
	}

	private function getNewInvoiceForm(array $values = null)
	{
		$customersTable = new Customers();
		$customers = $customersTable->fetchAll();
		
		$customerList = array();
		if ($customers->count() > 0) {
			foreach ($customers as $c) {
				$customerList[$c->customer_id] = $c->company;
			}
		}
		
		$form = new Zend_Form();
		$form->setAction('/invoices/save')
			->setMethod('post');
		
		$customer = new Zend_Form_Element_Select('customer');
		$customer->addMultiOptions($customerList)
			->setLabel('Customer');
		
		$invoiceDate = new Zend_Form_Element_Text('invoice_date');
		$invoiceDate->setRequired(true)
			->setLabel('Invoice Date')
			->addValidator(new Zend_Validate_Date());
		
		$dueDate = new Zend_Form_Element_Text('due_date');
		$dueDate->setRequired(true)
			->setLabel('Due Date')
			->addValidator(new Zend_Validate_Date());
		
		$notes = new Zend_Form_Element_Textarea('notes');
		$notes->setLabel('Notes');
		
		$submit = new Zend_Form_Element_Submit('submit');
		
		if (isset($values)) {
			$customer->setValue($values['customer_id']);
			$invoiceDate->setValue($values['invoice_date']);
			$dueDate->setValue($values['due_date']);
			$notes->setValue($values['notes']);
		}
		
		$form->addElement($customer)
			->addElement($invoiceDate)
			->addElement($dueDate)
			->addElement($notes)
			->addElement($submit);
		
		return $form;
	}
}
