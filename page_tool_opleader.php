<?php
include_once("./header.php");
include_once("./commonlib.php");
$p_FB_page_id =  $_GET['FB_page_id'];
 
$conn = getConnection("fb_page");
$list_query="select id,name from pages order by new_fans desc";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
while($row = mysql_fetch_array($list1)){
 
    $pages_list[$row[0]] = $row[1];
}

if (empty($p_FB_page_id)==1){ 
	$SQL_query="SELECT comments.user_id, comments.user_name, SUM(10) AS 'score'  FROM comments where page_id='135084993203916'  GROUP BY comments.user_id ORDER BY score DESC limit 50";
    $page_str='BabyHome寶貝家庭親子網';
}else{
	$SQL_query="SELECT comments.user_id, comments.user_name, SUM(10) AS 'score'  FROM comments where page_id='".$p_FB_page_id ."'  GROUP BY comments.user_id ORDER BY score DESC  limit 50";
	 
	$page_str=$pages_list[$p_FB_page_id] ;
 
}
$result = mysql_query($SQL_query,$conn) or die('MySQL query error '.mysql_error().' '.$SQL_query);
$ary=array();
while($row = mysql_fetch_array($result))
$ary[]=$row;

mysql_free_result($result);
 
?>
<div class="container">
	<div style="font-size:20px">
		<a href="./page_tool.php">粉絲團工具選單</a>
		<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
		<a href="./page_tool_opleader.php">粉絲團意見領袖</a>
	</div>
	<hr>
	<script src="./assets/d3.min.js"></script> 
	<script src="./assets/d3pie.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
 
	<div class="dropdown">
		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
			選擇粉絲團
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<?php
				foreach($pages_list as $k => $v){
					echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./page_tool_opleader.php?FB_page_id='.$k.'">'.$v.'</a></li>';
				}
			?>
		</ul>
	</div>

    <div id="pieChart"></div>
    <div id="user_list">
         
		<table id="tbl" class='table table-striped table-condensed table-hover' >
			<thead><tr class='info'>
					<th>Rank</th><th>Name</th>
					<th>Rank</th><th>Name</th>
					<th>Rank</th><th>Name</th>
					<th>Rank</th><th>Name</th>
					<th>Rank</th><th>Name</th>
				</tr>
			</thead> 
			<tbody id='jaredtbl'>
			</tbody>
		</table>
	</div>
	<script>
 
		var raw = <?php  echo json_encode($ary); ?>;
 
		var raw_ary = [];
		for(var i1=0;i1<50; i1++){
			var temp_elem = raw[i1];
			console.log('ff');
			console.log(temp_elem);
			if(!temp_elem){ 
				break;
			}
				
			var dataset = {
				label:temp_elem['user_name'],
				value:+temp_elem['score']
			}
			raw_ary.push(dataset);
		} 
 
			 
	
	
		var user_data=[];
		var page_text='<?php  echo $page_str; ?>';  
 
		while(user_data.length){
			user_data.pop();
		}
 
		var page=$("#myselect").val(); 

		 
		
		
		$("#pieChart").empty(); 

		var data1 = {
				"sortOrder": "value-desc",
					"content": raw_ary
		};

		drawPie(data1);
	 

		function drawPie(data) {
			 
			console.log(777);
			console.log(data);
			var pie = new d3pie("pieChart", {
				"header": {
					"title": {
						"text": page_text+"\n",
						"color": "#3a4edc",
						"fontSize": 34,
						"font": "courier"
					},
					"subtitle": {
						"color": "#999999",
						"fontSize": 10,
						"font": "courier"
					},
					"location": "pie-center",
					"titleSubtitlePadding": 10
				},
				"footer": {
					"color": "#999999",
					"fontSize": 10,
					"font": "open sans",
					"location": "bottom-left"
				},
				"size": {
					"canvasHeight": 700,
					"canvasWidth": 800,
					"pieInnerRadius": "91%",
					"pieOuterRadius": "89%"
				},
				"data": data,
				"labels": {
					"outer": {
						"format": "label-value1",
						"pieDistance": 20
					},
					"inner": {
						"format": "none"
					},
					"mainLabel": {
						"fontSize": 11
					},
					"percentage": {
						"color": "#999999",
						"fontSize": 11,
						"decimalPlaces": 0
					},
					"value": {
						"color": "#cccc43",
						"fontSize": 11
					},
					"lines": {
						"enabled": true,
						"color": "#777777"
					}
				},
				"effects": {
					"pullOutSegmentOnClick": {
						"effect": "linear",
							"speed": 400,
							"size": 8
					}
				},
				"misc": {
					"colors": {
						"segmentStroke": "#000000"
					}
				}
			});

			$("#links").show();
			 
			$("#jaredtbl").show();
			$("#tbl").show(); 
		};
		 

	$( document ).ready(function() {
		// $("button").click();
		$("#jaredtbl").hide();
		$("#tbl").hide();
	});
    </script>
</div>
<?php
include_once("footer.php");
?>

 