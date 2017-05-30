<?php
function npv($rate, $values) { 
    for ($i=0;$i<=count($values);$i+=1) { 
        $npv = $values[count($values) - $i] + $npv / (1 + $rate); 
    } 
    return $npv; 
} 

?>
