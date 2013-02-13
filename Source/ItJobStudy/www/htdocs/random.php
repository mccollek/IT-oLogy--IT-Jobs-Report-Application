<?php
$pk = $_GET["pk"];
$connect = mysql_connect("localhost", "root", "connect2it");
  if (!$connect) {
    die("<div class=\"error\">" . mysql_error() . "</div>");
  }
  mysql_select_db("itology", $connect);
  $result = mysql_query("SELECT year FROM `itology`.`occStats` WHERE `pk_occ`='$pk';", $connect);
  $rows = mysql_fetch_assoc($result);
  echo $rows['year'];
  ?>