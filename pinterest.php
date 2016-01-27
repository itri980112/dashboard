<?php
include_once("./header.php");
require_once "./assets/Pinterest.class.php";


if( isset( $_POST['jurl'] )&& isset( $_POST['board'] ) ){
 echo $_POST['jurl'].'   '.$_POST['board'];
 system("/var/www/html/jared/jtest.py ".$_POST['jurl']." ".$_POST['board']." &");
}





?>

<div class="col-sm-offset-2 col-sm-5">

<form method="post" action="<?php $_SERVER['PHP_SELF'];   ?>" >
信義居家產品頁面網址：<input name="jurl" type="text" class="form-control" size="100"/><br/>
Pinterest Board<input name="board" type="text" size="30"/><br/>
<!--

<select name="board" class="form-control" >
<?php
$p = new Pinterest();
$p->login("istore.itri@gmail.com", "sstc5202");
$p->get_boards();
 foreach($p->boards as $key => $val)
 { 
  echo '<option value="'.$key.'">'.$key.'</option>'."\n";
}

?>
</select>
-->
<input type="submit" value="轉檔" />
</form>
</div>

 
<?php
include_once("footer.php");
?>

