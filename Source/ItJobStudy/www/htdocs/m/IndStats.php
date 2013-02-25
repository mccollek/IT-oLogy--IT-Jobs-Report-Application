<?php

include_once('/srv/www/htdocs/m/table.php');

class IndStats extends Table {
	protected function bind_StateITJobs(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->stateName, $row->jobs);
		$this->throwExceptionOnError();
	}
	public function getStateITJobs($year) {
		$query = "SELECT stateName, sum(jobs) AS jobs
			FROM indStateITStats
			WHERE year='$year'
				AND stateName NOT IN ('District of Columbia', 'Guam', 'Puerto Rico',
					'Virgin Islands')
			GROUP BY stateName";

		return $this->get_rows($query, 'bind_StateITJobs');
	}

/*
 *	Provide data representing the percent of a state's domestic production is
 *		produced by IT industries.
 */
	// Utility function to bind database results in $stmt to data structure, $row.
	protected function bind_GSProws(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->stateAbr, $row->stateName, $row->gsp);
		$this->throwExceptionOnError();
	}
	//	Input: year
	//	Output: stateAbr, stateName, percentITGSP/percentAllGSP
	//		[(State Wide IT Jobs)*(State Wide IT Jobs Avg Salary)] / 
	//		[(State Wide All Jobs)*(State Wide All Jobs Avg Salary)] * 100
	public function getPercentITofGSP($year) {
		$query = "SELECT stateAbr, stateName, SUM(jobs)*AVG(salary) as gsp
			FROM %s WHERE year = '$year' %s GROUP BY stateAbr ORDER BY stateAbr";

		// get
		$totalRows = $this->get_rows(sprintf($query, 'indStateStats', 'AND naics=\'10\''),
			'bind_GSProws');
		$itRows = $this->get_rows(sprintf($query, 'indStateITStats', ''),
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
	public function bind_ITStatsByYear(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->year, $row->jobs, $row->orgs, $row->salary);
		$this->throwExceptionOnError();
	}
	public function getIndITStatsByState($state) {
		$query = "SELECT year, SUM(jobs) AS jobs, SUM(orgs) AS orgs, AVG(salary) AS salary
			FROM indStateITStats WHERE stateAbr='$state' GROUP BY year ASC";

		return $this->get_rows($query, 'bind_ITStatsByYear');		
	}

	//IT jobs & salary by state:
	// 	2000, SUM(jobs), SUM(salary)
	public function getIndITStatsByStateByNaics($state, $naics) {
		$query = "SELECT year, SUM(jobs) AS jobs, SUM(orgs) AS orgs, AVG(salary) AS salary
			FROM indStateITStats WHERE stateAbr='$state' AND naics = '$naics'
			GROUP BY year ASC";


		return $this->get_rows($query, 'bind_ITStatsByYear');		
	}

	//by year => of sum of all data for a given state 
	protected function bind_itStatsByNaics(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt,
			$row->naics, $row->indName, $row->indDescrip,
			$row->jobs, $row->orgs, $row->salary);
		$this->throwExceptionOnError();
	}
	public function getIndITStatsByYearByState($year, $state) {
		$query = "SELECT naics, indName, indDescrip,
				SUM(jobs) AS jobs, SUM(orgs) as orgs, AVG(salary) AS salary
			FROM indStateITStats WHERE year = '$year' AND stateAbr = '$state'
			GROUP BY naics";

		return $this->get_rows($query, 'bind_itStatsByNaics');
	}

	public function getIndNationalITStatsByYear($year) {
		//by year => of sum of all data for all states
		$query = "SELECT naics, indName, indDescrip,
				SUM(jobs) AS jobs, SUM(orgs) as orgs, AVG(salary) AS salary
			FROM indStateITStats WHERE year = '$year' GROUP BY naics";	

		return $this->get_rows($query, 'bind_itStatsByNaics');		
	}


	protected function bind_itIndustryDetails(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->naics, $row->name, $row->descrip,
			$row->source);

		$this->throwExceptionOnError();
	}
	public function getITIndustryDetails() {
		$query = 'SELECT naics, name, descrip, source FROM industriesIT ORDER BY name';

		return $this->get_rows($query, 'bind_itIndustryDetails');
	}

	public function getITIndustryDetailsByState($state) {
		$query = "SELECT naics, indName, indDescrip, indSource 
			FROM indStateITStats WHERE stateAbr = '$state'
			GROUP BY naics ORDER BY indName";

		return $this->get_rows($query, 'bind_itIndustryDetails');
	}

	public function getNonITIndustryDetailsByState($state) {
		$query = "SELECT naics, indName, indDescrip, indSource 
			FROM indStateNonITStats WHERE stateAbr = '$state'
			GROUP BY naics ORDER by indName";

		return $this->get_rows($query, 'bind_ITIndustryDetails');
	}


//IT naics, job names, jobs, and salary by state:
//	2010, naics, job name, jobs, salary
	protected function bind_year_extrema(&$stmt, &$row) {
		mysqli_stmt_bind_result($stmt, $row->min, $row->max);
		$this->throwExceptionOnError();
	}
	public function get_year_extrema() {
		$query = 'SELECT min(indStateITStats.year), max(indStateITStats.year)
			 FROM indStateITStats';

		return $this->get_rows($query, 'bind_yearExtrema');
	}
}
?>
