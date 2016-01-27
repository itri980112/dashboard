<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getStarBrand($conn, $_SESSION['user_id']);
$aryBrand = getAllBrandList($conn);
$brands = [];
$brands_id = [];
for($i = 0 ; $i < sizeof($aryBrand) ; $i++){
    $brands[$i] = $aryBrand[$i][1];
    $brands_id[$i] = $aryBrand[$i][0];
};
?>

<script type="text/javascript">
$( document ).ready(function(){
    $("#brand_group").removeClass("has-error")
        brands=<?php echo json_encode($brands) ?>;
    brands_id=<?php echo json_encode($brands_id) ?>;
    $("#brand").autocomplete({
        source: brands
    });
    $("#addBrandForm").submit(function(){
//        event.preventDefault();
        brand_name = $("#brand").val();
        url = $("#addBrandForm").attr("action");
        idx = $.inArray(brand_name, brands);
        data = {"type":"addStarBrand","data":{"bid":brands_id[idx]} };
        if(idx>=0){
            var post = $.post(url, JSON.stringify(data));
            post.done(function(ajax_data){
                $("#brand_group").removeClass("has-error").addClass("has-success");
                console.log(ajax_data);
                obj = JSON.parse(ajax_data);
                if(obj['status']=="OK"){
                    console.log("OK");
                    location.reload();
                }
                else{
                    if(obj['data']['code']==1){
                        console.log("already exist!");
                        $('#existedModal').modal();
                    }
                }
            });
        }
        else{
            $("#brand_group").addClass("has-error");
        }
        return false;
    });
    $(".deleteStarBrand").click(function(){
        console.log($(this).attr('brandid'));
        data = {"type":"deleteStarBrand", "data":{"bid":$(this).attr('brandid')}};
        url = "/dashboard/ajax.php";
        var post = $.post(url, JSON.stringify(data));
        post.done(function(ajax_data){
            console.log(ajax_data);
            obj = JSON.parse(ajax_data);
            if(obj['status']=="OK"){
                console.log("OK");
                location.reload();
            }
            else{
                $('#deleteModal').modal();
                console.log("error");
            }
        });

    });
});


</script>
<script>

</script>
<form id="addBrandForm" action="ajax.php" class="form-inline">
  <div id="brand_group" class="form-group">
    <label for="brand">品牌名稱(請使用點選的)</label>
    <input type="text" id="brand" class="form-control" placeholder="品牌" autocomplete="off">
    <button type="submit"  id="submit" class="btn btn-primary">新增</button>
  </div>
</form>

 <table class="table table-striped">
      <thead>
        <tr>
          <th>品牌</th>
          <th>刪除</th>
        </tr>
      </thead>
      <tbody>
<?php
for($i = 0 ; $i < sizeof($ary) ; $i++){
    echo '<tr>';
    echo '<th>'. $ary[$i][1] . '</th>';
    echo '<th>'. '<a brandid='. $ary[$i][0] .' type="button" class="deleteStarBrand btn btn-danger">刪除</button>' . '</th>';
    echo '</tr>';
}
?>
      </tbody>
</table>

<div class="modal fade" id="existedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">錯誤</h4>
      </div>
      <div class="modal-body">
        此品牌已經在清單內了
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">錯誤</h4>
      </div>
      <div class="modal-body">
        此刪除無法完成
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
      </div>
    </div>
  </div>
</div>

<?php
include_once("footer.php");
?>

