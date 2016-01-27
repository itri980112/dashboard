<?php
include_once("./header.php");
include_once("./commonlib.php");
?>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $conn = getConnection("ibabymall");
    $array = GetMaxResId($conn);
    $initId = (int)$array[0][0]+1;

    for($i = $initId; $i<$initId+5;$i++){
        $res_id = (int)$i;
        $ontology = (int)$_POST["$i"];

        
        try{
            insertMapResponseOntology($conn,$res_id,$ontology);
        }
        catch (Exception $e) {
            updateMapResponseOntology($conn,$res_id,$ontology);
        }
        


        insertMapResponseOntology($conn,$res_id,$ontology);

    }
        
}
 
?>



<?php

$conn = getConnection("ibabymall");
$array = GetMaxResId($conn);
$initId = (int)$array[0][0]+1;
$ary = getResponsesList($conn,$initId);

?>

<div class="container">
<h1 style = "color:red">工人智慧<3</h1>
<br><br>
<?php
echo "<h4>從res_id: " .  $initId . "開始</h4>";
?>
<br><br>
<form action="map_response_ontology.php" method="post">    
<table class="table table-striped table-bordered table-hover" style = "width:100%">
        <thead>
            <tr>
                <th>res_id</th>
                <th>response</th>
                <th>ontology</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            for($i = 0; $i < sizeof($ary); $i++){
                $res_id = (int)$ary[$i][0];
                $response = $ary[$i][5];
                echo
                    "<tr><td style = 'width:10%'>" . $res_id . "</td>" .
                    "<td style = 'width:70%'>".$response . "</td>" .
                    "<td style = 'width:20%'>
                        
                        <label class='radio-inline'>
                            <input type='radio' name='" . $res_id . "'value = '0' required>中立
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='" . $res_id . "' value = '1' required>正評
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='" . $res_id . "' value = '2' required>負評
                        </label></td>" .
                    "</tr>";
            }
        ?>  
        </tbody>
    </table>
    <input type="submit" class="btn btn-xlarge btn-primary ">
  
</form>

    <?php
        if(sizeof($ary) == 0){
            echo"Nothing to query.";
        }
    ?>


</div>
<?php
include_once("./footer.php");
?>
