<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");

$store_id = (int)$_GET['store_id'];
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getProductTotalByStoreId($conn, $store_id);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
$start = ($showpage - 1) * $each_page_count;
$ary = getProductListByStoreId($conn, $store_id, $start, $each_page_count);
?>

<style>
.small.photo{
    height: 50px;
    width: 50px;
}
</style>
    <nav>
      <ul class="pager">
      <li class="previous"><a href="./follow.php?store_id=<?php echo $store_id;?>"><span aria-hidden="true">&larr;</span>回商店分析</a></li>
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
        printPagination($showpage, $page_max, "./store_personal.php?store_id=" . $store_id . "&page=%d");
    ?> 

 
<?php
include_once("footer.php");
?>

