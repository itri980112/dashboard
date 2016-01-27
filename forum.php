<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getbrandList($conn);
/* process page */
$showpage = (int)$_GET['page'] ? (int)$_GET['page'] : 1;
$each_page_count = 50;
$page_max = ceil(sizeof($ary) / $each_page_count);
$showpage = max(1, min($showpage, $page_max));
?>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>品牌名稱</th>
                <th>商品數量</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = ($showpage-1) * $each_page_count ; $i < min($showpage * $each_page_count, sizeof($ary)) ; $i++){
                $id = ($i+1);
                echo
                    "<td>" . ($i + 1) . "</td>" .
                    "<td><a href='./brand.php?id=$id' >" . $ary[$i][0] . "</a></td>" .
                    "<td>" . $ary[$i][1] . "</td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
        printPagination($showpage, $page_max, "./forum.php?page=%d");
    ?>

 
<?php
include_once("footer.php");
?>

