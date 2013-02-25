<?php

class OccStatsData {
	var $connection;

	public function __construct() {
		require_once('./dbConnect.php');
		$this->connection = mysqli_connect(
			$server, $username, $password, $databasename, $port);

		$this->throwExceptionOnError($this->connection);
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

	public function generate_csv($year1, $year2) {
		$stmt = mysqli_prepare($this->connection,
			'SELECT occStateITStats.stateNo,
				occStateITStats.stateAbr,
				occStateITStats.stateName,
				occStateITStats.occ,
				occStateITStats.occName,
				occStateITStats.jobs,
				occStateITStats.salary,
				occStateITStats.year
			 FROM occStateITStats
			 WHERE occStateITStats.year BETWEEN ? AND ?
			 ORDER BY occStateITStats.year');
		$this->throwExceptionOnError();

		mysqli_stmt_bind_param($stmt, 'ss', $year1, $year2);
		$this->throwExceptionOnError();

		mysqli_stmt_execute($stmt);
		$this->throwExceptionOnError();
		
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=$year1-$year2.xls");
		echo '<html><body><table>';
		echo '<tr><td>State No</td><td>State Abbreviation</td><td>State Name</td>
			<td>Occupation Code</td><td>Occupation Name</td>
			<td>Jobs</td><td>Salary</td><td>Year</td></tr>';	
	
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

if($_GET['year1']) {
	$data = new OccStatsData();
	$year1 = $_GET['year1'];

	if($_GET['year2']) {
		$year2 = $_GET['year2'];
		$data->generate_csv($year1, $year2);
	} else {
		$data->generate_csv($year1, $year1);
	}
} else {
	$data = new OccStatsData();
	$extrema = $data->get_year_extrema();
	$max = ($extrema->max - $extrema->min) / 3;

	echo '<h3>Choose a range of years:</h3>';
	echo '<ul>';
	for($i =  0; $i < $max; $i++) {
		$year1 = $i*3 + $extrema->min;
		$year2 = $i*3 + $extrema->min + 2;
		if($extrema->max < $year2) {
			$year2 = $extrema->max;
		}

		echo "<li><a href='create_occStateITFile.php?year1=$year1&year2=$year2'>";
		echo "$year1-$year2.xls</a></li>";
	}
	echo '</ul>';
}

?>
