<?php
/** 
  * MySQL database connectscript 
  * $ mysql_connect password variable must be changed prior to deployment
  */
$pk = $_GET["pk"];
$connect = mysql_connect("localhost", "root", "");
  if (!$connect) {
    die("<div class=\"error\">" . mysql_error() . "</div>");
  }
  mysql_select_db("itology", $connect);
  $result = mysql_query("SELECT year FROM `itology`.`occStats` WHERE `pk_occ`='$pk';", $connect);
  $rows = mysql_fetch_assoc($result);
  echo $rows['year'];
  ?>