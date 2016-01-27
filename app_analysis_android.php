<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getAndroidApp($conn, $_GET['id']);
?>
 
<nav>
  <ul class="pager">
    <li class="previous"><a href="./app_analysis_android_list.php"><span aria-hidden="true">&larr;</span>回列表</a></li>
  </ul>
</nav>
<div class="row">
<?php for($i = 0 ; $i < sizeof($ary['comment']) ; $i++){ ?>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $ary['comment'][$i]['author_name'] ?>
            </div>
            <div class="panel-body">
                <?php echo $ary['comment'][$i]['comment'] ?>
            </div>
        </div>
    </div>
<?php } ?>
</div>


<?php
include_once("footer.php");
?>

