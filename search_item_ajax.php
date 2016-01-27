<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");

$word = $_GET['name'] ? $_GET['name'] : "";
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getProductTotalBySearch($conn, $word);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
$start = ($showpage - 1) * $each_page_count;
$ary = getProductListBySearch($conn, $word, $start, $each_page_count);
$result = [];
for($i = 0 ; $i < sizeof($ary) ; $i++){
    $result['items'][$i]["url"] = $ary[$i]["url"];
    $result['items'][$i]["photo"] = $ary[$i]["photo"];
    $result['items'][$i]["title"] = $ary[$i]["title"];
    $result['items'][$i]["recommend_price"] = $ary[$i]["recommend_price"];
    $result['items'][$i]["current_price"]= $ary[$i]["current_price"];
}
$result['data']['total'] = $total;
    echo json_encode($result);
?>
