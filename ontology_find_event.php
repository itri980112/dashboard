<?php
include_once("./header.php");
?>

<?php
$myquery = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>PREFIX owl: <http://www.w3.org/2002/07/owl#>PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>PREFIX db: <http://db#> SELECT ?about WHERE ";
$queryErr = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $people = $_POST["people"];
    $age = $_POST["age"];
    $amount = $_POST["amount"];
}
?>

<div class="panel panel-primary">
<div class="panel-heading">
<h1 class="glyphicon glyphicon-search">找事件</h1>
</div>
<br>
<div class="panel-body">
<form action="ontology_find_event.php" method="post">
   <select name="people" class="form-control">
        <option id="boy">Boy</option>
        <option id="girl">Girl</option>
        <option id="mom">Mom</option>
        <option id="dad">Dad</option>
    </select>
    <br>
    <h4 class="glyphicon glyphicon-user"> Age:</h4>
    <select name="age" class="form-control">
        <option id="8m">Age-1w</option>
        <option id="3m">Age-2w</option>
        <option id="8m">Age-3w</option>
        <option id="3m">Age-4w</option>
        <option id="3m">Age-5w</option>
        <option id="3m">Age-6w</option>
        <option id="3m">Age-7w</option>
        <option id="3m">Age-8w</option>
        <option id="3m">Age-9w</option>
        <option id="3m">Age-10w</option>
        <option id="3m">Age-11w</option>
        <option id="3m">Age-12w</option>
        <option id="8m">Age-13w</option>
        <option id="3m">Age-14w</option>
        <option id="8m">Age-15w</option>
        <option id="3m">Age-16w</option>
        <option id="3m">Age-17w</option>
        <option id="3m">Age-18w</option>
        <option id="3m">Age-19w</option>
        <option id="3m">Age-20w</option>
        <option id="3m">Age-21w</option>
        <option id="3m">Age-22w</option>
        <option id="3m">Age-23w</option>
        <option id="3m">Age-24w</option>
        <option id="8m">Age-25w</option>
        <option id="3m">Age-26w</option>
        <option id="8m">Age-27w</option>
        <option id="3m">Age-30w</option>
        <option id="3m">Age-31w</option>
        <option id="3m">Age-32w</option>
        <option id="3m">Age-33w</option>
        <option id="3m">Age-34w</option>
        <option id="3m">Age-35w</option>
        <option id="3m">Age-36w</option>
        <option id="3m">Age-37w</option>
        <option id="3m">Age-38w</option>
        <option id="3m">Age-39w</option>
        <option id="3m">Age-40w</option>
        <option id="3m">Age1m</option>
        <option id="3m">Age2m</option>
        <option id="3m">Age3m</option>
        <option id="3m">Age4m</option>
        <option id="3m">Age5m</option>
        <option id="3m">Age6m</option>
        <option id="3m">Age7m</option>
        <option id="3m">Age8m</option>
        <option id="3m">Age9m</option>
        <option id="3m">Age10m</option>
        <option id="3m">Age11m</option>
        <option id="3m">Age12m</option>
    </select>

    <br>

    <h4 class="glyphicon glyphicon-th-list"> Results Amount:</h4>
    <select name="amount" class="form-control">
        <option id="a10">10</option>
        <option id="a50">50</option>
    </select>

    <br><br>

    <input type="submit" class="btn btn-xlarge btn-primary">
</form>
</div>
</div>
<br>
<div class="panel panel-success">
<div class="panel-heading">
<h3 class="glyphicon glyphicon-book"> Results:</h3>
</div>
<br><br>
<div class="panel-body">
<?php
$myquery .= "{?about db:Event_has_People db:$people .?about db:Event_in_Age db:$age} limit $amount";
$myquery = urlencode($myquery);

$json = file_get_contents("http://220.132.97.119:3030/mydata/query?query=$myquery&output=json&stylesheet=");
$obj = json_decode($json,true);
if(count($obj["results"]["bindings"]) == 0){
    echo "Nothing to query.";
}

for ( $i=0;$i<count($obj["results"]["bindings"]);$i++ ){
    $item = $obj["results"]["bindings"][$i]["about"]["value"];
    $item = str_replace("http://db#","",$item);

    echo "<div class='panel panel-primary'>";
    echo "<div class='panel-heading'>" . $item . "</div>";
    $productQuery = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>PREFIX owl: <http://www.w3.org/2002/07/owl#>PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>PREFIX db: <http://db#>SELECT ?about WHERE {db:$item db:Event_has_Product_Category ?about}";
    $productQuery = urlencode($productQuery);
    $productJson = file_get_contents("http://220.132.97.119:3030/mydata/query?query=$productQuery&output=json&stylesheet=");
    $product = json_decode($productJson,true);

    echo "<div class='panel-body'>";
    echo  ' <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>商品類別(商品總數)</th>
                </tr>
            </thead>
            <tbody>';


    for ( $j=0;$j<count($product["results"]["bindings"]);$j++ ){
        $pro = $product["results"]["bindings"][$j]["about"]["value"];
        $pro = str_replace("http://db#","",$pro);
        $getId = getCategoryIdBySearch($conn,$pro);
        $catid = (int)$getId[0][0];
        $getCount = getCategoryCountByCatidSearch($conn,$catid);

        echo "<tr><td>" . ($j+1) . "</td>";
        echo "<td><a href='http://220.132.97.119/dashboard/category.php?id=" . $getId[0][0] . "'>$pro(" . $getCount[0][0] . ")</td></tr>";
 
    }   
    
    echo "</tbody></table>";
    if(count($product["results"]["bindings"]) == 0){
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "No product.";
    }
    echo "</div></div>";

}
?>
</div>

<?php
include_once("footer.php");
?>

