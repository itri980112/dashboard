<?php

 
include_once("./header.php");

include_once("./do_background_work.php");
 
 

?>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script language="javascript">
function execWork(){
	//alert('ready!!!');
 
    var MyKeyword = $('#MyKeyword').val();
 
    if(MyKeyword==""){
        $('#error_msg').text('Please enter keyword');
        $('#MyKeyword').focus();
        return false;
    }else{
		 
 
		 
		$.ajax({
			url:"./do_background_work.php",
			data:"MyKeyword="+MyKeyword,
			type : "POST",
			beforeSend:function(){
				 
			},
			success:function(msg){
				$('#error_msg').text('the result:!'+msg);
				sleep(1000);
				$('#error_msg').text('the result11:!'+msg);
			},
			error:function(xhr){
				 
			},
			complete:function(){
			    
			}
		}); 
	}
	


};

</script>
 
 
    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <label>請指定關鍵字</label>
                <div class='input-group date' id='since_datetime'>
                <input type='text' class="form-control" id="MyKeyword" value=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
		<div class='col-md-12'>
            <button   class="btn btn-info btn-block" onclick='execWork();'>搜尋</button>
        </div>
    </div>
 
<div id="error_msg"></div>
<?php
include_once("./footer.php");
 
?>

