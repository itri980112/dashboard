<script src="http://code.jquery.com/jquery-latest.js"></script>
<SCRIPT language="javascript">
    function addRow(tableID) {
		//<input type="checkbox" name="chk">
		//<input type="text" name="txt" value="">
        var table = document.getElementById(tableID);
		$("#dataTable").append('<tr><th><INPUT type="checkbox" name="chk"/></th><th><INPUT class="key1" type="text" name="txt" value=""/></th></tr>');
		 
	}
	function deleteRow(tableID) {
		try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[0].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
					if(rowCount <= 1) {
						alert("Cannot delete all the rows.");
						break;
					}
					table.deleteRow(i);
					rowCount--;
					i--;
				}


			}
		}catch(e) {
			alert(e);
		}
	}
 
</SCRIPT>
<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn = getConnection("news_db");
$list_query="select keyword from old_news_keywords ";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
?>
 
    <div class="row">
		<div class='col-md-6'>
            <div class="form-group">
                <label>起始日期</label>
                <div class='input-group date' id='since_datetime'>
                <input type='text' id='since_datetime1' class="form-control" name="start_date" value=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="form-group">
                <label>結束日期</label>
                <div class='input-group date' id='until_datetime'>
                <input type='text' id='until_datetime1' class="form-control" name="end_date" value=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
		<!--
		<div class='col-md-12'>
            <div class="form-group">
                <label>請指定關鍵字</label>
                <div class='input-group date' id='my_keyword'>
                <input type='text' class="form-control" id="MyKeyword" value=""/>
 
                </div>
            </div>
        </div>
		-->
		 
    </div>
 
	<div id="error_msg"></div>

	<INPUT type="button" class="btn btn-primary" value="新增" onclick="addRow('dataTable')" />
 
    <INPUT type="button" class="btn btn-danger" value="刪除" onclick="deleteRow('dataTable')" />
	<button id="btn_start" class="btn btn-default"   onclick="execWork('dataTable');" />   新聞擷取</button>
	<button  id="btn_stop" class="btn btn-info  " onclick='stopWork();'>停止</button>
    <br>
    <TABLE  class="table table-striped table-bordered table-hover"  border="1">
		<thead>
			<tr>
				<th>刪除選取</th>
				<th>keyword</th>
	 
			</tr>
		</thead>
		<tbody id="dataTable">
			
			<?php
 
//$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
			while($row = mysql_fetch_array($list1)){
				echo '<tr><th><INPUT type="checkbox" name="chk"/></th>';
				echo '<th><INPUT type="text" class="key1" name="txt"   value="'.$row[0].'"/></th></tr>';
			}
			?>
			<tr>
				<th><INPUT type="checkbox" name="chk"/></th>
				<th><INPUT type="text" class="key1" name="txt"   value=""/></th>
	 
			</tr>
		</tbody>
    </TABLE>
	

<?php
$conn = getConnection("news_db");
$sql="select status from crawler_task where id='1'";
$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
$row = mysql_fetch_array($result);
mysql_free_result($result);
if($row[0]=='running'){
	 
?>
<script language="javascript">
	document.getElementById('btn_start').disabled=true;
</script>
<?php
}else{
	 
?>
<script language="javascript">
	document.getElementById('btn_stop').disabled=true;
</script>
<?php
}
?>

<!-- -------------------------------------------------------- -->
<script type="text/javascript">
	$(function() {
		$('#since_datetime').datetimepicker(
			{
				sideBySide: true,
					format: 'YYYY/MM/DD',
			}
		);
		$('#until_datetime').datetimepicker(
			{
				sideBySide: true,
					format: 'YYYY/MM/DD',
			}
		);
		$("#since_datetime").on("dp.change", function(e) {
			$('#until_datetime').data("DateTimePicker").minDate(e.date);
		});
		$("#until_datetime").on("dp.change", function(e) {
			$('#since_datetime').data("DateTimePicker").maxDate(e.date);
		});
	});
</script>

<!-- --------------<script src="http://code.jquery.com/jquery-latest.js"></script>------------------------------------------ -->

 
<script src="./assets/js/collapse.js"></script>
<script src="./assets/js/transition.js"></script>
<script src="./assets/js/validator.js"></script>
<script src="./assets/js/moment.min.js"></script>
<script src="./assets/js/bootstrap-datepicker.js"></script>
<script src="./assets/js/bootstrap-datetimepicker.js"></script>

<script language="javascript">

function stopWork(){
		<?php
			$conn = getConnection("news_db");
			$sql = "UPDATE crawler_task SET  status='stop' WHERE id='1'";
			mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
			for ($sec1 = 0; $sec1 < 30; ++$sec1) {
				$sql="select status from crawler_task where id='1'";
				$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
				$row = mysql_fetch_array($result);
				mysql_free_result($result);
				sleep(1);
				if($row[0]=='stop'){
					break;
				}
			}
		?>
		document.getElementById('btn_start').disabled=false;
		document.getElementById('btn_stop').disabled=true;
		//$('#error_msg').text('抓取任務已經終止! ' );
};

function execWork(tableID){
	var key1s =$(".key1");
	var table = document.getElementById(tableID);
	var final_keyword='';
	for(var i = 0; i < key1s.length; i++){
	   //console.log(key1s[i].value);
	   if(i==0){
		   final_keyword=''+ key1s[i].value;
	   }else{
		   final_keyword=final_keyword+","+key1s[i].value;
	   }
	}
	console.log(final_keyword);
	
    
	var MyStart = $('#since_datetime1').val();
	var MyEnd = $('#until_datetime1').val();
 
    if(final_keyword=="" || MyStart=="" ||MyEnd==""){
        $('#error_msg').text('Please enter data');
        //$('#MyKeyword').focus();
        return false;
    }else{
		document.getElementById('btn_start').disabled=true;
		document.getElementById('btn_stop').disabled=false;
		 
 
		 
		$.ajax({
			url:"./CCC_back.php",
			 
			data:{"MyKeyword":final_keyword,"MyStart":MyStart,"MyEnd":MyEnd},
			type : "POST",
			beforeSend:function(){
			},
			success:function(msg){
				//console.log('msg');
				console.log(msg);
				 
			},
			error:function(xhr){
				document.getElementById('btn_stop').disabled=true;
				document.getElementById('btn_start').disabled=false;
				 
				console.log(' 發生錯誤');
				console.log(xhr);
			},
			complete:function(a){
				//alert(a);
			    
			}
		}); 
	}
};

</script>
<?php
 
include_once("./footer.php");
?>
