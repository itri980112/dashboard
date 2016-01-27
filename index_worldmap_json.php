<?php
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getCountryData($conn, $country_data);
echo json_encode($ary);
?>
