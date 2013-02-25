<html><head><title>Testing Gateway</title></head><body>
<?php
if('occ' == $_GET['tbl']) {
	require_once('./OccStats.php');
	$data = new OccStats();

	$func = $_GET['func'];
	if('getPercentITofGSP' == $func) {
		$rows = $data->getPercentITofGSP($_GET['year']);

		foreach($rows as $row) {
			echo "$row->stateAbr, $row->stateName, $row->gsp<br />";
		}
	} elseif('getITStatsByState' == $func) {
		$rows = $data->getOccITStatsByState($_GET['state']);

		foreach($rows as $row) {
			echo "{$row->year}, {$row->jobs}, {$row->salary}<br />";
		}
	} elseif('getITStatsByStateByOcc' == $func) {
		$rows = $data->getOccITStatsByStateByOcc($_GET['state'], $_GET['occ']);

		foreach($rows as $row) {
			echo "{$row->year}, {$row->jobs}, {$row->salary}<br />";
		}
	} elseif('getITStatsByYearByState' == $func) {
		$rows = $data->getOccITStatsByYearByState($_GET['year'], $_GET['state']);

		foreach($rows as $row) {
			echo "{$row->occ}, {$row->occName}, {$row->occDescrip},
				{$row->jobs}, {$row->salary}<br />";
		}
	} elseif('getNationalITStatsByYear' == $func) {
		$rows = $data->getOccNationalITStatsByYear($_GET['year']);

		foreach($rows as $row) {
			echo "$row->occ, $row->occName, $row->jobs, $row->salary<br />";
		}
	} elseif('getITDetails' == $func) {
		$rows = $data->getOccITDetails();

		foreach($rows as $row) {
			echo "$row->occ, $row->occName, $row->descrip, $row->source<br />";
		}
	} elseif('getITDetailsByState' == $func) {
		$rows = $data->getOccITDetailsByState($_GET['state']);

		foreach($rows as $row) {
			echo "$row->occ, $row->occName, $row->descrip, $row->source<br />";
		}
	} elseif('getNonITDetailsByState' == $func) {
		$rows = $data->getOccNonITDetailsByState($_GET['state']);

		foreach($rows as $row) {
			echo "$row->occ, $row->occName, $row->descrip, $row->source<br />";
		}
	} elseif('getStateITJobs' == $func) {
		$rows = $data->getStateITJobs($_GET['year']);

		foreach($rows as $row) {
			echo "$row->stateName, $row->jobs<br />";
		}
	} else {
		echo 'Unrecognized or missing func variable';
	}
} elseif('ind' == $_GET['tbl']) {
	require_once('./IndStats.php');
	$data = new IndStats();

	$func = $_GET['func'];
	if('getPercentITofGSP' == $func) {
		$rows = $data->getPercentITofGSP($_GET['year']);

		foreach($rows as $row) {
			echo "$row->stateAbr, $row->stateName, $row->gsp<br />";
		}
	} elseif('getITStatsByState' == $func) {
		$rows = $data->getIndITStatsByState($_GET['state']);

		foreach($rows as $row) {
			echo "{$row->year}, {$row->jobs}, {$row->orgs}, {$row->salary}<br />";
		}
	} elseif('getITStatsByStateByNaics' == $func) {
		$rows = $data->getIndITStatsByStateByNaics($_GET['state'], $_GET['naics']);

		foreach($rows as $row) {
			echo "{$row->year}, {$row->jobs}, {$row->orgs}, {$row->salary}<br />";
		}
	} elseif('getITStatsByYearByState' == $func) {
		$rows = $data->getIndITStatsByYearByState($_GET['year'], $_GET['state']);

		foreach($rows as $row) {
			echo "{$row->naics}, {$row->indName}, {$row->indDescrip},
				{$row->jobs}, {$row->orgs} {$row->salary}<br />";
		}
	} elseif('getNationalITStatsByYear' == $func) {
		$rows = $data->getIndNationalITStatsByYear($_GET['year']);

		foreach($rows as $row) {
			echo "{$row->naics}, {$row->indName}, {$row->indDescrip},
				{$row->jobs}, {$row->orgs} {$row->salary}<br />";
		}
	} elseif('getITDetails' == $func) {
		$rows = $data->getITIndustryDetails();

		foreach($rows as $row) {
			echo "$row->naics, $row->name, $row->descrip, $row->source<br />";
		}
	} elseif('getITDetailsByState' == $func) {
		$rows = $data->getITIndustryDetailsByState($_GET['state']);

		foreach($rows as $row) {
			echo "$row->naics, $row->name, $row->descrip, $row->source<br />";
		}
	} elseif('getNonITDetailsByState' == $func) {
		$rows = $data->getNonITIndustryDetailsByState($_GET['state']);

		foreach($rows as $row) {
			echo "$row->naics, $row->name, $row->descrip, $row->source<br />";
		}
	} elseif('getStateITJobs' == $func) {
		$rows = $data->getStateITJobs($_GET['year']);

		foreach($rows as $row) {
			echo "$row->stateName, $row->jobs<br />";
		}
	} else {
		echo 'Unrecognized or missing func variable';
	}

} else {
	echo 'Unrecognized or missing tbl variable';
}

//} elseif('Ind' == $_GET['mode']) {
//	require_once('./IndStats.php');
//	$data = new IndStats();
//
//	if($_GET['year'] && $_GET['state']) {
//		$rows = $data->getIndITStatsByYearByState($_GET['year'], $_GET['state']);
//		foreach($rows as $row) {
//			echo "{$row->naics}, {$row->indName}, {$row->indDescrip},
//				{$row->jobs}, {$row->orgs}, {$row->salary}<br />";
//		}
//	} elseif($_GET['state'] && $_GET['naics']) {
//		$rows = $data->getIndITStatsByStateByNaics($_GET['state'], $_GET['naics']);
//		foreach($rows as $row) {
//			echo "{$row->year}, {$row->jobs}, {$row->orgs}, {$row->salary}<br />";
//		}
//	}  elseif($_GET['state']) {
//		$rows = $data->getIndITStatsByState($_GET['state']);
//		foreach($rows as $row) {
//			echo "{$row->year}, {$row->jobs}, {$row->orgs}, {$row->salary}<br />";
//		}
//	} elseif($_GET['year']) {
//		$rows = $data->getIndNationalITStatsByYear($_GET['year']);
//		foreach($rows as $row) {
//			echo "$row->naics, $row->indName, $row->indDescrip,
//				$row->jobs, $row->orgs, $row->salary<br />";
//		}
//	}
//} elseif('IndDetails' == $_GET['mode']) {
//	require_once('./IndStats.php');
//	$data = new IndStats();
//
//	if($_GET['state']) {
//		$rows = $data->getITIndustryDetailsByState($_GET['state']);
//	} elseif($_GET['non_state']) {
//		$rows = $data->getNonITIndustryDetailsByState($_GET['non_state']);
//	} else {
//		$rows = $data->getITIndustryDetails();
//	}
//	foreach($rows as $row) {
//		echo "$row->naics, $row->name, $row->descrip, $row->source<br />";
//	}
//} elseif('IndCSV' == $_GET['mode']) {
//	require_once('./IndStats.php');
//	$data = new IndStats();
//	$data->getNationalITStatsCSV();
//} elseif ('IndGSP' == $_GET['mode']) {
//	require_once('./IndStats.php');
//	$data = new IndStats();
//	$rows = $data->getPercentITofGSP($_GET['year']);
//
//	foreach($rows as $row) { echo "$row->stateAbr, $row->stateName, $row->gsp<br />"; }
//
//}
?>
</body></html>

