<?php
/*

PHP/MySQL (PDO)  method with named parameters
---------------------------------------------

https://gist.github.com/luckyshot/9477105



define("MYSQL_SERVER", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "root");
define("MYSQL_DATABASE", "database");

$db = new DB(MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

$userid = $db->query("INSERT INTO users VALUES (NULL, :name, :date)")
	->bind(':title', $name)
	->bind(':date', date("Y-m-d H:i:s"))
	->insert();

*/

class DB {

	private $dbh;
	private $stmt;

	public function __construct($user, $pass, $dbname) {
		$this->dbh = new PDO(
			"mysql:host=localhost;dbname=$dbname",
			$user,
			$pass,
			array( PDO::ATTR_PERSISTENT => true )
		);
		$this->query("SET NAMES 'utf8';");
		$this->execute();
  }

	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
		return $this;
	}

	public function bind($pos, $value, $type = null) {

		if( is_null($type) ) {
			switch( true ) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}

		$this->stmt->bindValue($pos, $value, $type);
		return $this;
	}

	public function execute() {
		return $this->stmt->execute();
	}

	// Same as execute() but returns ID
	public function insert() {
		$this->stmt->execute();
		return $this->dbh->lastInsertId();
	}

	public function resultset() {
		$this->execute();
		return $this->stmt->fetchAll();
	}

	public function single() {
		$this->execute();
		return $this->stmt->fetch();
	}
}
