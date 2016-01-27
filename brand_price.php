<?php
include_once("./header.php");
include_once("./commonlib.php");
//-----
$p_category =  $_GET['product_category_id'];
if (empty($p_category)==1){//CCC:不要用is_null來判斷get變數內容，用empty比較有效
    echo 'the p_category is empty!';
	$p_category=0;//CCC:設定變數的預設值
	$p_category_str='看全部';
}
//-----
$conn = getConnection("ibabymall");
//-----
$list_query="select catid,category from categoryV2 order by catid asc";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
$category_list[0] = "看全部";
while($row = mysql_fetch_array($list1)){
    $category_list[$row[0]] = $row[1];
}
//-----
$price_data = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];
if($p_category==0){
	$ary = getBrandPrice($conn, $price_data);
}else{
	$ary = getBrandPrice_V3_by_prod_category($conn, $price_data,$p_category);
}

for($i = 0 ; $i < sizeof($ary) ; $i++)
    $title[$ary[$i][0]] = $ary[$i][1];
$price_data_sz = sizeof($price_data);
for($i = 1 ; $i < $price_data_sz ; $i++)
    $range["$i"] = $price_data[$i-1] . "~" . ($price_data[$i]-1);
$range["$price_data_sz"] = ($price_data[$price_data_sz-1]);


for($i = 0 ; $i < sizeof($ary) ; $i++){
    foreach($range as $key)
        $store[$key] = $ary[$i][$key];
    $data[$ary[$i][1]] = $store;
}
$result["title"] = $title;
$result["range"] = $range;
$result["data"] = $data;
$result["total"] = sizeof($ary);
draw_3layer_UI($conn,'brand_price.php');
?>

    <div class="container">
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
						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./brand_price.php?product_category_id='.$k.'">'.$v.'</a></li>';
					}
				?>
			</ul>
		</div>
		-->
		<br/>
        <div id="table">
        </div>
		<script type="text/javascript">
		//CCC:檢查一下collapse004在本檔案出現的三個地方，目的是當第二層展開時，第一層不會縮回去，這是最大的關鍵
				$(function () { $('#collapseOne').collapse({
					toggle: false
				})});
				$(function () { $('#collapseTwo').collapse({
					toggle: false
				})});
				$(function () { $('#collapse003').collapse({
					toggle: false
				})});
				$(function () { $('#collapse004').collapse({
					toggle: false
				})});
			/*
			$(function () { $('#collapseTwo').collapse('show')});
			$(function () { $('#collapseThree').collapse('toggle')});
			$(function () { $('#collapseOne').collapse('hide')});
			*/
		</script>  
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script type="text/javascript">
            //$.get("./analyze_price.php", function(response){
            //    raw=$.parseJSON(response);
			var raw = <?php  echo json_encode($result); ?>;
                var color = ['#4FC5C7', '#97EC71', '#F3E59A', '#DE9DD6', '#FA6E86'];
                var table = '<table class="table">\n';
                var table_title = "<tr>\n    <th>價格帶</th>\n";
                var color_picker = 0;

                for (var store_key in raw['data']) {
                    if (raw['data'].hasOwnProperty(store_key)) {
                        table_title += '    <th style="border-bottom: solid 4px ' + color[color_picker%5] + ';">' + store_key + "</th>\n";
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
                        for (var brand_key in raw['data']) {
                            if (raw['data'].hasOwnProperty(brand_key)) {
                                var amount = raw['data'][brand_key][raw['range'][range_key]];
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

