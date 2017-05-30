<?php


function getPowerByState($cState) {
	$SERVER = "cloud2.internal.progentus.com";
	$USER = "soar";
	$PASSWORD = "S0ar!";
	$DATABASE = "soar";

	$dblink = mysql_connect($SERVER, $USER, $PASSWORD) or die("Unable to connect to database server ".mysql_error());
	$testval = mysql_selectdb($DATABASE, $dblink) or die("Unable to connect to database ".mysql_error());
	
	$query = "SELECT Cost FROM PowerCostByState WHERE State='$cState'";
	$result = mysql_query($query, $dblink) or die("Unable to get power cost information ".mysql_error());
	$row = mysql_fetch_assoc($result);
	mysql_close($dblink);
	return ($row["Cost"]);
}

function calculatePowerConsumption($nUnits, $devicePower) {
	$unitConsumption = (($devicePower * 24 * 365) / 1000);
	$annualTotal = ($unitConsumption * $nUnits);
	return ($annualTotal);
}

?>

