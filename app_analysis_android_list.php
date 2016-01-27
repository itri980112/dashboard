<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("ibabymall");
$ary = getAndroidAppList($conn);
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>名稱</th>
            <th>連結</th>
            <th>評價</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i = 0 ; $i < sizeof($ary) ; $i++){
                $id = $ary[$i]['id'];
                $url = $ary[$i]['url'];
                $name = $ary[$i]['name'];
                $star = $ary[$i]['star'];
                echo "<tr>"
                    . "<td><a href='./app_analysis_android.php?id=$id'>$name</a></td>"
                    . "<td><a href='$url'>前往</a></td>"
                    . "<td>$star</td>"
                    . "</tr>";
            }
        ?>
    </tbody>
</table>
 
<?php
include_once("footer.php");
?>

