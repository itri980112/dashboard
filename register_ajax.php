<?php
require_once('./commonlib.php');
$conn=getConnection('ibabymall');
add_user($conn, $_POST['account'], $_POST['password']);
?>
