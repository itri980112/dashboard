<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getStoreList($conn);
$source_ary = getSourceList();
/* process page */
$each_page_count = 50;
$page_max = ceil(sizeof($ary) / $each_page_count);
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
//echo 'tttteeeessssstttt';

//echo 'tttteeeessssstttt';


//var_dump($page_max);
$showpage = max(1, min($showpage, $page_max));

?>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>店家名稱</th>
                <th>店家縮圖</th>
                <th>商城</th>
                <th>商品數量</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = ($showpage-1) * $each_page_count ; $i < min($showpage * $each_page_count, sizeof($ary)) ; $i++){
                $url = $ary[$i]["url"];
                $id = $ary[$i]["id"];
                $photo = $ary[$i]["photo"];
                echo
                    "<td>" . ($i + 1) . "</td>" .
                    "<td><a href='./store.php?id=$id' >" . $ary[$i]["chinese_name"] . "</a></td>" .
                    "<td><a href='$photo' download><img src='$photo'></td></a>" .
                    "<td><a href='$url' target='_blank'>" . $source_ary[$ary[$i]["source"]] . "</a></td>" .
                    "<td>" . $ary[$i]["count"] . "</td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
	//var_dump($showpage);
        printPagination($showpage, $page_max, "./store_list.php?page=%d");
    ?>

 
<?php
include_once("footer.php");
?>

