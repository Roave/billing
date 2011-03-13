<?php

/**
 * CustomersController
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

require "../application/models/Customers.php";

class CustomersController extends Zend_Controller_Action {

	/**
	 * View a list of all customers
	 */
	public function indexAction()
	{
		$customersTable = new Customers();
		$this->view->customers = $customersTable->fetchAll();
	}

	/**
	 * View a single customer record
	 */
	public function viewAction()
	{
		$customersTable = new Customers();
		$this->view->customer = $customersTable->find($this->_getParam('id'))
			->current();
	}

	/**
	 * Create a new customer
	 */
	public function newAction()
	{
		echo $this->getForm();
	}

	/**
	 * Update a customer record
	 */
	public function updateAction()
	{
		$customerId = $this->_getParam('id');
		
		$customersTable = new Customers();
		$customer = $customersTable->find($customerId)
			->current();
		
		echo $this->getForm($customer->toArray())
			->setAction('/customers/save/id/' . $customerId);
	}

	/**
	 * Create/update a customer record in the database
	 */
	public function saveAction()
	{
		$customersTable = new Customers();
		$form = $this->getForm();
		
		if ($form->isValid($_POST)) {
			$values = $form->getValues();
			
			$id = $this->_hasParam('id') ? $this->_getParam('id') : null;
			unset($values['customer_id'], $values['submit']);
			
			if (isset($id) && $id != null) {
				$customersTable->update($values, array('customer_id = ?' => $id));
			} else {
				$id = $customersTable->insert($values);
			}
			
			$this->_redirect('/customers/view/id/' . $id);
		} else {
			echo $form;
		}
	}

	/**
	 * Delete a customer record
	 */
	public function deleteAction()
	{
		$customersTable = new Customers();
		$customer = $customersTable->find($this->_getParam('id'))
			->current();
			
		!is_null($customer) && $customer->delete();
		$this->_redirect('/customers');
	}

	/**
	 * Returns the Zend_Form for creating/updating customer records
	 * @param array $values
	 * @return Zend_Form
	 */
	private function getForm(array $values = null)
	{
		$form = new Zend_Form();
		$form->setAction('/customers/save')
			->setMethod('post');
		
		$custid = new Zend_Form_Element_Text('customer_id');
		$custid->setAttrib('disabled', 'disabled')
			->setLabel('Customer ID');
		
		$company = new Zend_Form_Element_Text('company');
		$company->setRequired(true)
			->setLabel('Company');
		
		$address = new Zend_Form_Element_Text('address');
		$address->setRequired(true)
			->setLabel('Address');
		
		$address2 = new Zend_Form_Element_Text('address_2');
		$address2->setLabel('Address 2');
		
		$city = new Zend_Form_Element_Text('city');
		$city->setRequired(true)
			->setLabel('City');
		
		$state = new Zend_Form_Element_Text('state');
		$state->setRequired(true)
			->setLabel('State');
		
		$zip = new Zend_Form_Element_Text('zip');
		$zip->setRequired(true)
			->setLabel('ZIP');
		
		$country = new Zend_Form_Element_Text('country');
		$country->setLabel('Country');
		
		$phone = new Zend_Form_Element_Text('phone');
		$phone->setLabel('Phone');
		
		$fax = new Zend_Form_Element_Text('fax');
		$fax->setLabel('Fax');
		
		$submit = new Zend_Form_Element_Submit('submit');
		
		if (isset($values)) {
			$custid->setValue($values['customer_id']);
			$company->setValue($values['company']);
			$address->setValue($values['address']);
			$address2->setValue($values['address_2']);
			$city->setValue($values['city']);
			$state->setValue($values['state']);
			$zip->setValue($values['zip']);
			$country->setValue($values['country']);
			$phone->setValue($values['phone']);
			$fax->setValue($values['fax']);
		}
		
		$form->addElement($custid)
			->addElement($company)
			->addElement($address)
			->addElement($address2)
			->addElement($city)
			->addElement($state)
			->addElement($zip)
			->addElement($country)
			->addElement($phone)
			->addElement($fax)
			->addElement($submit);
		
		return $form;
	}

}
