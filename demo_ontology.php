<?php
include_once("./header.php");
include_once("./commonlib.php");
?>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $people = $_POST["people"];
    $age = $_POST["age"];
    $conn = getConnection("ibabymall");
    $ary = getProductListBySearch2($conn,$people,$age,0,50);
    $ary1 = getProductListBySearch($conn,$people,0,10);
    $ary2 = getProductListBySearch($conn,$age,0,10); 
    $count1 = getProductCountBySearch($conn, $people);
    $count2 = getProductCountBySearch($conn, $age);  
}
else{
    $ary = array();
}

$myquery = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>PREFIX owl: <http://www.w3.org/2002/07/owl#>PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>PREFIX db: <http://db#> SELECT ?about WHERE ";
$queryErr = "";

?>

<div class="container">
    <blockquote>
        <h1>Demo Page</h1>
        <footer>Show the difference between our powerful Ontology and original query.</footer>
    </blockquote>
    <br>

    <div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="glyphicon glyphicon-search">找商品</h1>
    </div>
    <br>
    <div class="panel-body">
    <form action="demo_ontology.php" method="post">
        <select name="people" class="form-control">
            <option>男嬰</option>
            <option>媽媽</option>
        </select>
        <br>
        <h4 class="glyphicon glyphicon-user"> Age:</h4>
        <select name ="age" class="form-control">
            <option >三個月</option>
            <option >四個月</option>
        </select>
    <br><br>
    <input type="submit" class="btn btn-xlarge btn-primary">
    </form>
    </div>
    </div>
    <br><br><br>
    <div class="row">
        <div class="col-xs-6">
    
    <div class="panel panel-success">
    <div class="panel-heading">
    <h1>Original Query</h1>
    <p class="lead">搜尋結果：  
        <?php
            echo sizeof($ary);
        ?>
     項</p>
    </div>
    <div class="panel-body">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>商品編號</th>
                <th>商品名稱</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = 0; $i < sizeof($ary); $i++){
                $id = $ary[$i][0];
                echo
                    "<tr><td>" . ($i + 1) . "</td>" .
                    "<td>".$id . "</td>" .
                    "<td><a href='" . $ary[$i][5] . "' >" . $ary[$i][2] . "</a></td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
        if(sizeof($ary) == 0){
            echo"Nothing to query.";
        }
    ?>
    </div>
    </div>
    <br><br><br><br>

    <div class="panel panel-success">
    <div class="panel-heading">
    <h2> Search for only
    <?php
        echo $people;
    ?>
    </h2>

    <p class="lead">搜尋結果：  
        <?php
            echo $count1[0][0];
        ?>
     項</p>
    </div>
    <div class="panel-body">  
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>商品編號</th>
                <th>商品名稱</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = 0; $i < 10; $i++){
                $id = $ary1[$i][0];
                echo
                    "<tr><td>" . ($i + 1) . "</td>" .
                    "<td>".$id . "</td>" .
                    "<td><a href='" . $ary1[$i][5] . "' >" . $ary1[$i][2] . "</a></td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
        if(sizeof($ary1) == 0){
            echo"Nothing to query.";
        }
    ?>
    
    </div>
    </div>
    <br><br><br><br>

    <div class="panel panel-success">
    <div class="panel-heading">
    <h2> Search for only
    <?php
        echo $age;
    ?>
    </h2>    
    <p class="lead">搜尋結果：  
        <?php
            echo $count2[0][0];
        ?>
     項</p>
    </div>
    <div class="panel-body">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>商品編號</th>
                <th>商品名稱</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = 0; $i < 10; $i++){
                $id = $ary2[$i][0];
                echo
                    "<tr><td>" . ($i + 1) . "</td>" .
                    "<td>".$id . "</td>" .
                    "<td><a href='" . $ary2[$i][5] . "' >" . $ary2[$i][2] . "</a></td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <?php
        if(sizeof($ary2) == 0){
            echo"Nothing to query.";
        }
    ?>
    </div>
    </div>

        </div>

        <div class="col-xs-6">

        <div class="panel panel-warning">
        <div class="panel-heading">
        <h1 style="color:brown">Ontology</h1>

    <?php
        if($people == "男嬰"){
            $people = "Boy";
        }

        if($people == "媽媽"){
            $people = "Mom";
        }

        if($age == "三個月"){
            $age = "Age3m";
        }

        if($age == "四個月"){
            $age = "Age4m";
        }   

        $myquery .= "{?about db:Product_Category_in_People db:$people .?about db:Product_Category_in_Age db:$age} limit 100";
        $myquery = urlencode($myquery);

        $json = file_get_contents("http://220.132.97.119:3030/mydata/query?query=$myquery&output=json&stylesheet=");
        $obj = json_decode($json,true);

        $count = 0; 
        echo '<p class="lead">搜尋結果: '; 
        echo (int)count($obj["results"]["bindings"]);
        echo "項類別</p>";
        echo "
        </div>
        <div class='panel-body'>
        ";
            echo
            ' <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>商品類別(商品總數)</th>
                </tr>
            </thead>
            <tbody>';
        
        for ( $i=0;$i<count($obj["results"]["bindings"]);$i++ ){
            $item = $obj["results"]["bindings"][$i]["about"]["value"];
            $item = str_replace("http://db#","",$item);
            $getId = getCategoryIdBySearch($conn,$item);
            $catid = (int)$getId[0][0];
            $getCount = getCategoryCountByCatidSearch($conn,$catid);
            $count += (int)$getCount[0][0];
            echo
                "<tr>
                    <td>" . ($i+1) . "</td>
                    <td><a href='http://220.132.97.119/dashboard/category.php?id=" . $getId[0][0] . "'>$item(" . $getCount[0][0] . ")</td></tr>";
        }

        echo"</table>";

        if(count($obj["results"]["bindings"]) == 0){
            echo "Nothing to query.";
        }
        echo "<p class='lead' style='color:brown'>商品總數：$count 項</p>";
    ?>
    </div>
    </div>
        </div>
    </div>
</div>
<?php
include_once("./footer.php");
?>
