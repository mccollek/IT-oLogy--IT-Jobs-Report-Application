<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>IT-oLogy</title>
<link href="reset.css" rel="stylesheet" type="text/css">
<link href="index.css" rel="stylesheet" type="text/css">
<script language="JavaScript" 
type="text/JavaScript">
function getInternetExplorerVersion() {
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer') {
        var ua = navigator.userAgent;
        var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat(RegExp.$1);
    }
    return rv;
}
function checkVersion() {
    var msg = "You're not using Windows Internet Explorer.";
    var ver = getInternetExplorerVersion();
    if (ver > -1) {
        if (ver < 9.0){
            msg = "This site is designed to work with Firefox, Chrome, and Internet Explorer 9.0.  Please consider upgrading your copy of Windows Internet Explorer or accessing this site with a supported browser for the best experience.";
    		alert(msg);
	}
    }
}

checkVersion();
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php
	include_once('/srv/www/htdocs/m/IndStats.php');
	$Ind = new IndStats();
	$IndRows = $Ind->getStateITJobs('2010');
	$IndRowsCount = count($IndRows);
?>


<?php
	include_once('/srv/www/htdocs/m/OccStats.php');
	$Occ = new OccStats();
	$OccRows = $Occ->getStateITJobs('2010');
	$OccRowsCount = count($OccRows);
?>

<?php
$toggleImage;
	if('occ' == $_GET['map'])
			{
				$toggleImage = "resources/toggleLeft.png";
				echo "<style type=\"text/css\">", PHP_EOL;
				echo ".OccMap {", PHP_EOL;
				echo "visibility: visible;", PHP_EOL;
				echo "}", PHP_EOL;
				echo ".IndMap {", PHP_EOL;
				echo "visibility: hidden;", PHP_EOL;
				echo "}", PHP_EOL;
				echo "</style>", PHP_EOL;
			}
			elseif('ind' == $_GET['map'])
			{
				$toggleImage = "resources/toggleRight.png";
				echo "<style type=\"text/css\">", PHP_EOL;
				echo ".IndMap {", PHP_EOL;
				echo "visibility: visible;", PHP_EOL;
				echo "}", PHP_EOL;
				echo ".OccMap {", PHP_EOL;
				echo "visibility: hidden;", PHP_EOL;
				echo "}", PHP_EOL;
				echo "</style>", PHP_EOL;
			}
			else
			{
				$toggleImage = "resources/toggleLeft.png";
				echo "<style type=\"text/css\">", PHP_EOL;
				echo ".OccMap {", PHP_EOL;
				echo "visibility: visible;", PHP_EOL;
				echo "}", PHP_EOL;
				echo ".IndMap {", PHP_EOL;
				echo "visibility: hidden;", PHP_EOL;
				echo "}", PHP_EOL;
				echo "</style>", PHP_EOL;
			}
?>
       
 <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">
    google.load('visualization', '1', {packages: ['geochart']});

    function drawOcc() {
      var OccData = new google.visualization.DataTable();
	  <?php
	  	echo "OccData.addRows($OccRowsCount);", PHP_EOL;
		echo "OccData.addColumn('string', 'Region');", PHP_EOL;
		echo "OccData.addColumn('number', 'Tier');", PHP_EOL;
		echo "OccData.addColumn('number', 'IT Professionals');", PHP_EOL;
		
		$count = 0;
		$tier = 1;
		
		foreach($OccRows as $OccRow) {
			echo "OccData.setValue($count, 0, '$OccRow->stateName');", PHP_EOL;
			if($OccRow->jobs <= '45000')
			{
				$tier = 1;
			}
			elseif($OccRow->jobs <= '90000')
			{
				$tier = 2;
			}
			elseif($OccRow->jobs <= '180000')
			{
				$tier = 3;
			}
			elseif($OccRow->jobs > '180000')
			{
				$tier = 4;
			}
			
			echo "OccData.setValue($count, 1, $tier);", PHP_EOL;
			echo "OccData.setValue($count, 2, $OccRow->jobs);", PHP_EOL;
			
			$count++;
		}
		
	  ?>
      
      var options = 
        {
          region: 'US',
          resolution: 'provinces',
          //colorAxis: {colors: ['#ffffff','#99ff99','#66cc66','#339933','#006600']},
		  colorAxis: {colors: ['#66cc66','#339933','#006600']},
          tooltip: {textStyle: {color: 'black'}, showColorCode: true},
		  backgroundColor: {strokeWidth: 2, stroke: '#FFF', fill:'#FFF'},
		  backgroundColor: 'transparent',
		  datalessRegionColor: '#FFFFFF'
        };
      
      var OccChart = new google.visualization.GeoChart(
          document.getElementById('OccMap'));
      OccChart.draw(OccData, options);
    }
	
	function drawInd() {
	  var IndData = new google.visualization.DataTable();
	  <?php
		echo "IndData.addRows($IndRowsCount);", PHP_EOL;
		echo "IndData.addColumn('string', 'Region');", PHP_EOL;
		echo "IndData.addColumn('number', 'Tier');", PHP_EOL;
		echo "IndData.addColumn('number', 'IT Sector Professionals');", PHP_EOL;
		
		$count = 0;
		$tier = 1;
		
		foreach($IndRows as $IndRow) {
			echo "IndData.setValue($count, 0, '$IndRow->stateName');", PHP_EOL;
			if($IndRow->jobs <= '10000')
			{
				$tier = 1;
			}
			elseif($IndRow->jobs <= '50000')
			{
				$tier = 2;
			}
			elseif($IndRow->jobs <= '100000')
			{
				$tier = 3;
			}
			elseif($IndRow->jobs > '100000')
			{
				$tier = 4;
			}
			
			echo "IndData.setValue($count, 1, $tier);", PHP_EOL;
			echo "IndData.setValue($count, 2, $IndRow->jobs);", PHP_EOL;
			
			$count++;
		}
		
	  ?>
      
      var options = 
        {
          region: 'US',
          resolution: 'provinces',
          //colorAxis: {colors: ['#ffffff','#99ff99','#66cc66','#339933','#006600']},
		  colorAxis: {colors: ['#66cc66','#339933','#006600']},
          tooltip: {textStyle: {color: 'black'}, showColorCode: true},
		  backgroundColor: {strokeWidth: 2, stroke: '#FFF', fill:'#FFF'},
		  backgroundColor: 'transparent',
		  datalessRegionColor: '#FFFFFF'
        };
	  
	  var IndChart = new google.visualization.GeoChart(
          document.getElementById('IndMap'));
      IndChart.draw(IndData, options);
	}
	
	google.setOnLoadCallback(drawOcc);
	google.setOnLoadCallback(drawInd);
	
  </script>
  
  <script type="text/javascript">
  
  	function toggle() {
		var element = document.getElementById("toggle");
		var indmap = document.getElementById("IndMap");
		var occmap = document.getElementById("OccMap");
		
		if (element.getAttribute("src") == "resources/toggleLeft.png")
		{
			element.setAttribute("src","resources/toggleRight.png");
			occmap.setAttribute("style","visibility:hidden;");
			indmap.setAttribute("style","visibility:visible;");
		}
		else if (element.getAttribute("src") == "resources/toggleRight.png")
		{
			element.setAttribute("src","resources/toggleLeft.png");
			indmap.setAttribute("style","visibility:hidden;");
			occmap.setAttribute("style","visibility:visible;");
		}
	};
  </script>
</head>

<body class="body">
<div class="page">
	<div class="header">
		<div class="logoarea">
			<a href="http://www.it-ology.org">
			<img src="resources/itology_logo.png" width=520px height=108px alt="IT-oLogy|Advancing IT Talent" class="logo"/>
			</a>
		</div>
		<div class="missionarea">
			IT-oLogy is a non-profit collaboration of businesses, academic institutions, and organizations dedicated to growing the IT talent pipeline and advancing the IT profession.
		</div>
    </div>
    <div class="content">
    	<div id="map" class="map">
		<p class="missionarea">Move your mouse over any state to see the latest IT employment figures at-large and for the IT industry</p>
        	<div id="OccMap" class="OccMap"></div>
            <div id="IndMap" class="IndMap"></div>
            <div id="Slider" class="Slider">
            	<p style="text-align: center">Display map showing the state totals for:</p>	
                <p>All IT Professionals<a href="javascript:toggle()" id="toggleWrapper"><img id="toggle" class="toggle" src="<?php echo $toggleImage; ?>" /></a>IT Industry Professionals</p>
            </div>
        </div>
    	<div class="description">
    		<h2 class="description">Information Technology Jobs Data</h2>
        	<p class="description">
			The IT-oLogy job study site is a collaborative project created by a cross-functional team of University of South Carolina students from Computer Science, Integrated Information Technology (iiT) and the Darla Moore School of Business.  The website provides users a visualization of the IT Employment data provided by the United States Bureau of Labor and Statistics.
            </p>
        <div  class="accessbutton">
 	      	<a href="itology/itology.html">Access the Study</a>
        </div>
	</div>
    </div>
    <div class="footer">
		<div id="helparea">
		<p valign="middle" align="right" >
			<a href="help.html" target="_blank" >Need tips on Using this report?<img src="resources/help.png" height="16" width="16" /></a>
		</p>
		</div>
    </div>
</div>
</body>
</html>
