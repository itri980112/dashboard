<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");

$store_id = (int)$_GET['id'] ? (int)$_GET['id'] : 1;
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getProductTotalByStoreId($conn, $store_id);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
$start = ($showpage - 1) * $each_page_count;
$ary = getProductListByStoreId($conn, $store_id, $start, $each_page_count);

/* calc this store in which page */
$store_ary = getStoreList($conn);
for($i = 0 ; $i < sizeof($store_ary) ; $i++)
    if($store_ary[$i][0] == $store_id)
        $store_list_page = $i;
$store_list_page = floor(($store_list_page) / 50) + 1;
?>


<style>
.small.photo{
    height: 50px;
    width: 50px;
}
</style>
    <nav>
      <ul class="pager">
        <li class="previous"><a href="./store_list.php?page=<?php echo $store_list_page; ?>"><span aria-hidden="true">&larr;</span>回商店列表</a></li>
      </ul>
    </nav>
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
                    "<td><img src='$photo' class='small photo'></td>" .
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
        printPagination($showpage, $page_max, "./store.php?id=$store_id&page=%d");
    ?> 

 
<?php
include_once("footer.php");
?>

