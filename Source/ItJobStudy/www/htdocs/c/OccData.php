<?php

class OccData {
	var $connection;

	public function __construct() {
		$server = "localhost"; 
		$port = "3306"; 
		$username = "root"; 
		$password = "connect2it"; 
		$databasename = "itology"; 
		$this->connection = mysqli_connect(
			$server, $username, $password, $databasename, $port);

		$this->throwExceptionOnError($this->connection);
	}

	public function getOccITStatsByState($state) {
	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
		$query = "SELECT year, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats
			WHERE stateAbr='$state'
			GROUP BY year ASC";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getOccITStatsByStateByOcc($state, $occ) {
	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
		$query = "SELECT year, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats
			WHERE stateAbr='$state' AND occ = '$occ'
			GROUP BY year ASC";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getOccITStatsByYearByState($year, $state) {
		//by year => of sum of all data for a given state 
		$query = "SELECT occ, occName,
				SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats
			WHERE year = '$year' AND stateAbr = '$state'
			GROUP BY occName";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt,
			$row->occ, $row->occName, $row->jobs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->occ, $row->occName, $row->jobs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getOccNationalITStatsByYear($year) {
		//by year => of sum of all data for all states
		$query = "SELECT occ, occName,
				SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats
			WHERE year = '$year'
			GROUP BY occName";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt,
			$row->occ, $row->occName, $row->jobs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->occ, $row->occName, $row->jobs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getOccITDetails() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT occ, occName, descrip, source FROM occNamesIT ORDER BY occName');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}

	public function getOccITDetailsByState($state) {
		$stmt = mysqli_prepare($this->connection,
			"SELECT occ, occName, occDescrip, occSource
			 FROM occStateITStats
			 WHERE stateAbr = '$state'
			 GROUP BY occName");
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}


	public function getOccNonITDetailsByState($state) {
		$stmt = mysqli_prepare($this->connection,
			"SELECT occ, occName, occDescrip, occSource
			 FROM occStateNonITStats
			 WHERE stateAbr = '$state'
			 GROUP BY occName");
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}



	public function get_year_extrema() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT min(occStateITStats.year), max(occStateITStats.year)
			 FROM occStateITStats');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->min, $row->max);
		mysqli_stmt_fetch($stmt);
		return $row;
	}

	public function getNationalITStatsCSV() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT year, SUM(jobs) as jobs, AVG(salary) as salary
			 FROM occStateITStats
			 GROUP BY year ASC');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename=NationalITStats.csv");
		echo "Year, Total Jobs, Average Salary\n";
		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		while (mysqli_stmt_fetch($stmt)) {
 			echo "$row->year, $row->jobs, $row->salary\n";
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);
	}

	/** 
	  * Utitity function to throw an exception if an error indurs 
	  * while running a mysql command. 
	  */ 
	private function throwExceptionOnError($link = null) { 
		if($link == null) { 
			$link = $this->connection; 
		} 
		if(mysqli_error($link)) { 
			$msg = mysqli_errno($link) . ": " . mysqli_error($link); 
			throw new Exception('MySQL Error - '. $msg); 
		}         
	}
}
?>
