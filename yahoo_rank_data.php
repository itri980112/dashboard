<?php
$id = $_GET["id"];
$d = $_GET["d"];
$url = "http://tw.mall.yahoo.com/ly_service/list/mid_leaf_item?catid=$id&img_only=1&depth=$d&hits=100&hasmore=1&count=0";
$context = stream_context_create(array('http' => array('timeout' => 1)));
$json = file_get_contents($url,0,$context);
echo($json);
?>
