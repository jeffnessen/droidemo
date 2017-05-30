<?php
function compoundGrowth($percent, $startValue, $cycles) {
	$percent = $percent/100;
	$newValue = $startValue;
	for ($i = 1; $i <= $cycles; $i++) {
		$newValue = $newValue + ($newValue * $percent);
		$calcValue[$i] = $newValue;
	}
	return ($calcValue);
}

function deltaCompoundGrowth($percent, $startValue, $cycles) {
	$percent = $percent/100;
	$newValue = $startValue;
	for ($i = 1; $i <= $cycles; $i++) {
		$calcValue[$i] = $newValue * $percent;
		$newValue = $newValue + $calcValue[$i];
	}
	return ($calcValue);
}

?>
