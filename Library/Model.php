<?php

namespace ezRPG\Library;
use \Pdo,
	\Exception;

/**
 * Model
 * 
 * Base model class that other models will use.
 * This class extends Pdo, and initializes it within it's
 * constructor with database settings gathered from Config.
 */
abstract class Model extends Pdo implements Interfaces\Model
{
	private $_config;
	protected $conainter;
	
	protected $tableName;
	protected $isVirtualTable;
	
	protected $primaryKey;
	protected $tableColumns;
	
	public $safeMode = true;
	
	/**
	 * Constructor
	 * @param object $app
	 */
	public function __construct(Interfaces\Container $container)
	{
		$this->container = $container;
		$config = $this->_config = $container['config']['db'];
		
		// initliaze the Pdo parent
		parent::__construct(
			$config['driver'] . ':host=' . $config['host'] . ';port='. $config['port'] . ';dbname=' . $config['database'].';charset=utf8', 
			$config['username'], 
			$config['password']
		);
		
		$this->setAttribute(Pdo::ATTR_ERRMODE, Pdo::ERRMODE_EXCEPTION);
		$this->setAttribute(Pdo::ATTR_DEFAULT_FETCH_MODE, Pdo::FETCH_ASSOC);
		
		// if this is a *virtual* table, skip all info probing
		if ($this->isVirtualTable == true){
			return;
		}
		
		// a few things to set up
		if (!isset($this->tableName)) {
			$table_fqn = get_class($this);
			$this->tableName = substr(strtolower($table_fqn), 1+(strrpos($table_fqn, '\\')));
			
			// prefix support
			if (!empty($this->_config['prefix'])) {
				$this->tableName = $this->_config['prefix'] . $this->tableName;
			}
		}
		
		$col_q = $this->prepare('SELECT COLUMN_NAME, COLUMN_KEY FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?');
		$col_q->execute(array($config['database'], $this->tableName));	
		$columns = $col_q->fetchAll();	
		
		// generate list of columns
		if (count($columns) <> 0) {
			foreach($columns as $column) {
				if ($this->primaryKey == null && $column['COLUMN_KEY'] == 'PRI') {
					$this->primaryKey = $column['COLUMN_NAME'];
				}
				
				$this->tableColumns[] = $column['COLUMN_NAME'];
			}
		}
	}
	
	/**
	 * Find a single record
	 * 
	 * @param mixed $lookup A vlaue to match against the primary key
	 * @param string $key Sets an alternative key to lookup against
	 * @param boolean $partial Search for a partial match
	 * @return array
	 */
	public function find($lookup=null, $key=null, $partail=false)
	{
		return current($this->findAll($lookup, $key, $partail));
	}
	
	/**
	 * Find all records matching a criteria
	 *
	 * @param mixed $lookup A vlaue to match against the primary key
	 * @param string $key Sets an alternative key to lookup against
	 * @param boolean $partial Search for a partial match
	 * @return array
	 */
	public function findAll($lookup=null, $key=null, $partail=false)
	{
		$priKey = $this->primaryKey;
		
		// reset the primary key
		if ($key != null) {
			$priKey = $key;
		}
		
		
		if ($partail) {
			$lookup = $this->quote(strpad($lookup, strlen($lookup) + 2, '%', \STR_PAD_BOTH));
		} elseif (is_string($lookup)) {
			$lookup = $this->quote($lookup);
		}
		
		$match_type = ($partail ? 'LIKE' : '=');
		
		$sql = 'SELECT * FROM `' . $this->tableName . '`';
		if ($lookup != null) {
			$sql .= ' WHERE `' . $priKey .'` ' . $match_type . ' ' . $lookup;
		}		
		
		$query = $this->prepare($sql);
		$query->execute();
		
		return $query->fetchAll();
	}
	
	/**
	 * Create a new record
	 * 
	 * @param array $data
	 * @return integer Id of created record
	 */
	public function add($data) 
	{
		$keys = array();
		$values = array();
		$sql = "INSERT INTO $this->tableName (";
		// iterate through array and sanatize 
		foreach($data as $key => $item) {			
			if (is_string($item)) {
				$item = $this->quote($item); //$value wasn't used, replaced with $item
			}
			
			array_push($keys, $key);
			array_push($values, $item );
		}
		$sql .= implode(', ', $keys);
		$sql .= ") VALUES (";
		$sql .= implode(', ', $values);
		$sql .= ")";
		//echo $sql . "<br />"; //Here for Debugging
		//$sql = "INSERT INTO :table (:keys) VALUES (:values)"; 
		$query = $this->prepare($sql);
		$query->execute();
		
		return $this->lastInsertId();
	}
	
	/**
	 * Saves an updated record
	 * 
	 * Will attempt to retrieve record ID from data array if $id 
	 * is not set.
	 * 
	 * @param array $data
	 * @param mixed $id
	 * @returns integer Number of affected rows
	 */
	public function save($data, $id=null)
	{
		$pairs = array();
		$priKey_value = $id;
		
		// iterate through array and sanatize
		foreach($data as $key => &$item) {
			if ($key == $this->primaryKey && $id == null) {
				$priKey_value = $item;
				unset($data[$key]);
				continue;
			}
			
			if (is_string($item)) {
				$item = $this->quote($item);
			}
				
			array_push($pairs, '`' . $key . '` = ' . $item);
		}
		

		$sql = 'UPDATE `' . $this->tableName . '` SET ' . implode(', ', $pairs);
		
		// impose restrictions
		if (is_null($priKey_value) && $this->safeMode == true) {
			throw new Exception('Cannot update a record without a where clause in safe mode');
		} elseif(!is_null($priKey_value)) {
			$sql .= '  WHERE `' . $this->primaryKey . '` = ' . $priKey_value;
		}
		
		var_dump($sql);
		
		$query = $this->prepare($sql);
		return $query->execute();
	}
	
	/**
	 * Removes a single record
	 * 
	 * @param mixed $id The record's primary key
	 * @param string $key A column the primary key should be reset as
	 * @param boolean $partial Remove partial matches
	 * @returns integer Number of affected rows
	 */
	public function remove($id, $key=null, $partial=false) 
	{
		// impose restrictions
		if ($partial && $this->safeMode) {
			throw new \Exception('Cannot remove a partially matched record in safe mode');
		}
		
		$sql = 'DELETE FROM `:table:` WHERE `:key` :match_type :value';
		$query = $this->prepare($sql);
		
		if ($partail) {
			$id = $this->quote(strpad($id, strlen($id) + 2, '%', \STR_PAD_BOTH));
		} elseif (is_string($lookup)) {
			$id = $this->quote($lookup);
		}
		
		$query->bindParam('table', $this->tableName);
		$query->bindParam('key', $key ?: $this->primaryKey);
		$query->bindParam('match_type', ((!$partail) ? '=' : 'LIKE'));
		$query->bindParam('value', $id);
		
		return $query->execute();
	}
}