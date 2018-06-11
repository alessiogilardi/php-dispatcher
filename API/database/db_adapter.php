<?php

/**
 * 
 */
class DbAdapter {
	private const CONNECTION_FAILED = -10;
	private const MISSING_CONNECTION = -11;
	private const BAD_INPUT = -12;
	private const INSERT_FAIL = -13;

	private $_serverName = '127.0.0.1';
	private $_username 	 = 'root';
	private $_password 	 = 'root';
	private $_dbName;

	private $_connection;

	
	function __construct() {
		# code...
	}

	function __destruct() {
		if ($this->alredyConnected())
			$this->disconnect();
    }

	public function setServerName($serverName) {
		$this->_serverName = $name;
	}

	public function setDbName($dbName) {
		$this->_dbName = $dbName;
	}

	public function setUsername($username) {
		$this->_username = $username;
	}

	public function setPassword($password) {
		$this->_password = $password;
	}

	public function getServerName() {
		return $this->_serverName;
	}

	public function getDbName() {
		return $this->_dbName;
	}

	public function getUsername() {
		return $this->_username;
	}

	public function getPassword() {
		return $this->_password;
	}

	public function connect() {
		if ($this->alredyConnected()) {
			$this->disconnect();
		}
		$this->_connection = new mysqli($this->getServerName(),
			$this->getUsername(), 
			$this->getPassword(), 
			$this->getDbName());
  		if ($this->_connection->connect_error) {
    		throw new RuntimeException('Failed to connect: '.$this->_connection->connect_error, DBAdapter::CONNECTION_FAILED);
    	}
	}

	public function disconnect() {
		$this->_connection->close();
	}

	private function alredyConnected() {
		return isset($this->_connection);
	}

	public function insert($tableName, $fieldsNames, $values, $valuesType) {
		if (!$this->alredyConnected()) {
			throw new RuntimeException('Missing connection.', DBAdapter::MISSING_CONNECTION);
		}

		foreach ($values as $row) {
			if (count($row) != count($values[0])) {
				throw new RuntimeException("Insert input badly formed: \n".var_dump($values), BAD_INPUT);
			}
		}

		$fieldsNames  = implode(',', $fieldsNames);
		$placeHolders = implode(',', array_fill( 0, count($values[0]), '?'));
		$paramType    = implode('', $valuesType).$whereValueType;
  		$aParams      = array();
  		$aParams[]    = &$paramType;

  		$query       = 'INSERT INTO $table ('.$fieldsNames.') VALUES ('.$placeHolders.')';
		$stmt        = $this->_connection->prepare($query);

		$insertedIds = array();
		foreach ($values as $row) {
		    foreach ($row as $v)
		    	$v = $this->_connection->real_escape_string($v);
		    foreach ($row as &$v)
		    	$aParams[] = &$v;
    		call_user_func_array(array($stmt, 'bind_param'), $aParams);
		    $stmt->execute();

		    if ($stmt->affected_rows <= 0) {
		      	$stmt->close();
	 			throw new RuntimeException('Isertion failed: '.$stmt->error, INSERT_FAIL);
		    }

		    $insertedIds[] = $connection->insert_id;
  		}

  		$stmt->close();
  		return $insertedIds;
	}

	public function delete($tableName, $whereField, $whereValue, $whereValueType) {
		if (!$this->alredyConnected()) {
			throw new RuntimeException('Missing connection.', DBAdapter::MISSING_CONNECTION);
		}

	}

	public function update($tableName, $fieldsNames, $values, $valuesType, $whereField, $whereValue, $whereValueType) {
		if (!$this->alredyConnected()) {
			throw new RuntimeException('Missing connection.', DBAdapter::MISSING_CONNECTION);
		}
	}

	public function select($tableName, $fieldsNames = null, $whereField = '', $whereValue = '', $whereValueType = '') {
		if (!$this->alredyConnected()) {
			throw new RuntimeException('Missing connection.', DBAdapter::MISSING_CONNECTION);
		}

		$fieldsNames = isset($fieldsNames) ? implode(',', $fieldsNames) : '*';
		$query = 'SELECT '.$fieldsNames.' FROM '.$tableName.' WHERE $whereField =?';
		$whereValue = $this->_connection->real_escape_string($whereValue);

		$stmt = $this->_connection->prepare($query);
		$stmt->bind_param($whereValueType, $whereValue);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result->num_rows >= 1) {
			return $result->fetch_assoc();
		}
		return null;
	}

}

?>