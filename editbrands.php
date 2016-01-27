<?php
include_once("./header.php");
include_once('./commonlib.php');
$conn=getConnection('ibabymall');



function getProductTitles($conn){
    $sql='select title from product p where p.id not in (select pid from map_prod_brand bm) order by rand() limit 100';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
    }
    mysql_free_result($result);
    return $ary;
}


function addRecord($conn,$bname){
    $sql='insert into brands(brand) values("'.$bname.'")  ';
    mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
}

$prods=getProductTitles($conn);


?>

<div class="col-sm-5">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="info">
                                <th>產品名稱</th>                                
                            </tr>
                        </thead>	
                        <tbody>	
                        <?php	
                            $storesize=sizeof($prods);
                            for($i=0;$i<$storesize;$i++){
                                echo '<tr class="success">';
                                echo "<td> ".$prods[$i]." </td>";
                               echo '</tr>';
                            }
                        ?>
                        </tbody>
                    </table>


</div>

<?php

if( isset( $_POST['b1'] ) ){
 echo "新增品牌: ".$_POST['b1'];
 addRecord($conn,$_POST['b1']);
}
if( isset( $_POST['b2'] ) ){
 echo "新增品牌: ".$_POST['b2'];
 addRecord($conn,$_POST['b2']);
}
if( isset( $_POST['b3'] ) ){
 echo "新增品牌: ".$_POST['b3'];
 addRecord($conn,$_POST['b3']);
}
if( isset( $_POST['b4'] ) ){
 echo "新增品牌: ".$_POST['b4'];
 addRecord($conn,$_POST['b4']);
}
if( isset( $_POST['b5'] ) ){
 echo "新增品牌: ".$_POST['b5'];
 addRecord($conn,$_POST['b5']);
}

?>  

<form method="post" action="<?php $_SERVER['PHP_SELF'];   ?>" >
<input name="b1" type="text" size="30"/><br/>
<input name="b2" type="text" size="30"/><br/>
<input name="b3" type="text" size="30"/><br/>
<input name="b4" type="text" size="30"/><br/>
<input name="b5" type="text" size="30"/><br/>
<input type="submit" value="submit"/>
</form>





 
<?php
include_once("footer.php");
?>

