<?php
include_once("./header.php");
include_once("./commonlib.php");
if (empty($_POST["MyKeyword"])==1  ){//CCC:不要用is_null來判斷，用empty比較有效
	echo '尚未指定關鍵字，關鍵字格式:key1,key2,key3';
}else{
	//echo $_POST["MyKeyword"];
	
	//清除
	$conn = getConnection("news_db");
	$sql='TRUNCATE TABLE every_day_keywords';
	//mysqli_query($conn,'TRUNCATE TABLE every_day_keywords');
	mysql_query($sql,$conn);
	
	 
	$keyword_array=explode (",",$_POST["MyKeyword"]);
	for($i=0;$i<sizeof($keyword_array);$i++){
		if( strlen($keyword_array[$i])>0   ){
			$sql="INSERT INTO every_day_keywords (keyword) VALUES ('".$keyword_array[$i]."'   )"; //date("Y-m-d",strtotime($news_date))    //strip_tags($g_element->parent()->parent()->childNodes(1))
			mysql_query($sql,$conn);
		}
	}
	 
	
	
	 
	//var_dump($keyword_array)  ;
	//echo 'g';
	//echo '已經在資料庫設定關鍵字:'.$_POST["MyKeyword"].',系統將在每天12:30與18:30抓取最近兩日新聞';
	
}
//  30 18 * * * /usr/bin/php -q  /var/www/html/dashboard/google_news_crawler.php
//  30 12 * * * /usr/bin/php -q  /var/www/html/dashboard/google_news_crawler.php
//  service crond restart
?>
<?php
include_once("./footer.php");
?>











