<?php
include_once("./header.php");
include_once("./commonlib.php");
$p_category =  $_GET['product_category_id'];
if (empty($p_category)==1){ 
     
	$p_category=0; 
	$p_category_str='看全部';
}
$conn = getConnection("ibabymall");
$list_query="select catid,category from category order by catid asc";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
$category_list[0] = "看全部";
while($row = mysql_fetch_array($list1)){
 
    $category_list[$row[0]] = $row[1];
}
 

 

$today_str=date("Y-m-d 00:00:00",time()); 
$today_d_str=date("d",time()); 
 
if(strcmp("01",$today_d_str)!=0){ 
	 
	$month_data[0]=$today_str; 
	$month_data[1]= date("Y-m-01 00:00:00",time()); 
	for($i = 2 ; $i <= 12 ; $i++){
		$month_data[$i]=date("Y-m-01 00:00:00",strtotime("-".($i-1)." month"));  
 
	}
}else{
	$month_data[0]=$today_str;
	for($i = 1 ; $i <= 11 ; $i++){
		$month_data[$i]=date("Y-m-01 00:00:00",strtotime("-".($i)." month"));  
 
	}
}
 
if($p_category==0){
 
	$ary = getProductDataByCreatedTimeFromStarStore($conn,$month_data  );
}else{
	$ary = getProductDataByCreatedTimeFromStarStoreV2($conn,$month_data ,$p_category );
}
 
$CCCSortedString="";
for($i = 0 ; $i < sizeof($ary) ; $i++){
    $title[$ary[$i][0]] = $ary[$i][1];
	$CCCSortedString =$CCCSortedString.$ary[$i][0].",";
	 
}
 
$CCCSortedStringV1= substr($CCCSortedString, 0, -1); 
$sz = sizeof($month_data);; 
for($i = 1 ; $i < $sz ; $i++){
	$range[($i-1)]=substr($month_data[$i], 0,10)  ."~".substr($month_data[$i-1], 0,10);
}
 
for($i = 0 ; $i < sizeof($ary) ; $i++){
	 
    foreach($range as $key)
        $product_created[$key] = $ary[$i][$key];
	$rev_product_created= array_reverse($product_created); 
	$item_data["product_created"] = $rev_product_created;
	$item_data["total_product"]= $ary[$i][13];
    $data[$ary[$i][1]] = $item_data;
	
 
}
$rev_range= array_reverse($range); 
$result["CCCSortedString"] = $CCCSortedStringV1;
$result["title"] = $title;
$result["data"] = $data;
$result["range"] = $rev_range;
$result["total"] = sizeof($ary);
 
 
 
 
 

 
 
?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="./assets/Chart.min.js"></script>
<br/>
<div class="container">
	<h2>目前所選商品類別為<?php  echo  $category_list[$p_category] ;?></h2>
	<br/>
	<div class="dropdown">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
			選擇商品類別
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<?php
				foreach($category_list as $k => $v){
					echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./product_created_permonth.php?product_category_id='.$k.'">'.$v.'</a></li>';
				}
			?>
		</ul>
	</div>
	<br/>
    <div id="chart">
        <canvas id="myChart" width="1000" height="400"></canvas>
    </div>
    <div id="table">

    </div>
	<script type="text/javascript">
		var colors = ["79,197,199","250,110,134","244,153,48","151,236,113","222,157,214","219,249,119","244,217,91"];


		function draw_chart(data){
			$("#myChart").remove();
			$("#chart").append('<canvas id="myChart" width="1000" height="400"></canvas>');
			var options = {
				bezierCurve: false,
					multiTooltipTemplate: "<%= datasetLabel %> <%= value %>"
			};
			var ctx = document.getElementById("myChart").getContext("2d");
			var myLineChart = new Chart(ctx).Line(data, options);
		};
		
		function chart_data_update(store_arr){
			var data = {labels: raw['range']}; 
			var datasets = [];
			for(var store in store_arr){
				var store_name = store_arr[store];
				
				var dataset = {
					label: store_name,
						fillColor: "rgba(" + colors[store%colors.length] + ",0.2)",
						strokeColor: "rgba(" + colors[store%colors.length] + ",1)",
						pointColor: "rgba(" + colors[store%colors.length] + ",1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(" + colors[store%colors.length] + ",1)",
						data: raw['data'][store_name]['product_created'],  
						labelColor: 'black',
						labelFontSize: '16'
				}
				datasets.push(dataset);
			};
			console.log(datasets);
			data['datasets'] = datasets;
			draw_chart(data);
			 
		};
		function store_checked_list() {
			var store_arr = [];
			$(".store_checkbox").each(function() {
				if ($(this).prop("checked") == true) {
					store_arr.push($(this).attr("store_name"));
				}
			});
 
			chart_data_update(store_arr);
		};
		function store_checkbox_change() {
			$(".store_checkbox").change(function() {
				store_checked_list();
			});
		};
		function create_discount_table(){
			 
			
			
			var table = '<table class="table table-striped" id="discount_table">\n    ';
			 
			table += 		'<tr>\n    <th>品牌</th>\n             <th>每月新上架商品數</th>\n';
			table += "";
			for (var store_key in raw['data']) {
				if (raw['data'].hasOwnProperty(store_key)) {
					table += '<tr>\n';
					table += '    <td>' + store_key + "</td>\n";
					//table += '    <td>' + raw['data'][store_key]['total_product'] + "</td>\n";
					table += '    <td> <input type="checkbox" class="store_checkbox" store_name="'+ store_key +'" id="' + store_key.replace(" ","_") + '"></td>\n';
					table += '</tr>\n';
				}
			}
			table += '</table>';
			$("#table").append(table);
		};
		var raw = <?php  echo json_encode($result); ?>;
 
		console.log(raw);
		create_discount_table();
		store_checkbox_change();
		$(".store_checkbox").prop('checked', true);
		store_checked_list();
 
	</script>
</div>
<?php
include_once("footer.php");
?>

 