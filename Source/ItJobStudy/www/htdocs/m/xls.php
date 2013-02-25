<?php

//require_once('Spreadsheet/Excel/Writer.php');
//
//class XLSdoc extends Spreadsheet_Excel_Writer {
//	private $worksheet;
//
//	public function __construct() {
//		$this->worksheet =& $this->addWorksheet();
//	}
//
//	public function __destruct() {
//		$this->close();
//	}
//
//	private function write_row($rowNo, &$data) {
//		$colNo = 0;
//		foreach($data as $datum) {
//			$this->worksheet->write($rowNo, $colNo++, $datum);
//		}
//	}
//
//	public function write_file($filename, $header, $data) {
//		$rowNo = 0;
//		$this->write_row($rowNo++, $header);
//
//		foreach($data as $datum) {
//			$this->write_row($rowNo++, $datum);
//		}
//
//		$workbook->send($filename);
//	}
//}


header('Content-type: text/csv');
$header = 'Content-Disposition:attachment; filename=%s.csv';
if('occ' == $_GET['tbl']) {
	require_once('./OccStats.php');
	$data = new OccStats();

	$func = $_GET['func'];
	if('getITStatsByState' == $func) {
		header(sprintf($header, "{$_GET['state']}_Occupational_IT_Statistics"));
		$rows = $data->getOccITStatsByState($_GET['state']);

		echo "Year,Total Jobs,Average Salary\n";
		foreach($rows as $row) {
			echo "{$row->year},{$row->jobs},{$row->salary}\n";
		}
	} elseif('getNationalITStatsByYear' == $func) {
		header(sprintf($header, "{$_GET['year']}_National_Occupational_IT_Statistics"));
		$rows = $data->getOccNationalITStatsByYear($_GET['year']);

		echo "Occ Code,Occ Name,Total Jobs,Average Salary\n";
		foreach($rows as $row) {
			echo "{$row->occ},\"{$row->occName}\",{$row->jobs},{$row->salary}\n";
		}
	}
} elseif('ind' == $_GET['tbl']) {
	require_once('./IndStats.php');
	$data = new IndStats();

	$func = $_GET['func'];
	if('getITStatsByState' == $func) {
		header(sprintf($header, "{$_GET['state']}_Industrial_IT_Statistics"));
		$rows = $data->getIndITStatsByState($_GET['state']);

		echo "Year,Total Jobs,Reporting Organizations,Average Salary\n";
		foreach($rows as $row) {
			echo "{$row->year},{$row->jobs},{$row->orgs},{$row->salary}\n";
		}
	} elseif('getNationalITStatsByYear' == $func) {
		header(sprintf($header, "{$_GET['year']}_National_Industrial_IT_Statistics"));
		$rows = $data->getIndNationalITStatsByYear($_GET['year']);

		echo "NAICS Code,Industry Name,Total Jobs,Reporting Organizations,Average Salary\n";
		foreach($rows as $row) {
			echo "{$row->naics},\"{$row->indName}\",{$row->jobs},{$row->orgs},{$row->salary}\n";
		}
	}
} else {
	echo 'Unrecognized or missing tbl variable';
}
?>
