<?php


require_once 'application/controllers/ProductsController.php';


/**
 * ProductsController test case.
 */
class ProductsControllerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ProductsController
	 */
	private $ProductsController;

	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();
		
		// TODO Auto-generated ProductsControllerTest::setUp()
		

		$this->ProductsController = new ProductsController(/* parameters */);
	
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		// TODO Auto-generated ProductsControllerTest::tearDown()
		

		$this->ProductsController = null;
		
		parent::tearDown();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
		// TODO Auto-generated constructor
	}

	
	/**
	 * Tests ProductsController->indexAction()
	 */
	public function testIndexAction()
	{
		// TODO Auto-generated ProductsControllerTest->testIndexAction()
		$this->markTestIncomplete("indexAction test not implemented");
		
		$this->ProductsController->indexAction(/* parameters */);
	
	}

	/**
	 * Tests ProductsController->viewAction()
	 */
	public function testViewAction()
	{
		// TODO Auto-generated ProductsControllerTest->testViewAction()
		$this->markTestIncomplete("viewAction test not implemented");
		
		$this->ProductsController->viewAction(/* parameters */);
	
	}

	/**
	 * Tests ProductsController->newAction()
	 */
	public function testNewAction()
	{
		// TODO Auto-generated ProductsControllerTest->testNewAction()
		$this->markTestIncomplete("newAction test not implemented");
		
		$this->ProductsController->newAction(/* parameters */);
	
	}

	/**
	 * Tests ProductsController->updateAction()
	 */
	public function testUpdateAction()
	{
		// TODO Auto-generated ProductsControllerTest->testUpdateAction()
		$this->markTestIncomplete("updateAction test not implemented");
		
		$this->ProductsController->updateAction(/* parameters */);
	
	}

	/**
	 * Tests ProductsController->saveAction()
	 */
	public function testSaveAction()
	{
		// TODO Auto-generated ProductsControllerTest->testSaveAction()
		$this->markTestIncomplete("saveAction test not implemented");
		
		$this->ProductsController->saveAction(/* parameters */);
	
	}

	/**
	 * Tests ProductsController->deleteAction()
	 */
	public function testDeleteAction()
	{
		// TODO Auto-generated ProductsControllerTest->testDeleteAction()
		$this->markTestIncomplete("deleteAction test not implemented");
		
		$this->ProductsController->deleteAction(/* parameters */);
	
	}

}

