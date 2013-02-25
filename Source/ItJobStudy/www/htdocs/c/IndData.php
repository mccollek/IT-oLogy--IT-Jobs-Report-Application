<?php
/** 
  * Industries Data MySQL database script 
  * $password variable must be changed prior to deployment
  */
class IndData {
	var $connection;

	public function __construct() {
		$server = "localhost"; 
		$port = "3306"; 
		$username = "root"; 
		$password = ""; 
		$databasename = "itology"; 
		$this->connection = mysqli_connect(
			$server, $username, $password, $databasename, $port);

		$this->throwExceptionOnError($this->connection);
	}
	
		public function getIndITStatsByState($state) {
	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
		$query = "SELECT year, SUM(jobs) AS jobs, SUM(orgs) AS orgs, AVG(salary) AS salary
			FROM indStateITStats
			WHERE stateAbr='$state'
			GROUP BY year ASC";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs,
				$row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getIndITStatsByStateByNaics($state, $naics) {
	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
		$query = "SELECT year, SUM(jobs) AS jobs, SUM(orgs) AS orgs, AVG(salary) AS salary
			FROM indStateITStats
			WHERE stateAbr='$state' AND naics = '$naics'
			GROUP BY year ASC";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs,
				$row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getIndITStatsByYearByState($year, $state) {
		//by year => of sum of all data for a given state 
		$query = "SELECT naics, indName, indDescrip,
				SUM(jobs) AS jobs, SUM(orgs) as orgs, AVG(salary) AS salary
			FROM indStateITStats
			WHERE year = '$year' AND stateAbr = '$state'
			GROUP BY naics";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt,
			$row->naics, $row->indName, $row->indDescrip,
			$row->jobs, $row->orgs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->naics, $row->indName, $row->indDescrip,
				$row->jobs, $row->orgs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}

	public function getIndNationalITStatsByYear($year) {
		//by year => of sum of all data for all states
		$query = "SELECT naics, indName, indDescrip,
				SUM(jobs) AS jobs, SUM(orgs) as orgs, AVG(salary) AS salary
			FROM indStateITStats
			WHERE year = '$year'
			GROUP BY naics";	
		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt,
			$row->naics, $row->indName, $row->indDescrip,
			$row->jobs, $row->orgs, $row->salary);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->naics, $row->indName, $row->indDescrip,
				$row->jobs, $row->orgs, $row->salary);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;		
	}


	public function getITIndustryDetails() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT naics, name, descrip, source FROM industriesIT');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}

	public function getITIndustryDetailsByState($state) {
		$stmt = mysqli_prepare($this->connection,
			"SELECT naics, indName, indDescrip, indSource 
			 FROM indStateITStats
			 WHERE stateAbr = '$state'
			 GROUP BY naics");
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}

	public function getNonITIndustryDetailsByState($state) {
		$stmt = mysqli_prepare($this->connection,
			"SELECT naics, indName, indDescrip, indSource 
			 FROM indStateNonITStats
			 WHERE stateAbr = '$state'
			 GROUP BY naics");
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
			$row->source);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass;

			mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
				$row->source);
		}

		mysqli_stmt_free_result($stmt);
		mysqli_close($this->connection);

		return $rows;
	}


//	public function get
//IT naics, job names, jobs, and salary by state:
//	2010, naics, job name, jobs, salary

	public function get_year_extrema() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT min(indStateITStats.year), max(indStateITStats.year)
			 FROM indStateITStats');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt, $row->min, $row->max);
		mysqli_stmt_fetch($stmt);
		return $row;
	}

	public function getNationalITStatsCSV() {
		$stmt = mysqli_prepare($this->connection,
			'SELECT year, SUM(jobs) as jobs, SUM(orgs) as orgs, AVG(salary) as salary
			 FROM indStateITStats
			 GROUP BY year ASC');
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename=NationalITStats.csv");
		echo "Year, Total Jobs, Total Organizations Reporting, Average Salary\n";
		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs, $row->salary);
		while (mysqli_stmt_fetch($stmt)) {
 			echo "$row->year, $row->jobs, $row->orgs, $row->salary\n";
			mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs,
				$row->salary);
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
