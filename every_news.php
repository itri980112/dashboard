<script src="http://code.jquery.com/jquery-latest.js"></script>
<SCRIPT language="javascript">
	function save_keywords(tableID) {
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
		$.ajax({
			url:"./CCC_everyday_news_parameter.php",
			data:"MyKeyword="+final_keyword,
			type : "POST",
			beforeSend:function(){
				//alert('before send');
				//beforeSend 發送請求之前會執行的函式
			},
			success:function(msg){
				alert('succeed!');
				//alert(msg);
			},
			error:function(xhr){
				alert('error!');
				alert(xhr);
			},
			complete:function(){
			   //alert('Ajax request 結束');
			}
		}); 
		//location.href="./CCC_everyday_news_parameter.php?MyKeyword="+MyKeyword;
	}
	
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
$list_query="select keyword from every_day_keywords ";
$list1 = mysql_query($list_query,$conn) or die('MySQL query error '.mysql_error().' '.$list_query);
 /*
while($row = mysql_fetch_array($list1)){
 
    $category_list[$row[0]] = $row[1];
}
*/
?>
 
    <div class="row">
		<div class='col-md-12'>
            <div class="form-group">
                 
				<label>本介面將設定新聞關鍵字，系統將在每日12:30與18:30定時抓取與關鍵字相關的最近兩天新聞</label>
 
            </div>
        </div>
 
    </div>
 
	<div id="error_msg"></div>
	<INPUT type="button" class="btn btn-primary" value="新增" onclick="addRow('dataTable')" />
 
    <INPUT type="button" class="btn btn-danger" value="刪除" onclick="deleteRow('dataTable')" />
	<INPUT type="button" class="btn btn-default" value="設定" onclick="save_keywords('dataTable')" />
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
include_once("./footer.php");
?>
