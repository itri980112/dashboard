<?php
//ini_set('display_errors', 'On');

function printPagination($now_page, $page_num, $page_format="%d", $len=10){
    //$start = floor(($now_page - 1) / $len) * $len + 1;
    if($now_page < 5){
        $start = 1;
    }
    else{
        $start = $now_page-4;
    }    
    $end = $start + $len;
    echo "<div style='margin: 0 auto; text-align: center;'>";
    echo "</nav>";
    echo "<ul class='pagination'>";
	printf("<li><a href='$page_format'><<<</a></li>", 1);
    printf("<li><a href='$page_format'><<</a></li>", $now_page-$len);
    printf("<li><a href='$page_format'><</a></li>", $now_page-1);
    for($i = $start ; $i < $end ; $i++){
        if($i>$page_num) break;
        if($i==$now_page) printf("<li class='active'>");
        else printf("<li>");
        printf("<a href='$page_format'>$i</a></li>", $i);
    }
    printf("<li><a href='$page_format'>></a></li>", $now_page+1);
    printf("<li><a href='$page_format'>>></a></li>", $now_page+$len);
	printf("<li><a href='$page_format'>>>></a></li>", $page_num);
	/*
	printf("<li><font size='2'>跳第<select onchange='location.href='$page_format'this.value'>
    <option>select your page</option>
    <option value='1'>1</option>頁</select>頁</font> </li>' );
	*/
    echo "</ul>";
    echo "</nav>";
    echo "</div>";
}
/*
./store_list.php?page=%d

Select Language:
<select onChange="location.href='index.php?lang='+this.value">
    <option>select your language</option>
    <option value="zh_tw">中文(臺灣)</option>
    <option value="zh_cn">中文(簡体)</option>
    <option value="english">English</option>
</select>
*/





function getConnection($dbname){
    $dbhost = '127.0.0.1';
    $dbuser = 'root';
    $dbpass = 'iscae100';
    //    $dbname = 'ibabymall';
    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db($dbname);
    return $conn;
}

function getBrandCounts($conn){
    $sql='select b.brand,count(p.title) from brands b, product p where p.title like  CONCAT("%", b.brand ,"%") group by b.brand';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];

    }
    mysql_free_result($result);
    return $ary;
}


function getKeywordCounts($conn){
    $sql='select k.keyword,count(p.title) from product_name_keyword k, product p where p.title like  CONCAT("%", k.keyword ,"%") group by k.keyword';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];

    }
    mysql_free_result($result);

    return $ary;
}


function getBrandKeywordCounts($conn){
    $sql='select b.brand,k.keyword,count(km.pid)  from map_prod_keyword km, map_prod_brand bm,brands b,product_name_keyword k where km.pid=bm.pid and b.bid=bm.bid and k.kid=km.kid group by bm.bid,km.kid order by b.brand,count(km.pid) desc';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row[0];
        $ary[]=$row[1];
        $ary[]=$row[2];

    }
    mysql_free_result($result);

    return $ary;
}


function getTotalProducts($conn){
    $sql='select count(id) from product';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);

    return $cnt;
}

function getTotalStores($conn){
    $sql='select count(id) from store';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);

    return $cnt;
}


function getTotalBrands($conn){
    $sql='select count(bid) from brands';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);

    return $cnt;
}

function getStoreNameList($conn){
    $sql='select chinese_name from store where chinese_name!="" limit 50';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);

    return $ary;
}

function getBrandList($conn){
    $sql='select brands.bid, brands.brand, b.count from brands inner join (select bid, count(*) as count from map_prod_brand group by bid) as b on brands.bid=b.bid order by b.count DESC';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);

    return $ary;
}

function getAllBrandList($conn){
    $sql='select * from brands';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);

    return $ary;
}
function getCategoryList($conn){
    $sql='select category.catid, category.category, b.count from category inner join (select catid, count(*) as count from map_prod_category group by catid) as b on category.catid=b.catid order by b.count DESC';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);

    return $ary;
}

function getCategoryIdBySearch($conn,$word){
     
    $sql = "SELECT catid FROM `category` where category = '$word'";
    $result = mysql_query($sql,$conn) or die('MyAQL querry error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);

    return $ary;
}

function getCategoryCountByCatidSearch($conn,$catid){
    $sql = "SELECT COUNT(id) FROM `map_prod_category` where catid = $catid";
    $result = mysql_query($sql,$conn) or die('MyAQL querry error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);

    return $ary;
}

function getStoreList($conn){
    $sql = 'select store.* , b.`count` from store inner join (select store_id, count(*) as `count` from product group by store_id) as b on store.id = b.store_id AND store.source!=3 order by b.count DESC, store.id ASC';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);
    return $ary;
}

function getSourceList(){
    $conn = getconnection("ibabymall");
    $ary = array();
    $sql = "SELECT * FROM `source` ORDER BY `source_id` ASC";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    while($row = mysql_fetch_array($result)){
        $ary[$row[0]] = $row[1];
    }
    mysql_free_result($result);
    return $ary;
}
function getSourceCountList($conn){
    $sql = " select source, SUM(b.cnt) from store inner join (select store_id, count(*) as cnt from product group by store_id) as b on b.store_id=store.id group by source order by store.source ASC";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);
    return $ary;
}
function getHotProductByStoreId($conn, $store_id, $count=30){
    $sql = "select * from product where store_id=$store_id limit $count";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);
    return $ary;
}
function getProductByID($conn, $pid){
    $sql = "select * from product where id=$pid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);
    return $ary;
}

function getProductCountBySearch($conn,$word){

    $sql = "select COUNT(id) from product where title like  '%$word%'";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);
    return $ary;

}

function getProductListByStoreId($conn, $store_id, $start, $count){
    $sql = "select * from product where store_id=$store_id order by id limit $start, $count";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    mysql_free_result($result);
    return $ary;
}

function getProductTotalByStoreId($conn, $store_id){
    $sql="select count(id) from product where store_id=$store_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);
    return $cnt;
}

function getProductListBySearch($conn, $word, $start, $count){
    $sql = "select * from product where (title like '%$word%') AND available=1 order by id limit $start, $count";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    return $ary;
}

function getProductListBySearch2($conn, $word1,$word2, $start, $count){
    $sql = "select * from product where (title like '%$word1%' AND title like '%$word2%') AND available=1 order by id limit $start, $count";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[]=$row;
    }
    return $ary;
}


function getProductTotalBySearch($conn, $word){
    $sql="select count(id) from product where (title like '%$word%') AND available=1";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);
    return $cnt;
}

function getProductListByBrandId($conn, $brand_id, $start, $count){
    $sql = "select * from product inner join (select pid from map_prod_brand where bid=$brand_id order by pid limit $start, $count) as b on product.id=b.pid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    return $ary;
}

function getProductTotalByBrandId($conn, $brand_id){
    $sql="select count(mid) from map_prod_brand where bid = $brand_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);
    return $cnt;
}

function getProductListByCategoryId($conn, $category_id, $start, $count){
    $sql = "select * from product inner join (select pid from map_prod_category where catid=$category_id order by pid limit $start, $count) as b on product.id=b.pid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    return $ary;
}

function getProductTotalByCategoryId($conn, $category_id){
    $sql="select count(catid) from map_prod_category where catid = $category_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);
    return $cnt;
}

function getNewProductList($conn, $source, $store_id, $start_date, $end_date,$start, $count){
    $ary=array();
    $tmp_end_date=new DateTime("$end_date");
    $tmp_end_date->modify('+1 day');
    $add_end_date=date_format($tmp_end_date, 'Y/m/d');
    if ($source=="0" && $store_id=="0"){
        $sql = "select p.* from product as p, (SELECT id FROM product where created_at>'$start_date' and created_at<'$add_end_date' order by id limit $start, $count) as p2 WHERE p.id=p2.id";
    }
    else if($store_id=="0"){
        $sql = "select * from product inner join (select id,source from store where source=$source) as b on product.store_id=b.id where created_at>\"$start_date\" and created_at<\"$add_end_date\" limit $start, $count";
    }
    else{
        $sql = "select * from product where store_id=$store_id and created_at>\"$start_date\" and created_at<\"$add_end_date\" order by id limit $start, $count";
    }
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    return $ary;
}

function getNewProductTotal($conn, $source, $store_id, $start_date, $end_date){
    $cnt="0";
    $tmp_end_date=new DateTime($end_date);
    $tmp_end_date->modify('+1 day');
    $add_end_date=date_format($tmp_end_date, 'Y/m/d');
    if ($source=="0" && $store_id=="0"){
        $sql = "select count(id) from product where created_at>\"$start_date\" and created_at<\"$add_end_date\"";
    }
    else if($store_id=="0"){
        $sql = "select count(0) from product inner join (select id,source from store where source=$source) as b on product.store_id=b.id where created_at>\"$start_date\" and created_at<\"$add_end_date\"";
    }
    else{
        $sql = "select count(id) from product where store_id=$store_id and created_at>\"$start_date\" and created_at<\"$add_end_date\"";
    }
	
	 
	
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);
    return $cnt;
}

function getTotalNews($conn){
    $sql='SELECT count(newsid) FROM newsarticles';
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $cnt="0";
    if($row = mysql_fetch_array($result)){
        $cnt=$row[0];
    }
    mysql_free_result($result);

    return $cnt;
}


function startsWith($haystack, $needle) {
	/*
	example:
	startsWith("abcdef", "ab") -> true
startsWith("abcdef", "cd") -> false
startsWith("abcdef", "ef") -> false
startsWith("abcdef", "") -> true
startsWith("", "abcdef") -> false
	*/
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function getBrandPrice($conn, $data){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){

        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT b.bid,
        brands.brand, 
        "
        . $value . 
        "
        , count(*)
        FROM product
        INNER JOIN (SELECT pid, bid from map_prod_brand) as b on product.`id`=b.`pid`
        INNER JOIN brands on brands.bid = b.bid 
        INNER JOIN (SELECT bid from star_brand WHERE uid=1) as s on b.bid = s.bid 
        GROUP BY bid ORDER BY count(*) DESC;
    ";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;

}
function getBrandPrice_V3_threelayer_by_prod_category($conn, $data,$p_category){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){

        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT b.bid,
        brands.brand, 
        "
        . $value . 
        "
        , count(*)
        FROM product
		INNER JOIN (SELECT pid from map_prod_categoryV2 WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT pid, bid from map_prod_brand) as b on product.`id`=b.`pid`
        INNER JOIN brands on brands.bid = b.bid 
        INNER JOIN (SELECT bid from star_brand WHERE uid=1) as s on b.bid = s.bid 
        GROUP BY bid ORDER BY count(*) DESC;
    ";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;

}
function draw_3layer_UI($conn,$php_file){
	//-----------------------
	$list_query_V2="select * from categoryV2  where length(categoryCode)=3 order by categoryCode asc";
	$big_category_V2 = mysql_query($list_query_V2,$conn) or die('MySQL query error '.mysql_error().' '.$list_query_V2);
	$big_ary=array();
	while($row = mysql_fetch_array($big_category_V2))
		$big_ary[]=$row;
	mysql_free_result($big_category_V2);
	//--------------------
	$list_query_V2="select * from categoryV2  where length(categoryCode)=6 order by categoryCode asc";
	$middle_category_V2 = mysql_query($list_query_V2,$conn) or die('MySQL query error '.mysql_error().' '.$list_query_V2);
	$mid_ary=array();
	while($row = mysql_fetch_array($middle_category_V2))
		$mid_ary[]=$row;
	mysql_free_result($middle_category_V2);
	//var_dump($mid_ary );
	//--------------------
	$list_query_V2="select * from categoryV2  where length(categoryCode)=9 order by categoryCode asc";
	$small_category_V2 = mysql_query($list_query_V2,$conn) or die('MySQL query error '.mysql_error().' '.$list_query_V2);
	$small_ary=array();
	while($row = mysql_fetch_array($small_category_V2))
		$small_ary[]=$row;
	mysql_free_result($small_category_V2);
	//--------------------
	echo '<ul id="myTab" class="nav nav-tabs" >';
	for($i = 0 ; $i < sizeof($big_ary) ; $i++){
		if($i == 0 ){
			echo   '<li class="active"><a href="#big'.$big_ary[$i]['categoryCode'].'"  data-toggle="tab">'.$big_ary[$i]['category'].'</a></li>';
		}else{//CCC:移除active
			echo '<li><a href="#big'.$big_ary[$i]['categoryCode'].'"  data-toggle="tab">'.$big_ary[$i]['category'].'</a></li>';
		}
		
	}
	echo '</ul>';

	echo '<div id="myTabContent" class="tab-content">';// CCC: tab-content 讓tab的內容都從左上角顯示，如果沒有tab-content，就會把每個tab的內容由上而下全部顯示，有tab-content就會一次只顯示一個tab內容  
		for($i = 0 ; $i < sizeof($big_ary) ; $i++){
			if($i == 0 ){
				echo '<div class="tab-pane fade in active" id="big'.$big_ary[$i]['categoryCode'].'">';//CCC: id= out_go 對應到 上方的<a href="# "
				//echo 'test'.$i;
					echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';//CCC: panel-group 只有在Collapse（折叠）的時候會用到
						for($j = 0 ; $j < sizeof($mid_ary) ; $j++){
							if(startsWith($mid_ary[$j]['categoryCode'], $big_ary[$i]['categoryCode'])       ){
								echo '<div class="panel panel-info" style="margin-left:10px; " >';//CCC:注意是width:550px; 不要寫width=550px;
									echo '<div class="panel-heading" role="tab" style="border-style:solid;"  >';
										echo '<h4 class="panel-title">';
											echo '<a data-toggle="collapse" data-parent="#accordion" href="#mid'.$mid_ary[$j]['categoryCode'].'">';
												echo $mid_ary[$j]['category'];
											echo '</a>';
										echo '</h4>';
									echo '</div>';
									echo '<div id="mid'.$mid_ary[$j]['categoryCode'].'" class="panel-collapse collapse"  role="tabpanel" aria-labelledby="headingOne" style="border-color:#bce8f1; border-style:solid;">';
										echo '<div class="panel-body">';
											for($k = 0 ; $k < sizeof($small_ary) ; $k++){
												if(startsWith($small_ary[$k]['categoryCode'], $mid_ary[$j]['categoryCode'])       ){//href="./testCCC.php?product_category_id=
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./'.$php_file.'?product_category_id='.$small_ary[$k]['catid'].'">'.$small_ary[$k]['category'].'</a></li>';
												} 
											}
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						}
					echo '</div>';
				echo '</div>';//end of  <div class="tab-pane fade in，BIG
			}else{//CCC:移除active，不然剛載入page會看到一大堆東西
				echo '<div class="tab-pane fade in " id="big'.$big_ary[$i]['categoryCode'].'">';
				//echo 'test'.$i;
					echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
						for($j = 0 ; $j < sizeof($mid_ary) ; $j++){
							if(startsWith($mid_ary[$j]['categoryCode'], $big_ary[$i]['categoryCode'])       ){
								echo '<div class="panel panel-info" style="margin-left:10px; " >';
									echo '<div class="panel-heading" role="tab" style="border-style:solid;"  >';
										echo '<h4 class="panel-title">';
											echo '<a data-toggle="collapse" data-parent="#accordion" href="#mid'.$mid_ary[$j]['categoryCode'].'">';
												echo $mid_ary[$j]['category'];
											echo '</a>';
										echo '</h4>';
									echo '</div>';
									echo '<div id="mid'.$mid_ary[$j]['categoryCode'].'" class="panel-collapse collapse"  role="tabpanel" aria-labelledby="headingOne" style="border-color:#bce8f1; border-style:solid;">';
										echo '<div class="panel-body">';
											for($k = 0 ; $k < sizeof($small_ary) ; $k++){
												if(startsWith($small_ary[$k]['categoryCode'], $mid_ary[$j]['categoryCode'])       ){
													echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./'.$php_file.'?product_category_id='.$small_ary[$k]['catid'].'">'.$small_ary[$k]['category'].'</a></li>';
												}
											}
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						}
					echo '</div>';
				
				echo '</div>';//end of  <div class="tab-pane fade in，BIG
			}
		}
	echo '</div>';
}
function getBrandPrice_V3_by_prod_category($conn, $data,$p_category){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){

        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT b.bid,
        brands.brand, 
        "
        . $value . 
        "
        , count(*)
        FROM product
		INNER JOIN (SELECT pid from map_prod_categoryV2 WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT pid, bid from map_prod_brand) as b on product.`id`=b.`pid`
        INNER JOIN brands on brands.bid = b.bid 
        INNER JOIN (SELECT bid from star_brand WHERE uid=1) as s on b.bid = s.bid 
        GROUP BY bid ORDER BY count(*) DESC;
    ";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;

}
function getBrandPrice_V2_by_prod_category($conn, $data,$p_category){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){

        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT b.bid,
        brands.brand, 
        "
        . $value . 
        "
        , count(*)
        FROM product
		INNER JOIN (SELECT pid from map_prod_category WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT pid, bid from map_prod_brand) as b on product.`id`=b.`pid`
        INNER JOIN brands on brands.bid = b.bid 
        INNER JOIN (SELECT bid from star_brand WHERE uid=1) as s on b.bid = s.bid 
        GROUP BY bid ORDER BY count(*) DESC;
    ";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;

}
function getPriceByStarStoreV3_product_category($conn, $data,$p_category){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
	 
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT product.store_id, 
        store.chinese_name,
        "
        . $value . 
        "
        , count(*)
        FROM product 
		INNER JOIN (SELECT pid from map_prod_categoryV2 WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
 
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
 
		$ary[]=$row;
	}
        
    mysql_free_result($result);
 
    return $ary;
}
function getPriceByStarStoreV2_product_category($conn, $data,$p_category){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
	 
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT product.store_id, 
        store.chinese_name,
        "
        . $value . 
        "
        , count(*)
        FROM product 
		INNER JOIN (SELECT pid from map_prod_category WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
 
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
 
		$ary[]=$row;
	}
        
    mysql_free_result($result);
 
    return $ary;
}
function getPriceByStarStore($conn, $data){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
 
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT product.store_id, 
        store.chinese_name,
        "
        . $value . 
        "
        , count(*)
        FROM product 
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
 
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
 
		$ary[]=$row;
	}
        
    mysql_free_result($result);
 
    return $ary;
}

function getPriceByStarStoreV1_the_product_of_price_and_numofproducts($conn, $data){
    
	 
	
	$value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN ".($data[$i-1]+50)." ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
	 
	
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";
    $sql = 
        "
        SELECT product.store_id, 
        store.chinese_name,
        "
        . $value . 
        "
        , count(*)
        FROM product 
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
 
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
 
		$ary[]=$row;
	}
        
    mysql_free_result($result);
 
    return $ary;
	
}

function getPriceByStore($conn, $data, $store_id){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-1) . " THEN 1 ELSE 0 END) AS `" .$data[$i-1]."~" . ($data[$i]-1) . "`,";
    }
    $value .= "SUM(CASE WHEN `current_price` > ".$data[$sz-1]." THEN 1 ELSE 0 END) AS `".$data[$sz-1] . "`";

    $sql = 
        "
        SELECT 
        "
        . $value . 
        "
        , count(*)
        FROM product WHERE store_id=$store_id
        ORDER BY count(*) DESC;
    ";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
}
function getDiscount($conn, $data){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` /`recommend_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-0.01) . " THEN 1 ELSE 0 END),";
    }
    $sql =
        "
        SELECT b.bid,
        brands.brand,
        SUM(CASE WHEN `current_price` < `recommend_price` THEN 1 ELSE 0 END),
        SUM(1), 
        "
        . $value .
        "
        SUM(CASE WHEN `current_price` /`recommend_price`=1 THEN 1 ELSE 0 END)
        FROM product
        INNER JOIN (SELECT pid, bid from map_prod_brand) as b on product.`id`=b.`pid`
        INNER JOIN brands on brands.bid = b.bid GROUP BY bid ORDER BY SUM(1) DESC;
    ";


    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
}
function getDiscountByStore($conn, $data, $store_id){
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` /`recommend_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-0.01) . " THEN 1 ELSE 0 END),";
    }
    $sql =
        "
        SELECT
        SUM(CASE WHEN `current_price` < `recommend_price` THEN 1 ELSE 0 END),
            SUM(1), 
        "
        . $value .
        "
        SUM(CASE WHEN `current_price` /`recommend_price`=1 THEN 1 ELSE 0 END)
        FROM product WHERE store_id=$store_id and available=1 
        ORDER BY SUM(1) DESC;
    ";


    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
}
function getDiscountByStarStore($conn, $data){
	
	 
    $value = "";
    $sz = sizeof($data);
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN `current_price` /`recommend_price` BETWEEN " . $data[$i-1] . " AND " . ($data[$i]-0.01) . " THEN 1 ELSE 0 END),";
    }
    $sql =
        "
        SELECT
        product.store_id,
        store.chinese_name,
        SUM(CASE WHEN `current_price` < `recommend_price` THEN 1 ELSE 0 END),
        SUM(1), 
        "
        . $value .
        "
        SUM(CASE WHEN `current_price` /`recommend_price`=1 THEN 1 ELSE 0 END)
        FROM product
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY s.store_id
        ORDER BY SUM(1) DESC;
    ";
 
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
}
function getProductDataByCreatedTimeFromStarStoreV2($conn,$month_data,$p_category  ){
	  
    $value = ""; 
    $sz = sizeof($month_data);; 
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN product.created_at  <= '" . $month_data[$i-1] . "' AND product.created_at >= '" . ($month_data[$i]) . "' THEN 1 ELSE 0 END) as '".substr($month_data[$i], 0,10)  ."~".substr($month_data[$i-1], 0,10)."',";
    }
    $sql =
        "
        SELECT
        product.store_id,
        store.chinese_name,
        "
        . $value .
        "
        count(*)
        FROM product
		INNER JOIN (SELECT pid from map_prod_category WHERE catid=".$p_category.") as prod_c_id  on prod_c_id.pid = product.id
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
     
	
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
	 
}



function getProductDataByCreatedTimeFromStarStore($conn,$month_data  ){
	  
    $value = ""; 
    $sz = sizeof($month_data);; 
    for($i = 1 ; $i < $sz ; $i++){
        $value .= "SUM(CASE WHEN product.created_at  <= '" . $month_data[$i-1] . "' AND product.created_at >= '" . ($month_data[$i]) . "' THEN 1 ELSE 0 END) as '".substr($month_data[$i], 0,10)  ."~".substr($month_data[$i-1], 0,10)."',";
    }
    $sql =
        "
        SELECT
        product.store_id,
        store.chinese_name,
        "
        . $value .
        "
        count(*)
        FROM product
        INNER JOIN (SELECT store_id from star_store WHERE uid=1) as s on product.store_id = s.store_id
        INNER JOIN store on store.id = s.store_id
        GROUP BY product.store_id
        ORDER BY count(*) DESC;
    ";
 
	
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[]=$row;
    mysql_free_result($result);
    return $ary;
	 
}

function getCountryData($conn){
    $sql = "select a.*, b.cnt from countrylist as a inner join (select cid, count(*) as cnt from map_prod_country group by cid) as b on a.cid = b.cid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result))
        $ary[] = $row;
    mysql_free_result($result);
    return $ary;
}
function getStarBrand($conn, $uid){
    $sql="SELECT star_brand.bid, b.brand FROM `star_brand` INNER JOIN (SELECT bid, brand FROM brands) as b on star_brand.`bid`=b.`bid` WHERE uid=$uid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);
    return $ary;
}
function checkStarBrand($conn, $uid, $bid){
    $sql="SELECT count(*) FROM star_brand WHERE uid=$uid AND bid=$bid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $row = mysql_fetch_array($result)[0];
    mysql_free_result($result);
    return $row;
}
function addStarBrand($conn, $uid, $bid){
    $sql="INSERT INTO star_brand (uid, bid) VALUES ($uid, $bid)";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
}
function deleteStarBrand($conn, $uid, $bid){
    $sql = "DELETE FROM star_brand WHERE uid=$uid AND bid=$bid";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
}
function getStarStore($conn, $uid){
    $sql = "SELECT star_store.store_id, store.chinese_name, store.source FROM star_store, store WHERE star_store.uid=$uid AND store.id = star_store.store_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);
    return $ary;
}
function checkStarStore($conn, $uid, $store_id){
    $sql = "SELECT count(*) FROM star_store where uid=$uid and store_id=$store_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $row = mysql_fetch_array($result)[0];
    mysql_free_result($result);
    return $row;
}
function addStarStore($conn, $uid, $store_id){
    $sql="INSERT INTO star_store (uid, store_id) VALUES ($uid, $store_id)";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
}
function deleteStarStore($conn, $uid, $store_id){
    $sql = "DELETE FROM star_store WHERE uid=$uid AND store_id=$store_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
}
function getStarStore2($conn, $uid){
    $sql = "SELECT star_store.store_id, store.chinese_name, store.source FROM star_store, store WHERE star_store.uid=$uid AND store.id = star_store.store_id";
    $result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary=array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    mysql_free_result($result);
    return $ary;

}
function getAndroidAppList($conn){
    $sql = "SELECT id, name, star, url FROM app WHERE `type`=1 ORDER BY star DESC";
    $result = mysql_query($sql, $conn) or die('MySL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result))
        $ary[] = $row;
    mysql_free_result($result);
    return $ary;
}
function getAndroidApp($conn, $id){
    $sql = "SELECT id, name, star, url FROM app WHERE id=$id";
    $result = mysql_query($sql, $conn) or die('MySL query error '.mysql_error().' '.$sql);
    $ary = mysql_fetch_array($result);
    mysql_free_result($result);
    $sql = "SELECT * FROM app_comment WHERE app_id=$id";
    $ary['comment'] = array();
    $result = mysql_query($sql, $conn) or die('MySL query error '.mysql_error().' '.$sql);
    while($row = mysql_fetch_array($result))
        $ary['comment'][] = $row;
    mysql_free_result($result);
    return $ary;
}
function getStoreData($conn, $id){
    $sql = "SELECT * FROM store WHERE id=$id";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $row = mysql_fetch_array($result);
    $sql = "SELECT count(*) FROM product WHERE store_id=$id";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $row['source'] = getSourceList()[$row['source']];
    $row['count'] = mysql_fetch_array($result)[0];
    return $row;
}

function getActiveWall($conn, $from, $count){
    $sql = "SELECT active_wall.*, map_active_wall_type.name as style FROM active_wall, map_active_wall_type WHERE active_wall.type=map_active_wall_type.type ORDER BY id DESC LIMIT $from, $count";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result))
        $ary[] = $row;
    mysql_free_result($result);
    return $ary;
}

function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function getMaxResId($conn){
    $sql = "SELECT MAX(res_id) FROM map_response_ontology";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result))
        $ary[] = $row;
    mysql_free_result($result);
    return $ary;

}

function getResponsesList($conn,$init){
    $sql = "SELECT * FROM responses where res_ID>= $init and response_content != ' ' ORDER BY res_ID ASC limit 5";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result))
        $ary[] = $row;
    mysql_free_result($result);
    return $ary;
}

function insertMapResponseOntology($conn,$res_id,$ontology){
    $sql = "INSERT INTO `ibabymall`.`map_response_ontology` (`mid`, `res_id`, `oid`) VALUES (NULL, '$res_id', '$ontology');";
    mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);

}

function updateResponseOntology($conn,$res_id,$ontology){
    $sql = "UPDATE `ibabymall`.`map_response_ontology` SET `oid` = $ontology WHERE res_id = $res_id";
    mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);

}

function login($conn, $account, $passwd){
    $passwd = md5($passwd);
    $sql = "SELECT * FROM user WHERE account='$account'";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    if($row = mysql_fetch_array($result)){
        print_r($row);
        print($passwd);
        if($row['passwd'] == $passwd){
            return $row['id'];
        } else {
            return -1;
        }
    } else {
        return -1;
    }
}
function user_list($conn){
    $sql = "SELECT * FROM user";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
    $ary = array();
    while($row = mysql_fetch_array($result)){
        $ary[] = $row;
    }
    return $ary;
}
function add_user($conn, $account, $passwd){
    $passwd = md5($passwd);
    $sql = "INSERT INTO user (account, passwd) VALUES ('$account', '$passwd')";
    $result = mysql_query($sql, $conn) or die('MySQL query error '.mysql_error().' '.$sql);
}



?>
