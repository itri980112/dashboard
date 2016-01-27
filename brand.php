<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$brand_id = (int)$_GET['id'] ? (int)$_GET['id'] : 1;
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$total = getProductTotalByBrandId($conn, $brand_id);
$each_page_count = 50;
$page_max = ceil($total / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
$start = ($showpage - 1) * $each_page_count;
$ary = getProductListByBrandId($conn, $brand_id, $start, $each_page_count);
/* calc this store in which page */
$brand_ary = getBrandList($conn);
for($i = 0 ; $i < sizeof($store_ary) ; $i++)
    if($brand_ary[$i][0] == $brand_id)
        $brand_list_page = $i;
$brand_list_page = floor(($brand_list_page) / 50) + 1;
?>


<style>
.small.photo{
    height: 50px;
    width: 50px;
}
</style>
    <nav>
      <ul class="pager">
        <li class="previous"><a href="./brand_list.php?page=<?php echo $brand_list_page; ?>"><span aria-hidden="true">&larr;</span>回品牌列表</a></li>
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
        printPagination($showpage, $page_max, "./brand.php?id=$brand_id&page=%d");
    ?> 

 
<?php
include_once("footer.php");
?>

