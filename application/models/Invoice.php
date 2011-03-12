<?php

/**
 * 
 * Invoices!
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 *
 */
class Invoice extends ModelAbstract {

	/**
	 * @var int
	 */
	protected $_invoiceId;

	/**
	 * @var int
	 */
	protected $_customerId;

	/**
	 * @var string
	 */
	protected $_invoiceDate;

	/**
	 * @var string
	 */
	protected $_dueDate;

	/**
	 * @var string
	 */
	protected $_notes;

	/**
	 * Whether this instance has been stored in the database
	 * @var boolean
	 */
	protected $_new = false;

	/**
	 * Whether this instance is up-to-date in the database
	 * @var boolean
	 */
	protected $_updated = false;

	/**
	 * The database table to which this class corresponds
	 * @var string
	 */
	protected static $_tableName = 'invoices';

	/**
	 * Mapping of database fields to class attributes
	 * @var array
	 */
	protected static $_fieldMapping = array();

	/**
	 * Mapping of class attributes to database fields
	 * @var array
	 */
	protected static $_attributeMapping = array();

	/**
	 * @param int $lookup
	 * @throws ItemNotFoundException
	 */
	public function __construct($id = null)
	{
		// Don't re-do the field mapping if it's already been done during this request
		if (count(self::$_fieldMapping) == 0) {
			// Describe the table
			$dbColumns = Zend_Registry::get('db')->describeTable(self::$_tableName);
			
			// Class attributes should be the same as db fields, just in camelCase
			foreach ($dbColumns as $col) {
				self::$_fieldMapping[$col['COLUMN_NAME']] = '_' . $this->toCamelCase($col['COLUMN_NAME']);
			}
			
			// Flip it for easy two-way lookup
			self::$_attributeMapping = array_flip(self::$_fieldMapping);
		}
		
		// If they provide an ID, look it up; if not, we're creating a new record
		if (isset($id) && $id !== null) {
			$result = Zend_Registry::get('db')->select()
				->from(self::$_tableName)
				->where('invoice_id = ?', $id)
				->query()
				->fetch();
			
			if (!$result) {
				throw new ItemNotFoundException('ID:' . $id);
			}
			
			foreach ($result as $field => $value) {
				$this->{ self::$_fieldMapping[$field] } = $value;
			}
		} else {
			$this->_new = true;
			$this->_updated = true;
		}
	}

	/**
	 * We're going to handle the getters and setters with some magic!
	 * 
	 * Any methods that don't fit with what's defined here can easily
	 * be overridden simply by implementing them.
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @throws InvalidMethodException
	 */
	public function __call($method, $arguments)
	{
		// Match set*() and get*()
		$count = preg_match('/^(set|get)(.*)$/', $method, $matches);
		
		// There better be 3 matches; one for the full string, one for set/get
		// and one for the property name
		if (count($matches) == 3) {
			
			// Method will be in the format getAttribute()
			// so convert that to _attribute;
			$property = '_' . lcfirst($matches[2]);
			
			if ($matches[1] == 'get') {
				// Checks that the property is either set or is defined in the class
				// isset is quite a bit faster than property_exists, so we'll hope it's set
				if (isset($this->{$property}) || property_exists($this, $property)) {
					return $this->{$property};
				}
			} else if ($matches[1] == 'set') {
				if (isset($this->{$property}) || property_exists($this, $property)) {
					// One argument: the new value
					if (count($arguments) != 1) {
						throw new InvalidMethodException('Invalid argument count');
					}
					
					$this->{$property} = $arguments[0];
					$this->_updated = true;
					
					// Allow method chaining on setters
					return $this;
				}
			}
		}
		
		throw new InvalidMethodException('Invalid method');
	}

}