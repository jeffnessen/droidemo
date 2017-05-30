<?php

function countOps($formulaToCount) {
	return substr_count($formulaToCount, "$");
}
function getOps($formulaToParse) {
	$numOps = substr_count($formulaToParse, "$");
	for ($a = 0; $a < $numOps; $a++) {
		$positionOfFirstString = strpos($formulaToParse, "$");
		$positionOfNextSpace = strpos($formulaToParse, " ", $positionOfFirstString);
		if ($positionOfNextSpace == FALSE) $positionOfNextSpace = strlen($formulaToParse);
		$opLength = $positionOfNextSpace - $positionOfFirstString;
		$ops[$a] = substr($formulaToParse, $positionOfFirstString, $opLength);
		$mathops = array("+", "-", "*", "/", "$", "(", ")", " ");
		$ops[$a] = str_replace($mathops, "", $ops[$a]);
		$formulaToParse = substr($formulaToParse, $positionOfNextSpace);
	}		
//	$formulaToParse = trim($formulaToParse);
//	$formulaToParse = str_replace("$", "", $formulaToParse);
//	$formulaToParse = str_replace("(", "", $formulaToParse);
//	$formulaToParse = str_replace(")", "", $formulaToParse);
//	$formulaToParse = str_replace(" ", "", $formulaToParse);
//	$mathops = array("+", "-", "*", "/");
//	$formulaToParse = str_replace($mathops, "|", $formulaToParse);
//	$ops = explode("|", $formulaToParse);
	return $ops;
} 

function calculateString( $mathString )    {
    $mathString = trim($mathString);     // trim white spaces
    $mathString = ereg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
 
    $compute = create_function("", "return (" . $mathString . ");" );
    return 0 + $compute();
}
?>
