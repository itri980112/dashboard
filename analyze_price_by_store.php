<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$price_data = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];
$ary = getPriceByStore($conn, $price_data,$_GET["store_id"]);
$price_data_sz = sizeof($price_data);
for($i = 1 ; $i < $price_data_sz ; $i++)
    $range["$i"] = $price_data[$i-1] . "~" . ($price_data[$i]-1);
$range["$price_data_sz"] = (string)($price_data[$price_data_sz-1]);
for($i = 0 ; $i < sizeof($ary) ; $i++){
    foreach($range as $key)
        $store[$key] = $ary[$i][$key];
    $data = $store;
}
$result["range"] = $range;
$result["data"] = $data;

echo json_encode($result);


?>
