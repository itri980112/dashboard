<?php
include_once("./header.php");
include_once('./commonlib.php');
$conn=getConnection('ibabymall');
$ary=getBrandCounts($conn);
$ary3=getBrandKeywordCounts($conn);
$ary2=getKeywordCounts($conn);
$prodCNT=getTotalProducts($conn);
?>

<link href="./assets/c3.css" rel="stylesheet" type="text/css">
<script src="./assets/d3.min.js" charset="utf-8"></script>
<script src="./assets/c3.min.js"></script>

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="info">
                        <th>品牌</th>
                        <th>類型</th>
                        <th>數量</th>
                    </tr>
                </thead>
                <tbody>
<?php	
for($i=0;$i<sizeof($ary3);$i+=3){
    echo '<tr class="success">';
    echo "<td> ".$ary3[$i]." </td><td>". $ary3[$i+1]   ."</td><td>". $ary3[$i+2]."</td> \n";
    echo   '</tr>';
}
?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <div id="chart"></div>
            <div id="chart2"></div>
        </div>
        <div class="col-sm-2"></div>
    </div>

<!-- REQUIRED SCRIPTS FILES -->
<!-- CORE JQUERY FILE -->
<script src="assets/js/jquery-1.11.1.js"></script>
<!-- REQUIRED BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<script>
var chart = c3.generate({
    bindto: '#chart',
        data: {
            // iris data from R
            columns: [
<?php	
for($i=0;$i<sizeof($ary);$i+=2){
    if($i>0)
        echo ","; 
    echo "['".$ary[$i]."', ". $ary[$i+1]   ." ] \n";

}
?>
],
    type : 'pie',
    onclick: function (d, i) { console.log("onclick", d, i); },
    onmouseover: function (d, i) { console.log("onmouseover", d, i); },
    onmouseout: function (d, i) { console.log("onmouseout", d, i); }
        }
});

var chart2 = c3.generate({
    bindto: '#chart2',
        data: {
            columns: [
<?php
for($i=0;$i<sizeof($ary2);$i+=2){
    if($i>0)
        echo ",";
    echo "['".$ary2[$i]."', ". $ary2[$i+1]   ." ] \n";
}
?>
],
    type : 'pie',
    onclick: function (d, i) { console.log("onclick", d, i); },
    onmouseover: function (d, i) { console.log("onmouseover", d, i); },
    onmouseout: function (d, i) { console.log("onmouseout", d, i); }
        }
});
</script>

<?php
include_once("footer.php");
?>

