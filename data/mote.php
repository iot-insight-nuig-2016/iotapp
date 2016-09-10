<?php

// BY IHAB

header("Access-Control-Allow-Origin: *");

/// input values
$sensor_type = $_REQUEST["sensor_type"];
$day = $_REQUEST["day"];


//// GET all sensors information
$path = "http://vmvital03.deri.ie/living_lab/motes";

$get_motes = curl_init();
curl_setopt_array($get_motes, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $path
));
$motes_result = curl_exec($get_motes);
$motes_data = json_decode($motes_result);
$motes = array();
foreach($motes_data as $key => $mote)
{
	$motes[$mote->mote_id] = $mote->location ;
}

//// GET all sensors information
$path = "http://vmvital03.deri.ie/living_lab/sensors";

$get_sensors = curl_init();
curl_setopt_array($get_sensors, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $path
));
$sensors_result = curl_exec($get_sensors);
$sensors_data = json_decode($sensors_result);
$sensor_list = array();
foreach($sensors_data as $key => $sensor)
{
	if ($sensor->sensor_type == $sensor_type)
		$sensor_list[] = $sensor;
}
//var_dump($sensor_list);
$output_tmp = array();
foreach ($sensor_list as $key => $sensor){
	$path = "http://vmvital03.deri.ie/living_lab/sensor_data/". $sensor->sensor_id . "/" . $day . " 00:00:00/" . $day . " 23:59:59";
	
	$get_data = curl_init();
	curl_setopt_array($get_data, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $path
	));
	$data_result = curl_exec($get_data);
	$sensors_data = json_decode($data_result);
	
	//// GROUP INFORMATION BY HOUR
	$HH = 0;
	$vals = array();
	$vals_counter = array();

	foreach($sensors_data as $data) { 
		$val = $data->observation;
		$date = strtotime($data->date_time);
		$hh = date('H',$date);
		$vals[$hh] = $vals[$hh] + $val;
		$vals_counter[$hh]++;
	}
	
	$sum_group = array("Magnetic","Pir");
	if (!in_array ($sensor_type, $sum_group))
		foreach($vals as $key => $val)
			$vals[$key] = $val/$vals_counter[$key];
		
	$output_tmp[] = array(
		"mote_id" => $data->mote_id,
		"Values" => $vals
	);
}

$output = array(); /*array(
	"00" => array(),
	"01" => array(),
	"02" => array(),
	"03" => array(),
	"04" => array(),
	"05" => array(),
	"06" => array(),
	"07" => array(),
	"08" => array(),
	"09" => array(),
	"10" => array(),
	"11" => array(),
	"12" => array(),
	"13" => array(),
	"14" => array(),
	"15" => array(),
	"16" => array(),
	"17" => array(),
	"18" => array(),
	"19" => array(),
	"20" => array(),
	"21" => array(),
	"22" => array(),
	"23" => array()
);*/

foreach($output_tmp as $vals)
{
	
	foreach($vals["Values"] as $key => $val)
	{
		//echo "$key => $val <br/>";
		$output[$key][] = array(
							"label" => $motes[$vals["mote_id"]],
							"value" => round ($val, 2));
	}
}

//var_dump($output);
header('Content-type: application/json');
echo json_encode($output);