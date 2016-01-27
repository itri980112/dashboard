<?php
include_once("./header.php");
include_once("./commonlib.php");
$p_category =  $_GET['product_category_id'];
if (empty($p_category)==1){//CCC:不要用is_null來判斷get變數內容，用empty比較有效
    //echo 'the p_category is empty!';
	$p_category=0;//CCC:設定變數的預設值
	$p_category_str='看全部';
}
$conn = getConnection("ibabymall");
$list_query="select catid,category from categoryV2 order by catid asc";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
$category_list[0] = "看全部";
while($row = mysql_fetch_array($list1)){
//CCC:這邊只是剛好$category_list的index是從1,2,循序開始
    $category_list[$row[0]] = $row[1];
}
//CCC:以上是在處理商品類別的程式碼，重點在於$category_list陣列
//CCC:下面的php 程式碼都是從analyze_price_by_store_all.php直接複製過來


$price_data = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];
//$ary = getPriceByStarStore($conn, $price_data);//CCC:commonlib.php
if($p_category==0){
	//echo '看全部';
	$ary = getPriceByStarStore($conn, $price_data);//CCC:commonlib.php
}else{
	$ary = getPriceByStarStoreV3_product_category($conn, $price_data,$p_category);//CCC:commonlib.php
}
//CCC:$CCCSortedString的作用是資料排序，把資料從php轉道javascript的過程中，順序會亂掉，所以一定要靠本字串
$CCCSortedString="";
for($i = 0 ; $i < sizeof($ary) ; $i++){ // sprintf("%d",$num)
	$title[$ary[$i][0]] = $ary[$i][1];
	$CCCSortedString =$CCCSortedString.$ary[$i][0].",";
	//echo '_title_';
	//var_dump($title[$ary[$i][0]]);
	//echo '_title_';
}
//CCC:去掉最後一個字
$CCCSortedStringV1= substr($CCCSortedString, 0, -1); 
$price_data_sz = sizeof($price_data);
for($i = 1 ; $i < $price_data_sz ; $i++)
    $range["$i"] = $price_data[$i-1] . "~" . ($price_data[$i]-1);
 
$range["$price_data_sz"] = ($price_data[$price_data_sz-1]);
 
for($i = 0 ; $i < sizeof($ary) ; $i++){
    foreach($range as $key)
        $store[$key] = $ary[$i][$key];
    $data[$ary[$i][0]] = $store;
}
$result["CCCSortedString"] = $CCCSortedStringV1;
$result["title"] = $title;
$result["range"] = $range;
$result["data"] = $data;
$result["total"] = sizeof($ary);
//echo json_encode($result);


draw_3layer_UI($conn,'store_price.php');
?>

	
	
    <div class="container">
		<br/>
		<div style="font-size:20px">
			<a href="./store_price.php">價格帶分析(商店)</a>
		</div>
		<br/>
		<h2>依照商店之商品總數排序</h2>
		<h2>目前所選商品類別為<?php  echo  $category_list[$p_category] ;?></h2>
		<br/>
		<!--
		<div class="dropdown">
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
				選擇商品類別
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<?php
					foreach($category_list as $k => $v){
						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./store_price.php?product_category_id='.$k.'">'.$v.'</a></li>';
					}
				?>
			</ul>
		</div>
		-->
		<br/>
	
        <div id="table">
        </div>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script type="text/javascript">
		
			var raw = <?php  echo json_encode($result); ?>;
			//console.log(raw);
			//raw=$.parseJSON(CCCvalue);//CCC:這邊應該就不要使用parseJSON
/*			
//$.get("./analyze_price_by_store_all.php", function(response){
//alert('CCC test');
console.log(response);
raw=$.parseJSON(response);
*/
			var color = ['#4FC5C7', '#97EC71', '#F3E59A', '#DE9DD6', '#FA6E86'];
			var table = '<table class="table">\n';
			var table_title = "<tr>\n    <th>價格帶</th>\n";
			var color_picker = 0;

			var testCCC=raw['CCCSortedString'];
			//console.log(testCCC);
			//alert(testCCC);
			var CCCSortedArray =testCCC.split(",") ;
			//console.log(raw['data']);
			for(var ii1=0;ii1<CCCSortedArray.length;ii1++){
				//alert('ff'+CCCSortedArray[ii1]);
				if (raw['data'].hasOwnProperty(CCCSortedArray[ii1])) {
					//alert('YYYYYYYYYYYYAAAAAAAAA');
					table_title += '    <th style="border-bottom: solid 4px ' + color[color_picker%5] + ';">' + raw['title'][CCCSortedArray[ii1]] + "</th>\n";
					color_picker += 1;
				}
			}
				 
			table_title += "</tr>\n"
				var table_data = "";
			for (var range_key in raw['range']) {
				if (raw['range'].hasOwnProperty(range_key)) {
					table_data += "<tr>\n";
					table_data += '    <td>' + raw['range'][range_key] + "</td>\n";
					color_picker = 0;
					for(var ii1=0;ii1<CCCSortedArray.length;ii1++){
						if (raw['data'].hasOwnProperty(CCCSortedArray[ii1])) {
							var amount = raw['data'][CCCSortedArray[ii1]][raw['range'][range_key]];
							var radius = Math.log(amount) * 5;
							var circle = '<svg height="100" width="100">\
								<circle cx="50" cy="50" r="' + radius + '" stroke="black" stroke-width="0" fill="' + color[color_picker%5] +
								'" />\
								<text x="50%" y="50%" text-anchor="middle" fill="black" dy=".3em" ;>' + amount + '</text>\
								</svg>';
							color_picker += 1;
							table_data += '    <td>' + circle + '</td>\n';
						}
					}
			 
					table_data += "</tr>\n";
				}
			};
			table = table + table_title + table_data + "</table>\n"
				$("#table").append(table);
			
//})
        </script>
    </div>

<?php
include_once("footer.php");
 
?>

 