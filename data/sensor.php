<?php
header('Content-type: application/json');

// FAKE DATA

$arr = array();
//$arr["begin"] = "Sensor";

for($h = 0; $h<24; $h++){

	$hh = str_pad($h,2,'0', STR_PAD_LEFT);


	$arr[$hh] = array();

	for($room = 1; $room <=8; $room ++){
		$value = rand(5,100)/100;
		$roomname = "room-".$room;
		//$arr[$hh][$roomname] = $value;
		$arr[$hh][] = array("label"=>$roomname, "value"=>$value);
	}

}

echo json_encode($arr);

?>