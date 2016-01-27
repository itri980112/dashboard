<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$price_data = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];
$ary = getPriceByStarStoreV1_the_product_of_price_and_numofproducts($conn, $price_data);
for($i = 0 ; $i < sizeof($ary) ; $i++)
    $title[$ary[$i][0]] = $ary[$i][1];
$price_data_sz = sizeof($price_data);
for($i = 1 ; $i < $price_data_sz ; $i++)
    $range["$i"] = $price_data[$i-1] . "~" . ($price_data[$i]-1);
$range["$price_data_sz"] = ($price_data[$price_data_sz-1]);
for($i = 0 ; $i < sizeof($ary) ; $i++){
    foreach($range as $key)
        $store[$key] = $ary[$i][$key];
    $data[$ary[$i][0]] = $store;
}
$result["title"] = $title;
$result["range"] = $range;
$result["data"] = $data;
$result["total"] = sizeof($ary);
echo json_encode($result);
?>
