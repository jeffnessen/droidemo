<?php

$foo = "";
foreach ($_POST as $key => $value) {
	$foo .= $key.": ".$value."\n";
	} 

$bla = json_encode($foo);

echo $bla;


?>
