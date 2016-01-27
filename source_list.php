<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getSourceCountList($conn);
$source_ary = getSourceList();
/* process page */
?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>商城</th>
                <th>商品數量</th>
            </tr>
        </thead>
        <tbody>
        <?php
            for($i = 0 ; $i < sizeof($ary) ; $i++){
                echo "<tr>".
                    "<td>" . $ary[$i][0] . "</td>" .
                    "<td>" . $source_ary[$ary[$i][0]] . "</td>" .
                    "<td>" . $ary[$i][1] . "</td>";
            }
        ?>
        </tbody>
    </table>
 
<?php
include_once("footer.php");
?>

