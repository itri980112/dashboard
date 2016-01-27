<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$price_data = [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1];
$discount_ch = ["低於一折", "二折", "三折", "四折", "五折", "六折", "七折", "八折", "九折"];
$ary = getDiscountByStore($conn, $price_data,$_GET["store_id"]);
for($i = 0 ; $i < sizeof($ary) ; $i++){
    for($j = 0 ; $j < 9 ; $j++){
        $discount[$j] = $ary[$i][$j+3];
    }
    $shop_data["total_product"] = $ary[$i][1];
    $shop_data["normal"] = $ary[$i][$j+3];
    $shop_data["discount_product"] = $ary[$i][0];
    $shop_data["discount"] = $discount;
};
$result["data"] = $shop_data;
$result["discount_ch"] = $discount_ch;
echo json_encode($result);
?>
