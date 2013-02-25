<?php

include_once('/srv/www/htdocs/m/table.php');

class OccStats extends Table {
	protected function bind_StateITJobs(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->stateName, $row->jobs);
		$this->throwExceptionOnError();
	}
	public function getStateITJobs($year) {
		$query = "SELECT stateName, SUM(jobs) as jobs
			FROM occStateITStats
			WHERE year='$year'
				AND stateName NOT IN ('District of Columbia', 'Guam', 'Puerto Rico',
					'Virgin Islands')
			GROUP BY stateName";

		return $this->get_rows($query, 'bind_StateITJobs');
	}

	// Input: year
	// Output: stateAbr, stateName, percentITGSP/percentAllGSP
	// 	[(State Wide IT Jobs)*(State Wide IT Jobs Avg Salary)] / 
	//	[(State Wide All Jobs)*(State Wide All Jobs Avg Salary)] * 100
	public function bind_GSProws(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->stateAbr, $row->stateName, $row->gsp);
		$this->throwExceptionOnError();
	}
	public function getPercentITofGSP($year) {
		$query = "SELECT stateAbr, stateName, SUM(jobs)*AVG(salary) as gsp
			FROM %s WHERE year = '$year' %s
			GROUP BY stateAbr ORDER BY stateAbr";

		$totalRows = $this->get_rows(
			sprintf($query, 'occStateStats', 'AND occ=\'00-0000\''),
			'bind_GSProws');
		$itRows = $this->get_rows(
			sprintf($query, 'occStateITStats', ''),
			'bind_GSProws');

		$row = new stdClass();
		for($i=0; $i<count($itRows); $i++) {
			$row->stateAbr = $itRows[$i]->stateAbr;
			$row->stateName = $itRows[$i]->stateName;
			$row->gsp = 100*number_format($itRows[$i]->gsp / $totalRows[$i]->gsp, 4);

			$rows[] = $row;
			$row = new stdClass();
		}

		return $rows;
	}

	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
	public function bind_itStatsByYear(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->salary);
		$this->throwExceptionOnError();
	}
	public function getOccITStatsByState($state) {
		$query = "SELECT year, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats WHERE stateAbr='$state'
			GROUP BY year ASC";

		return $this->get_rows($query, 'bind_itStatsByYear');
	}

	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
	public function getOccITStatsByStateByOcc($state, $occ) {
		$query = "SELECT year, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats WHERE stateAbr='$state' AND occ = '$occ'
			GROUP BY year ASC";	

		return $this->get_rows($query, 'bind_itStatsByYear');
	}

	//by year => of sum of all data for a given state 
	public function bind_itStatsByOcc(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->jobs, $row->salary);
		$this->throwExceptionOnError();
	}
	public function getOccITStatsByYearByState($year, $state) {
		$query = "SELECT occ, occName, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats WHERE year = '$year' AND stateAbr = '$state'
			GROUP BY occ";	

		return $this->get_rows($query, 'bind_itStatsByOcc');		
	}

	//by year => of sum of all data for all states
	public function getOccNationalITStatsByYear($year) {
		$query = "SELECT occ, occName, SUM(jobs) AS jobs, AVG(salary) AS salary
			FROM occStateITStats WHERE year = '$year' GROUP BY occ";	

		return $this->get_rows($query, 'bind_itStatsByOcc');	
	}

	//A method to retrieve all information about all IT occupations
	public function bind_itDetails(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->occ, $row->occName, $row->descrip,
			$row->source);
		$this->throwExceptionOnError();
	}
	public function getOccITDetails() {
		$query = 'SELECT occ, occName, descrip, source FROM occNamesIT ORDER BY occName';

		return $this->get_rows($query, 'bind_itDetails');
	}

	//A method to retrieve all information about in occupations in a given state
	public function getOccITDetailsByState($state) {
		$query = "SELECT occ, occName, occDescrip, occSource
			FROM occStateITStats  WHERE stateAbr = '$state'
			GROUP BY occ ORDER BY occName";

		return $this->get_rows($query, 'bind_itDetails');
	}


	public function getOccNonITDetailsByState($state) {
		$query = "SELECT occ, occName, occDescrip, occSource
			FROM occStateNonITStats WHERE stateAbr = '$state'
			GROUP BY occ ORDER BY occName";

		return $this->get_rows($query, 'bind_itDetails');
	}
}
?>
