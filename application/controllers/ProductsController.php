<?php

/**
 * ProductsController
 * 
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */

require_once '../application/models/Products.php';

class ProductsController extends Zend_Controller_Action {

	/**
	 * View a list of all products
	 */
	public function indexAction()
	{
		$productsTable = new Products();
		$this->view->products = $productsTable->fetchAll();
	}

	/**
	 * View a single product record
	 */
	public function viewAction()
	{
		$productsTable = new Products();
		$this->view->product = $productsTable->find($this->_getParam('id'))
			->current();
	}

	/**
	 * Create a new product
	 */
	public function newAction()
	{
		echo $this->getForm();
	}

	/**
	 * Update a product record
	 */
	public function updateAction()
	{
		$productId = $this->_getParam('id');
		
		$productsTable = new Products();
		$product = $productsTable->find($productId)
			->current();
		
		echo $this->getForm($product->toArray())
			->setAction('/products/save/id/' . $productId);
	}

	/**
	 * Create/update a product record in the database
	 */
	public function saveAction()
	{
		$productsTable = new Products();
		$form = $this->getForm();
		
		if ($form->isValid($_POST)) {
			$values = $form->getValues();
			
			$id = $this->_hasParam('id') ? $this->_getParam('id') : null;
			unset($values['product_id'], $values['submit']);
			
			if (isset($id) && $id != null) {
				$productsTable->update($values, array('product_id = ?' => $id));
			} else {
				$id = $productsTable->insert($values);
			}
			
			$this->_redirect('/products/view/id/' . $id);
		} else {
			echo $form;
		}
	}

	/**
	 * Delete a customer record
	 */
	public function deleteAction()
	{
		$productsTable = new Products();
		$product = $productsTable->find($this->_getParam('id'))
			->current();
		
		!is_null($product) && $product->delete();
		$this->_redirect('/products');
	}

	/**
	 * Returns the Zend_Form for creating/updating product records
	 * @param array $values
	 * @return Zend_Form
	 */
	private function getForm(array $values = null)
	{
		$form = new Zend_Form();
		$form->setAction('/products/save')
			->setMethod('post');
		
		$productId = new Zend_Form_Element_Text('product_id');
		$productId->setAttrib('disabled', 'disabled')
			->setLabel('Product ID');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Product Name')
			->setRequired(true);
		
		$desc = new Zend_Form_Element_Textarea('description');
		$desc->setLabel('Description')
			->setRequired(true);
		
		$price = new Zend_Form_Element_Text('unit_price');
		$price->setLabel('Unit Price')
			->setRequired(true)
			->addValidator(new Zend_Validate_Float());
		
		$submit = new Zend_Form_Element_Submit('submit');
		
		if (isset($values)) {
			$productId->setValue($values['product_id']);
			$name->setValue($values['name']);
			$desc->setValue($values['description']);
			$price->setValue($values['unit_price']);
		}
		
		$form->addElement($productId)
			->addElement($name)
			->addElement($desc)
			->addElement($price)
			->addElement($submit);
		
		return $form;
	}

}
