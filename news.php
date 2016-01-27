<?php
include_once("./header.php");
include_once('./commonlib.php');
function getNewsList($conn,$key,$ary){
    $sql="select title,src,ndate,url from newsarticles where  title LIKE '%".$key."%' order by ndate desc";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    //$ary=array();
	//echo $sql;
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];
        $ary[]=$row[2];
        $ary[]=$row[3];
    }
    mysql_free_result($result);
    return $ary;
}
function getNewsStatistics_V1( $year_str,$month_str,$conn,$kw){
    // $yest_str=date("Y/m/d", strtotime("-1 day"));//昨天日期
    $sql="select YEAR(n.ndate), MONTH(n.ndate), DAY(n.ndate),count(*) from   newsarticles n where n.title LIKE '%".$kw."%'  and YEAR(n.ndate)>=".$year_str."   and MONTH(n.ndate)>=".$month_str."   group by YEAR(n.ndate),MONTH(n.ndate),DAY(n.ndate) order by YEAR(n.ndate) asc,MONTH(n.ndate) asc,DAY(n.ndate) asc";
    //echo $sql;
	$result = mysql_query($sql) or die('MySQL query error');
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];
        $ary[]=$row[2]; 
		$ary[]=$row[3]; 
    }
    mysql_free_result($result);
    return $ary;
}

function getNewsDateList($conn){
    $sql="select YEAR(n.ndate), MONTH(n.ndate), DAY(n.ndate) from newsarticles n group by YEAR(n.ndate),MONTH(n.ndate),DAY(n.ndate) order by YEAR(n.ndate) asc ,MONTH(n.ndate) asc,DAY(n.ndate) asc";
    $result = mysql_query($sql) or die('MySQL query error');
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];
		$ary[]=$row[2];
    }
    mysql_free_result($result);
	//var_dump($ary);
    return $ary;
}
if (empty($_POST["MyKeywordLList"])==1  ){ 
	echo '尚未指定關鍵字，關鍵字格式:keyword1,keyword2,keyword3...';
}else{
	$three_month_before_str=date("Y-m-d", strtotime("-3 month")); 
    $year_str=substr($three_month_before_str,0,4);
	$month_str=substr($three_month_before_str,5,2);
	$temp_key=$_POST["MyKeywordLList"];
	//echo 'temp_key:'.$temp_key;
	$keyword_array=explode (",",$temp_key);
	$conn=getConnection('news_db');
	$data_list=array();
	for($i=0;$i<sizeof($keyword_array);$i++){
		$data_list[$i] = getNewsStatistics_V1($year_str,$month_str,$conn,$keyword_array[$i]);;
	}
	$ary=array();
	for($i=0;$i<sizeof($keyword_array);$i++){
		
		$ary=getNewsList($conn,$keyword_array[$i],$ary);
		//var_dump($ary);
	}
	$newslist=$ary;
	
	//$newslist=getNewsList($conn);
}
?>

<form action="./news.php" method="post" id="mform" class="form-horizontal">
    <div class="row">
		<div class='col-md-12'>
            <div class="form-group">
                <label>請指定關鍵字</label>
                <div class='input-group date' id='my_keyword'>
                <input type='text' class="form-control"  id="MyKeyword" name="MyKeywordLList" value=""/>
 
                </div>
            </div>
        </div>
		<div class='col-md-12'>
             
		    <button type="submit" class="btn btn-primary">分析</button>
			<br>
			 
        </div>
    </div>
</form>
<link href="./assets/c3.css" rel="stylesheet" type="text/css">
<script src="./assets/d3.min.js" charset="utf-8"></script>
<script src="./assets/c3.min.js"></script>
<script src="./assets/js/jQDateRangeSlider.js"></script>
<script>

</script>
    <div id="chart"></div>

	<div class="row">
		<div id="slider"></div>    
	</div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="info">
                        <th>標題</th>
                        <th>媒體</th>
                        <th>日期</th>
                    </tr>
                </thead>
                <tbody id="table_list">
<?php
if (empty($_POST["MyKeywordLList"])==1  ){
}else{
	for($i=0;$i<sizeof($newslist);$i+=4){
		echo '<tr date="'.$newslist[$i+2].'" class="news_item success">';
		echo "<td > <a href='".$newslist[$i+3]."'>".$newslist[$i]."</a></td><td>".$newslist[$i+1]."</td><td>".$newslist[$i+2]."</td> \n";
		echo '</tr>';
	}
}

?>
                </tbody>
            </table>


<?php
if (empty($_POST["MyKeywordLList"])==1  ){
}else{
	
 
?>			
<script>
 
	var chart = c3.generate({
		bindto: '#slider',
		data: {
			 xs: {
				<?php
					for($i=0;$i<sizeof($keyword_array);$i++){
						if($i>0){
						echo ",";//
						}
						echo "'".$keyword_array[$i]."': 'x".$i."'" ; 
					}
				?>	
			},

			columns: [
            <?php	
				for($i=0;$i<sizeof($data_list);$i++){
					if($i>0){
						echo ",";
					}
					echo "['x".$i."',";
					for($j=0;$j<sizeof($data_list[$i]);$j+=4){
						if($j>0){
							echo ",";
						}
						echo "'".$data_list[$i][$j]."-".$data_list[$i][$j+1]."-".$data_list[$i][$j+2]."'";
					}
					echo "]" ;
				}
				echo "," ;
				for($i=0;$i<sizeof($data_list);$i++){
					if($i>0){
						echo ",";
					} 
					echo "['".$keyword_array[$i]."',"; 
					for($j=0;$j<sizeof($data_list[$i]);$j+=4){
						if($j>0){
							echo ",";
						}
						echo   $data_list[$i][$j+3] ;
					}
					echo "]" ;
				}
 
			?>
			],
			onclick: function (d, element) {
			}
		},
		axis: {
            x: {
                type: 'timeseries',
				tick: {
					format: '%Y-%m-%d'
				}
			}
		},
		
        subchart: {
            show: true,
                onbrush: function(){

			}
		},
		
        zoom: { 
            enabled: true
		}	
    });
    </script>
<?php	
}
?>	
<?php
include_once("footer.php");
?>

