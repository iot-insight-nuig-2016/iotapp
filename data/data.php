<?php
header('Content-type: text/plain');


$jsonurl = "http://vmvital03.deri.ie/living_lab/sensor_data/35/2016-09-07+00:00:00/2016-09-07+23:59:00";
$jsondata = file_get_contents($jsonurl);

$arr = (array)json_decode($jsondata);

//print_r($arr);
//echo "<hr/>";


echo "id,order,score,weight,color,label\r\n";
$i=0;

$arr = array_reverse($arr);

$HH = 0;
$vals = array();

foreach($arr as $item) { 
	
    $val = $item->observation;
    $date = strtotime($item->date_time);

    $hh = date('H',$date);

    if($HH!=$hh){
    	$i++;
    	$avg_val = array_sum($vals) / count($vals);

    	$red = round($avg_val*255/50);
    	$color = "#".dechex($red)."0000";

    	echo "$i,$i,$avg_val,1,$color,$HH h\r\n";

    	$vals = array();
    	$HH = $hh;
    }

    $vals[] = $val;
    
}

$i++;
$avg_val = array_sum($vals) / count($vals);

$red = round($avg_val*255/50);
$color = "#".dechex($red)."0000";

echo "$i,$i,$avg_val,1,$color,$HH\r\n";


?>