<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$source_id = (int)$_GET['source'] ? (int)$_GET['source'] : 0;
$store_id = (int)$_GET['store'] ? (int)$_GET['store'] : 0;
$now_date = date('Y/m/d');
$start_date = $_GET['start_date'] ? myurlencode($_GET['start_date']) : $now_date;
$end_date = $_GET['end_date'] ? myurlencode($_GET['end_date']) : $now_date;
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getNewProductTotal($conn, $source_id, $store_id, $start_date, $end_date);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
$start = ($showpage - 1) * $each_page_count;
$ary = getNewProductList($conn, $source_id, $store_id, $start_date, $end_date, $start, $each_page_count);
?>
<link href="./assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<style>
.small.photo{
    height: 50px;
    width: 50px;
}
</style>
<h1>新上架商品列表</h1>
<form action="" method="get" data-toggle="validator">
    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <label>起始日期</label>
                <div class='input-group date' id='since_datetime'>
                <input type='text' class="form-control" name="start_date" value="<?php echo $start_date ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="form-group">
                <label>結束日期</label>
                <div class='input-group date' id='until_datetime'>
                <input type='text' class="form-control" name="end_date" value="<?php echo $end_date ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="form-group">
                <label>商城</label>
                <select id="select_source" name="source" class="form-control">
                        <option value='0'>全部</option>
<?php
$source = getSourceList();
foreach($source as $v => $s)
{
    echo "<option value='$v'>$s</option>";
}
?>
                </select>
            </div>
        </div>
        <div class='col-md-6'>
            <div id="store" class="form-group">
                <label>店家</label>
                <select id="select_store" name="store" class="form-control">
                    <option value='0'>全部</option>
<?php        
$star = getStarStore($conn, "1");
for($i = 0 ; $i < sizeof($star) ; $i++){
    $s=$star[$i]['store_id'];
    $v=$star[$i]['chinese_name'];
    echo "<option value='$s'>$v</option>";
}
?>
                </select>
            </div>
        </div>
<script>
    store_id=<?php echo $store_id ?>;
    source_id=<?php echo $source_id ?>;
    $("#select_store option[value=" + store_id + "]").attr('selected','selected');
    $("#select_source option[value=" + source_id + "]").attr('selected','selected');
</script>
        <div class='col-md-12'>
            <button type="submit" class="btn btn-info btn-block">搜尋</button>
        </div>
            </div>
        </form>
<script type="text/javascript">
$(function() {
    $('#since_datetime').datetimepicker(
{
    sideBySide: true,
        format: 'YYYY/MM/DD',
}
);
$('#until_datetime').datetimepicker(
{
    sideBySide: true,
        format: 'YYYY/MM/DD',
}
);
$("#since_datetime").on("dp.change", function(e) {
    $('#until_datetime').data("DateTimePicker").minDate(e.date);
});
$("#until_datetime").on("dp.change", function(e) {
    $('#since_datetime').data("DateTimePicker").maxDate(e.date);
});
});
</script>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>產品名稱</th>
                <th>縮圖</th>
                <th>狀態</th>
                <th>建議售價</th>
                <th>售價</th>
                <th>外部連結</th>
            </tr>
        </thead>
        <tbody> 
<?php
for($i = 0 ; $i < sizeof($ary) ; $i++){
    $url = $ary[$i]["url"];
    $photo = $ary[$i]["photo"];
    echo
        "<tr>" .
        "<td>" . ($start + $i + 1) . "</a></td>" .
        "<td>" . $ary[$i]["title"] . "</td>" .
        "<td><a href='$photo' download><img src='$photo' class='small photo'></a></td>" .
        "<td>" . $ary[$i]["available"] . "</td>" .
        "<td>" . $ary[$i]["recommend_price"] . "</td>" .
        "<td>" . $ary[$i]["current_price"] . "</td>" .
        "<td><a href='$url' target='_blank'>前往</a></td>" .
        "</tr>";
}
?>  
        </tbody>
    </table>
<?php
printPagination($showpage, $page_max, "./new_product.php?source=$source_id&store=$store_id&start_date=$start_date&end_date=$end_date&page=%d");
?> 
<script src="./assets/js/collapse.js"></script>
<script src="./assets/js/transition.js"></script>
<script src="./assets/js/validator.js"></script>
<script src="./assets/js/moment.min.js"></script>
<script src="./assets/js/bootstrap-datepicker.js"></script>
<script src="./assets/js/bootstrap-datetimepicker.js"></script>
<?php
include_once("footer.php");
?>

