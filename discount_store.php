<?php
include_once("./header.php");
?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="./assets/Chart.min.js"></script>
<br/>
<div class="container">
    <div id="chart">
        <canvas id="myChart" width="1000" height="400"></canvas>
    </div>
    <div id="table">

    </div>
</div>
	<script type="text/javascript">
		var colors = ["79,197,199","250,110,134","244,153,48","151,236,113","222,157,214","219,249,119","244,217,91"];
		function store_checked_list() {
			var store_arr = [];
			//console.log('g1');//CCC
			//console.log($(".store_checkbox"));//CCC
			
			$(".store_checkbox").each(function() {
				
				if ($(this).prop("checked") == true) {
					store_arr.push($(this).attr("store_name"));
					//console.log('g');
					//console.log(store_arr);
				}
			});
			chart_data_update(store_arr);
		};

		function store_checkbox_change() {
			$(".store_checkbox").change(function() {
				store_checked_list();
			});
		};
		

		function chart_data_update(store_arr){
			var data = {labels: raw['discount_ch']};
			//console.log(data);
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
						data: raw['data'][store_name]['discount'],
						labelColor: 'black',
						labelFontSize: '16'
				}
				datasets.push(dataset);
			};
			console.log('hehe');
			console.log(datasets);
			data['datasets'] = datasets;
			draw_chart(data);
		};
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
		//CCC:create_discount_table本質上是在組建一個字串，然後append(實際上是塞)到HTML tag裡面
		function create_discount_table(){
			/*CCC:class="table table-striped"是boostrap的東西
			*/
			var table = '<table class="table table-striped" id="discount_table">\n    ';
			table += 		'<tr>\n    <th>品牌</th>\n     <th>折價商品數</th>\n    <th>全部商品數</th>\n    <th>折價分佈圖表</th>\n';
			table += "";
			for (var store_key in raw['data']) {
				//CCC:store_key是商店的名稱，例如"next"字串
				if (raw['data'].hasOwnProperty(store_key)) {
					/*CCC:raw['data']是一個javascript物件，
					假設raw['data']是{"next","test"}的javascript物件，
					raw['data'].hasOwnProperty("next")回傳true
					*/
					table += '<tr>\n';
					table += '    <td>' + store_key + "</td>\n";
					table += '    <td>' + raw['data'][store_key]['discount_product'] + "</td>\n";
					table += '    <td>' + raw['data'][store_key]['total_product'] + "</td>\n";
					table += '    <td> <input type="checkbox" class="store_checkbox" store_name="'+ store_key +'" id="' + store_key.replace(" ","_") + '"></td>\n';
					table += '</tr>\n';
				}else{
					//alert('test');
				}
			}
			table += '</table>';
			$("#table").append(table);
		};
		$.get("http://220.132.97.119/dashboard/discount_json_store.php", function(response) {
			raw = $.parseJSON(response);
			console.log(raw);
			create_discount_table();
			store_checkbox_change();
			$(".store_checkbox").prop('checked', true);
			store_checked_list();
		})
	</script>


<?php
include_once("footer.php");

/*
array(4) {
	["title"]=> array(17) {
		[285801]=> string(4) "next"
		[285803]=> string(4) "momo"
		[744072]=> string(9) "百事特"
		[744088]=> string(7) "smalife"
		[2919]=> string(9) "麗嬰房"
		[285799]=> string(10) "littlemoni"
		[744092]=> string(6) "Uniqlo"
		[285689]=> string(12) "Peter Rabbit"
		[285688]=> string(6) "奇哥"
		[285697]=> string(3) "PUP"
		[285693]=> string(9) "BabyBjorn"
		[285690]=> string(7) "absorba"
		[285695]=> string(10) "STOKKE™ "
		[285696]=> string(8) "Suavinex"
		[285692]=> string(4) "Joie"
		[285691]=> string(14) "Classic Mickey"
		[285694]=> string(9) "Pegperego"
	}
	["data"]=> array(17) {
		["next"]=> array(3) {
			["total_product"]=> string(5) "67809"
			["discount_product"]=> string(1) "0"
			["discount"]=> array(9) {
				[0]=> string(1) "0"
				[1]=> string(1) "0"
				[2]=> string(1) "0"
				[3]=> string(1) "0"
				[4]=> string(1) "0"
				[5]=> string(1) "0"
				[6]=> string(1) "0"
				[7]=> string(1) "0"
				[8]=> string(1) "0"
			}
		}
		["momo"]=> array(3) { ["total_product"]=> string(4) "6065" ["discount_product"]=> string(4) "5898" ["discount"]=> array(9) { [0]=> string(1) "2" [1]=> string(2) "14" [2]=> string(1) "6" [3]=> string(2) "13" [4]=> string(2) "44" [5]=> string(2) "68" [6]=> string(2) "87" [7]=> string(3) "143" [8]=> string(4) "5218" } } ["百事特"]=> array(3) { ["total_product"]=> string(4) "3931" ["discount_product"]=> string(4) "3183" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(2) "12" [2]=> string(1) "4" [3]=> string(3) "161" [4]=> string(3) "785" [5]=> string(3) "743" [6]=> string(3) "168" [7]=> string(3) "497" [8]=> string(3) "640" } } ["smalife"]=> array(3) { ["total_product"]=> string(4) "2229" ["discount_product"]=> string(4) "2217" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "6" [2]=> string(1) "0" [3]=> string(3) "858" [4]=> string(3) "814" [5]=> string(2) "38" [6]=> string(3) "436" [7]=> string(2) "19" [8]=> string(2) "22" } } ["麗嬰房"]=> array(3) { ["total_product"]=> string(4) "2151" ["discount_product"]=> string(4) "1854" ["discount"]=> array(9) { [0]=> string(1) "7" [1]=> string(2) "65" [2]=> string(3) "371" [3]=> string(3) "116" [4]=> string(2) "39" [5]=> string(2) "88" [6]=> string(3) "409" [7]=> string(3) "244" [8]=> string(3) "180" } } ["littlemoni"]=> array(3) { ["total_product"]=> string(4) "1243" ["discount_product"]=> string(3) "952" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "4" [3]=> string(2) "16" [4]=> string(1) "3" [5]=> string(2) "50" [6]=> string(3) "177" [7]=> string(3) "252" [8]=> string(3) "119" } } ["Uniqlo"]=> array(3) { ["total_product"]=> string(3) "598" ["discount_product"]=> string(1) "0" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "0" [7]=> string(1) "0" [8]=> string(1) "0" } } ["Peter Rabbit"]=> array(3) { ["total_product"]=> string(3) "304" ["discount_product"]=> string(3) "303" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "3" [7]=> string(3) "176" [8]=> string(2) "34" } } ["奇哥"]=> array(3) { ["total_product"]=> string(3) "224" ["discount_product"]=> string(3) "224" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(2) "10" [7]=> string(2) "13" [8]=> string(1) "1" } } ["PUP"]=> array(3) { ["total_product"]=> string(3) "197" ["discount_product"]=> string(3) "197" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(2) "12" [5]=> string(2) "64" [6]=> string(2) "37" [7]=> string(2) "15" [8]=> string(1) "2" } } ["BabyBjorn"]=> array(3) { ["total_product"]=> string(3) "102" ["discount_product"]=> string(2) "95" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "8" [7]=> string(1) "0" [8]=> string(1) "0" } } ["absorba"]=> array(3) { ["total_product"]=> string(2) "70" ["discount_product"]=> string(2) "70" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "2" [7]=> string(2) "41" [8]=> string(1) "0" } } ["STOKKE™ "]=> array(3) { ["total_product"]=> string(2) "61" ["discount_product"]=> string(2) "61" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "0" [7]=> string(1) "0" [8]=> string(1) "0" } } ["Suavinex"]=> array(3) { ["total_product"]=> string(2) "40" ["discount_product"]=> string(2) "40" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "0" [7]=> string(1) "0" [8]=> string(1) "0" } } ["Joie"]=> array(3) { ["total_product"]=> string(2) "36" ["discount_product"]=> string(2) "36" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "4" [7]=> string(2) "29" [8]=> string(1) "0" } } ["Classic Mickey"]=> array(3) { ["total_product"]=> string(2) "13" ["discount_product"]=> string(2) "13" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "0" [7]=> string(1) "6" [8]=> string(1) "1" } } ["Pegperego"]=> array(3) { ["total_product"]=> string(1) "2" ["discount_product"]=> string(1) "2" ["discount"]=> array(9) { [0]=> string(1) "0" [1]=> string(1) "0" [2]=> string(1) "0" [3]=> string(1) "0" [4]=> string(1) "0" [5]=> string(1) "0" [6]=> string(1) "0" [7]=> string(1) "0" [8]=> string(1) "2" } } 
	}
	["discount_ch"]=> array(9) {
		[0]=> string(12) "低於一折"
		[1]=> string(6) "二折"
		[2]=> string(6) "三折"
		[3]=> string(6) "四折"
		[4]=> string(6) "五折"
		[5]=> string(6) "六折"
		[6]=> string(6) "七折"
		[7]=> string(6) "八折"
		[8]=> string(6) "九折" 
	} ["total"]=> int(17)
}
*/

?>

