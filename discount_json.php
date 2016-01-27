<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$price_data = [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1];
$discount_ch = ["低於一折", "二折", "三折", "四折", "五折", "六折", "七折", "八折", "九折"];
$ary = getDiscount($conn, $price_data);
for($i = 0 ; $i < sizeof($ary) ; $i++)
    $title[$ary[$i][0]] = $ary[$i][1];
for($i = 0 ; $i < sizeof($ary) ; $i++){
    for($j = 0 ; $j < 9 ; $j++){
        $discount[$j] = $ary[$i][$j+4];
    }
    $shop_data["total_product"] = $ary[$i][3];
    $shop_data["discount_product"] = $ary[$i][2];
    $shop_data["discount"] = $discount;
    $data[$ary[$i][1]] = $shop_data;
};
$result["title"] = $title;
$result["data"] = $data;
$result["discount_ch"] = $discount_ch;
$result["star"] = getStarBrand($conn, "1");
$result["total"] = sizeof($ary);
echo json_encode($result);
?>
