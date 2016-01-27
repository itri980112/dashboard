<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getStarStore($conn, $_SESSION['user_id']);
$storelist = getStoreList($conn);
$stores = array();
$stores_id = array();
for($i = 0 ; $i < sizeof($storelist) ; $i++){
    $stores[] = $storelist[$i]['chinese_name'];
    $stores_id[] = $storelist[$i]['id'];
}
?>
<script>
$( document ).ready(function(){
    $("#store_group").removeClass("has-error");
    stores=<?php echo json_encode($stores); ?>;
    stores_id=<?php echo json_encode($stores_id); ?>;
    $("#store").autocomplete({
        source: stores
    });
    $("#addStoreForm").submit(function(){
        store_name = $("#store").val();
        url = $("#addStoreForm").attr("action");
        idx = $.inArray(store_name, stores);
        data = {"type":"addStarStore","data":{"store_id":stores_id[idx]}};
        if(idx>=0){
            var post = $.post(url, JSON.stringify(data));
            post.done(function(ajax_data){
                $("#store_group").removeClass("has-error").addClass("has-success");
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
            $("#store_group").addClass("has-error");
        }
        return false;
    });
    $(".deleteStarStore").click(function(){
        console.log($(this).attr('storeid'));
        data = {"type":"deleteStarStore", "data":{"store_id":$(this).attr('storeid')}};
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

<form id="addStoreForm" action="ajax.php" class="form-inline">
  <div id="store_group" class="form-group">
    <label for="store">商店名稱(請使用點選的)</label>
    <input type="text" id="store" class="form-control" placeholder="商店" autocomplete="off">
    <button type="submit" id="submit" class="btn btn-primary">新增</button>
  </div>
</form>

 <table class="table table-striped">
      <thead>
        <tr>
          <th>商店</th>
          <th>刪除</th>
        </tr>
      </thead>
      <tbody>
<?php
for($i = 0 ; $i < sizeof($ary) ; $i++){
    echo '<tr>';
    echo '<th>'. $ary[$i][1] . '</th>';
    echo '<th>'. '<a storeid='. $ary[$i][0] .' type="button" class="deleteStarStore btn btn-danger">刪除</button>' . '</th>';
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
        此商品已經在清單內了
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

