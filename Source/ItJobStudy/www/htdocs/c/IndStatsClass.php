<?php
/** 
  * Industry Stats Class MySQL database script 
  * $password variable must be changed prior to deployment
  */
class IndStatsClass {
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

	public function getIndITStats($state, $naics, $year) {
		$query = 'SELECT * FROM indStateITStats';
		if(NULL != $state) {
			$query .= " WHERE stateAbr = '$state'";
			if(NULL != $naics) {
				$query .= " AND naics = '$naics'";
			}
			if(NULL != $year) {
				$query .= " AND year = '$year'";
			}
		} elseif(NULL != $occ) {
			$query .= " WHERE naics = '$naics'";
			if(NULL != $year) {
				$query .= " AND year = '$year'";
			}
		} elseif(NULL != $year) {
				$query .= " WHERE year = '$year'";
		}

		$stmt = mysqli_prepare($this->connection, $query);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();

		mysqli_stmt_bind_result($stmt,
			$row->naics, $row->indName, $row->indDescrip, $row->indSource,
			$row->stateNo, $row->stateAbr, $row->stateName,
			$row->jobs, $row->orgs, $row->salary, $row->year);
		while(mysqli_stmt_fetch($stmt)) {
			$rows[] = $row;
			$row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->naics, $row->indName, $row->indDescrip, $row->indSource,
				$row->stateNo, $row->stateAbr, $row->stateName,
				$row->jobs, $row->orgs, $row->salary, $row->year);
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

	public function generate_excel($year1, $year2) {
		$stmt = mysqli_prepare($this->connection,
			'SELECT stateNo, stateAbr, stateName,
				occ, occName, jobs, salary, year
			 FROM occStateITStats
			 WHERE occStateITStats.year BETWEEN ? AND ?
			 ORDER BY year');
		$this->throwExceptionOnError();

		mysqli_stmt_bind_param($stmt, 'ss', $year1, $year2);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=$year1-$year2.xls");
		echo '<html><body><table>';
		echo '<tr><td>State No</td><td>State Abbreviation</td><td>State Name</td>
			<td>Indupation Code</td><td>Indupation Name</td><td>Jobs</td><td>Salary</td>
			<td>Year</td></tr>';	
	
		mysqli_stmt_bind_result($stmt,
			$row->stateNo, $row->stateAbr, $row->stateName,
			$row->occ, $row->occName,
			$row->jobs, $row->salary, $row->year);

		while (mysqli_stmt_fetch($stmt)) {
			echo '<tr>';
			foreach($row as $datum) {
				echo "<td>$datum</td>";
			}
			echo '</tr>';
		    $row = new stdClass();
			mysqli_stmt_bind_result($stmt,
				$row->stateNo, $row->stateAbr, $row->stateName,
				$row->occ, $row->occName,
				$row->jobs, $row->salary, $row->year);
		}
		
		echo '</table></html></body>';

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
