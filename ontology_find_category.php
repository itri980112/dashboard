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
<h1 class="glyphicon glyphicon-search">找商品</h1>
</div>
<br>
<div class="panel-body">
<form action="ontology_find_category.php" method="post">
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
    <input type="submit" class="btn btn-primary">
</form>
</div>
</div>
<br>

<div class="panel panel-success">
<div class="panel-heading">
<h3 class="glyphicon glyphicon-book"> Results:</h3>
</div>
<div class="panel-body">
<?php
$myquery .= "{?about db:Product_Category_in_People db:$people .?about db:Product_Category_in_Age db:$age} limit $amount";
$myquery = urlencode($myquery);

$json = file_get_contents("http://220.132.97.119:3030/mydata/query?query=$myquery&output=json&stylesheet=");
$obj = json_decode($json,true);

   echo
            ' <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>商品類別(總數)</th>
                </tr>
                </thead><tbody>';

if(count($obj["results"]["bindings"]) == 0){
    echo "Nothing to query.";
}


for ( $i=0;$i<count($obj["results"]["bindings"]);$i++ ){
    $item = $obj["results"]["bindings"][$i]["about"]["value"];
    $item = str_replace("http://db#","",$item);
    $getId = getCategoryIdBySearch($conn,$item);
    $catid = (int)$getId[0][0];
    $getCount = getCategoryCountByCatidSearch($conn,$catid);

    echo '<tr><td>' . ($i+1) . '</td>';
    echo " <td><a href='http://220.132.97.119/dashboard/category.php?id=" . $getId[0][0] . "'>$item(" . $getCount[0][0] . ")</td></tr>";

    
}
?>
</div>
</div>


<?php
include_once("footer.php");
?>

