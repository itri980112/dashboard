<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");

$word = $_GET['name'] ? $_GET['name'] : "";
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getProductTotalBySearch($conn, $word);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);

?>

<link rel="stylesheet" href="http://css-spinners.com/css/spinner/dots.css" type="text/css">
<script>
function createTable(data){
    table = "";
    for(var item in data['items']){
        i = data['items'][item];
        var td="\n<tr>";
        td+="\n    <td>" + (parseInt(item)+1) + "</td>";
        td+="\n    <td>" + i['title'] + "</td>";
        td+="\n    <td><a href=\""+ i['photo'] + "\" download=''> <img src=\"" + i['photo'] + "\" class='small photo'></a></td>";
        td+="\n    <td>" + i['recommend_price'] + "</td>";
        td+="\n    <td>" + i['current_price'] + "</td>";
        td+= "\n    <td><a href='"+i['url'] +"' target='_blank'>前往</a></td>";
        td+="\n</tr>";
        table+=td;
    }
    console.log(table);
    $("#tbody").append(table);
    $("#totalItem").append(data['data']['total']);
    $("#loading").hide();
}


$.getJSON( "./search_item_ajax.php?name="+ "<?php echo $word?>" +"&page="+ "<?php echo $showpage?>", function( data ) {
    createTable(data);
    });
</script>
<style>
.small.photo{
    height: 50px;
    width: 50px;
}
</style>
    <nav>
      <ul class="pager">
        <li class="previous"><a href=""><span aria-hidden="true">&larr;</span>回首頁</a></li>
      </ul>
    </nav>
    <div class="alert alert-success" role="alert">搜尋標題或副標題含有 "<?php echo $word?>" 的結果，共有 <span id="totalItem"></span> 項搜尋結果！</div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>產品名稱</th>
                <th>縮圖</th>
                <th>建議售價</th>
                <th>售價</th>
                <th>外部連結</th>
            </tr>
        </thead>
        <tbody id="tbody">
        <div id="loading" style="position: fixed;top: 50%;left: 55%;"  class="text-center dots-loader">
        Loading…
    </div>

        
        </tbody>
    </table>
    <?php
        printPagination($showpage, $page_max, "./search_item.php?name=$word&page=%d");
    ?> 

 
<?php
include_once("footer.php");
?>

