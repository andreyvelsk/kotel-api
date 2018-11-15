<?php
include 'dbconnect.php';

$interval = $_GET['interval'] ?? 3;
$sensor =  $_GET['sensor'];

$sql = "";

for ($i = 0; $i < count($sensor); $i++){
    $sql = $sql . "
    SELECT id_sensor, value, vdatetime FROM ( 
    SELECT @row := @row +1 AS rownum, id_sensor, value, vdatetime
    FROM (
    SELECT @row :=0) r, value 
    WHERE id_sensor = $sensor[$i] AND
    vdatetime BETWEEN now()-INTERVAL $interval HOUR AND now()
    ORDER BY vdatetime ASC
    ) tmp
    WHERE rownum MOD 5 = 0
    ";
    // rownum - выбираем каждую 5 строчку
    if (count($sensor) > 1 && $i != count($sensor) - 1 ){
        $sql = $sql."
        UNION
        ";
    }

}

$result = mysqli_query($conn, $sql);

$final = array();
$labels = array();
$data = array();
$dataset = array();
$dataset1 = array();

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        array_push($labels, $row['vdatetime']);
        array_push($data,  $row['value']);
    }
    $dataset1['label'] = 't_pod';
    $dataset1['data'] = $data;
    array_push($dataset, $dataset1);
    
    $final['labels'] = $labels;
    $final['datasets'] = $dataset;

	$json = json_encode($final); 

	echo $json;
} else {
    echo "[]";
}



?>