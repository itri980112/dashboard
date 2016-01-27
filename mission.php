<?php
include_once("/var/www/html/dashboard/commonlib.php");
function startsWith($hit_times,$category_data ,$title,$conn,$product_id) {
    for($j = 0 ; $j < sizeof($category_data) ; $j++){
		$num_of_searchkeyword_matched=0;
		//CCC:假設大分類和中分類的search keyword只有一個關鍵字
		$search_keyword_array=explode (",",$category_data[$j]['searchKeyword']);
		//CCC:假設小分類 的search keyword可以有多個關鍵字
		$small_search_keyword_array=explode ("_",$search_keyword_array[2]);
		//CCC:掃描大分類、中分類
		for($k = 0 ; $k < 2 ; $k++){
			//CCC:檢查product的title欄位是否包含某一類別的搜尋關鍵字
			if( strpos($title, $search_keyword_array[$k])  >0){
				//$ary[i] 包含 $category_data[j]
				//echo $ary[$i]['title'].','.$category_data[$j]['searchKeyword'].'<br>';
				 
				$num_of_searchkeyword_matched=$num_of_searchkeyword_matched+1;
			}
		}
		for($k1 = 0 ; $k1 < sizeof($small_search_keyword_array) ; $k1++){
			//CCC:檢查product的title欄位是否包含某一類別的搜尋關鍵字
			if( strpos($title, $small_search_keyword_array[$k1] )  >0){
				//$ary[i] 包含 $category_data[j]
				//echo $ary[$i]['title'].','.$category_data[$j]['searchKeyword'].'<br>';
				 
				$num_of_searchkeyword_matched=$num_of_searchkeyword_matched+1;
			}
		}
		if($num_of_searchkeyword_matched==$hit_times ){
			 
			$possible_category=$category_data[$j]['catid'];
			
			$sql="INSERT INTO map_prod_categoryV2 (pid,catid) VALUES ( ".$product_id." , ".$possible_category." )"; //date("Y-m-d",strtotime($news_date))    //strip_tags($g_element->parent()->parent()->childNodes(1))
			mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
		}
	}
}
/*
select * from product where created_at>'2016-01-05' 
CCC:我們先看product表的一筆data
array(1996) {
	[0]=> array(34) {
		[0]=> string(8) "25005629" 
		["id"]=> string(8) "25005629"
		[1]=> string(6) "285799"
		["store_id"]=> string(6) "285799"
		[2]=> string(31) " 簡約時尚針織西裝外套"
		["title"]=> string(31) " 簡約時尚針織西裝外套"
		[3]=> string(0) ""
		["subtitle"]=> string(0) ""
		[4]=> string(0) ""
		["tag"]=> string(0) ""
		[5]=> string(94)     "http://www.littlemoni.com.tw/littlemoni/index.php?action=product_detail&prod_no=P0000100003021"
		["url"]=> string(94) "http://www.littlemoni.com.tw/littlemoni/index.php?action=product_detail&prod_no=P0000100003021"
		[6]=> string(9) "266969264"
		["hash_url"]=> string(9) "266969264"
		[7]=> string(11) "92330107052"
		["enternal_product_id"]=> string(11) "92330107052"
		[8]=> string(1179) "
*/
 
	ignore_user_abort(true);
	set_time_limit(0);
	
	//CCC:這邊是把categoryV2的資料先載入到記憶體，不要每次都到資料庫取出categoryV2的資料
	$conn = getConnection("ibabymall");
	$category_data=array();
	$list_query="select * from categoryV2 where length(categoryCode)=9";
 
	$list0  = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
	while($row = mysql_fetch_array($list0))
        $category_data[]=$row ;
	
	/*
	rray(8) {
		[0]=> string(1) "1"
		["catid"]=> string(1) "1"
		[1]=> string(9) "外出服"
		["category"]=> string(9) "外出服"
		[2]=> string(3) "001"
		["categoryCode"]=> string(3) "001"
		[3]=> string(6) "外出"
		["searchKeyword"]=> string(6) "外出"
	}
	*/
	//CCC:這裡的問題是只要insert還沒被檢查過的product id到底是哪一類的
	$yest_str=date("Y-m-d", strtotime("-1 day"));//前天日期
	$conn = getConnection("ibabymall");
 
	$keywords='';
	//select * from product where created_at>'2016-01-01' order by created_at desc
	$list_query="select * from product where catid is NULL  and created_at>'".$yest_str."' ";
	echo $list_query;
	$list1  = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
    $ary=array();
    while($row = mysql_fetch_array($list1))
        $ary[]=$row;
    mysql_free_result($list1);
     
	echo '<br><br><br><br><br><br><br><br><br>';
	//var_dump($category_data );
	
    for($i = 0 ; $i < sizeof($ary) ; $i++){
		$temp_b=false;
		$max_num_of_searchkeyword_matched=0;
		$possible_category=-1;
		for($j = 0 ; $j < sizeof($category_data) ; $j++){
			$num_of_searchkeyword_matched=0;
			//CCC:假設大分類和中分類的search keyword只有一個關鍵字
			$search_keyword_array=explode (",",$category_data[$j]['searchKeyword']);
			//CCC:假設小分類 的search keyword可以有多個關鍵字
			$small_search_keyword_array=explode ("_",$search_keyword_array[2]);
			//CCC:掃描大分類、中分類
			for($k = 0 ; $k < 2 ; $k++){
				//CCC:檢查product的title欄位是否包含某一類別的搜尋關鍵字
				if( strpos($ary[$i]['title'], $search_keyword_array[$k])  >0){
					//$ary[i] 包含 $category_data[j]
					//echo $ary[$i]['title'].','.$category_data[$j]['searchKeyword'].'<br>';
					$temp_b=true;
					$num_of_searchkeyword_matched=$num_of_searchkeyword_matched+1;
				}
			}
			for($k1 = 0 ; $k1 < sizeof($small_search_keyword_array) ; $k1++){
				//CCC:檢查product的title欄位是否包含某一類別的搜尋關鍵字
				if( strpos($ary[$i]['title'], $small_search_keyword_array[$k1] )  >0){
					//$ary[i] 包含 $category_data[j]
					//echo $ary[$i]['title'].','.$category_data[$j]['searchKeyword'].'<br>';
					$temp_b=true;
					$num_of_searchkeyword_matched=$num_of_searchkeyword_matched+1;
				}
			}
			if($num_of_searchkeyword_matched>$max_num_of_searchkeyword_matched ){
				$max_num_of_searchkeyword_matched=$num_of_searchkeyword_matched;
				$possible_category=$category_data[$j]['catid'];
			}
		}
		if($temp_b==false){
			//echo 'no category:'.$ary[$i]['title'].'<br>';
		}else{
	        startsWith($max_num_of_searchkeyword_matched,$category_data ,$ary[$i]['title'],$conn,$ary[$i]['id']);
			$sql = "UPDATE product SET catid='1' WHERE id=".$ary[$i]['id'];
			mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
		}
	}
//var_dump($ary);

passthru('kill -9 ' . getmypid());
exec('kill -9 ' . getmypid());

// CCC:如果要直接執行php程式，可以在cmd執行/usr/bin/php -q /var/www/html/dashboard/mission.php 
?>