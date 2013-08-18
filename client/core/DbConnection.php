<?php

class DbConnection {
	private static $instance;
	private $connection;

	private function __construct() {
	}

	public static function getInstance() {
		if (! isset( self::$instance )) {
			$className = __CLASS__;
			self::$instance = new $className();
			self::$instance->setConnection();
		}
		return self::$instance;
	}

	public function __clone() {
		trigger_error( 'Cloning not supported', E_USER_ERROR );
	}

	public function __wakeup() {
		trigger_error( 'Deserialisation not supported', E_USER_ERROR );
	}

	public function getConnection() {
		return $this->connection;
	}

	public function setConnection() {
		$this->connection = new \PDO( DATASOURCE , DB_USER, DB_PASS, array (
//				\PDO::ATTR_PERSISTENT => true
		) );
		$this->connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
	}
}

?>
