<?php

class Table {
	var $connection;

	public function __construct() {
		include('/srv/www/htdocs/m/dbConnect.php');

		$this->connection = mysqli_connect(
			$server, $username, $password, $databasename, $port);
		$this->throwExceptionOnError($this->connection);
	}

	public function __destruct() {
		mysqli_close($this->connection);
		$this->throwExceptionOnError($connection);
	}

	/** 
	  * Utitity function to throw an exception if an error indurs 
	  * while running a mysql command. 
	  */ 
	protected function throwExceptionOnError($link = null) { 
		if($link == null) { 
			$link = $this->connection; 
		}

		if(mysqli_error($link)) { 
			$msg = mysqli_errno($link) . ": " . mysqli_error($link); 
			throw new Exception('MySQL Error - '. $msg); 
		}         
	}

	protected function get_rows($query, $bind_func) {
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		$this->$bind_func($stmt, $row);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			$this->$bind_func($stmt, $row);
		}

		mysqli_stmt_free_result($stmt);

		return $rows;		
	}
}
?>
