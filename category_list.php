<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getCategoryList($conn);
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
                <th>類別名稱</th>
                <th>商品數量</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = ($showpage-1) * $each_page_count ; $i < min($showpage * $each_page_count, sizeof($ary)) ; $i++){
                $id = $ary[$i][0];
                echo
                    "<td>" . ($i + 1) . "</td>" .
                    "<td><a href='./category.php?id=$id' >" . $ary[$i][1] . "</a></td>" .
                    "<td>" . $ary[$i][2] . "</td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
        printPagination($showpage, $page_max, "./category_list.php?page=%d");
    ?>

 
<?php
include_once("footer.php");
?>

