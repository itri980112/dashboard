<?php
include_once('./commonlib.php');
$conn = getConnection("ibabymall");
print_r(json_encode(getActiveWall($conn, $_GET['from'], $_GET['count'])));
?>
