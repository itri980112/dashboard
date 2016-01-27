<?php
include_once("./header.php");
include_once('./commonlib.php');
include_once("./page_list_sort.php");
?>
<?php
$sql="select page_id,page_name from pages";
#$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
#$row = mysql_fetch_array($result);
?>
<br>
<a href="./page_list.php">工具選單</a> <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> <a href="./page_monitor.php">社群監控(beta)</a>
<hr>
<form method="post" action="./page_monitor.php" id="mform" class="form-horizontal">
<!--panel-group-->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<!--panel1-->
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a style="text-decoration: none" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          粉絲團選擇
        </a>
        &nbsp;
        &nbsp;
        <label><input type="checkbox" class="check" id="checkAll">&nbsp;選擇全部</label>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      	<div class="btn-group" role="group" aria-label="...">
		<button href="#c_likes" role="button" class="btn btn-success btn-large" aria-controls="c_likes" data-toggle="tab">依本月按讚增加數排序</button>
		<button href="#c_comments" role="button" class="btn btn-warning btn-large" aria-controls="c_comments"  data-toggle="tab">依本月評論增加數排序</button>
		<button href="#c_shares" role="button" class="btn btn-danger btn-large" aria-controls="c_shares"  data-toggle="tab">依本月分享增加數排序</button>
	</div>
	<div id="myTabContent" class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="c_likes">
			<div class="checkbox">
                  	             <?php
	                  	foreach($month_likes as $k => $v){
	                  	$sql_name="select page_name from month_likes where page_id = '$k'";
			        $result_name = mysql_query($sql_name,$conn) or die('MySQL query error '.mysql_error().' '.$sql_name);
			        $row = mysql_fetch_array($result_name);
	                        echo '<label style="display : block"><input class="check" type="checkbox" name="page[]" value="'.$k.'">'.$row[0].'('.$v.')</label>';
	                    }
	                  ?>
                		</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="c_comments">
			<div class="checkbox">
                  	             <?php
	                  	foreach($month_comments as $k => $v){
	                  	$sql_name="select page_name from month_comments where page_id = '$k'";
			        $result_name = mysql_query($sql_name,$conn) or die('MySQL query error '.mysql_error().' '.$sql_name);
			        $row = mysql_fetch_array($result_name);
	                        echo '<label style="display : block"><input class="check" type="checkbox" name="page[]" value="'.$k.'">'.$row[0].'('.$v.')</label>';
	                    }
	                  ?>
                		</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="c_shares">
			<div class="checkbox">
                  	             <?php
	                  	foreach($month_shares as $k => $v){
	                  	$sql_name="select page_name from month_shares where page_id = '$k'";
			        $result_name = mysql_query($sql_name,$conn) or die('MySQL query error '.mysql_error().' '.$sql_name);
			        $row = mysql_fetch_array($result_name);
	                        echo '<label style="display : block"><input class="check" type="checkbox" name="page[]" value="'.$k.'">'.$row[0].'('.$v.')</label>';
	                    }
	                  ?>
                	</div>
		</div>
      	</div>
    </div>
  </div>
</div>
<!--panel2-->
   <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a style="text-decoration: none" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          參數選擇
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <label>按讚數超過<input type="value" name="like" class="form-control" placeholder="Min Likes Count"></label>
        <label>評論數超過<input type="value" name="comment" class="form-control" placeholder="Min Comments Count"></label>
        <label>分享數超過<input type="value" name="share" class="form-control" placeholder="Min Shares Count"></label>
        <label>文章顯示數量<input type="value" name="post" class="form-control" placeholder="Posts Show Count"></label>
      </div>
    </div>
  </div>
<!--panel3-->
   <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a style="text-decoration: none" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          時間選擇
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
          時間軸施工中...
      </div>
    </div>
  </div>
<!--panel4-->
   <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a style="text-decoration: none" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          排序選擇
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
          <div class="radio">
            <label><input type="radio" name="optionsRadios" id="optionsRadios1" name="sort" value="post_likes">依按讚數排序</label>
            <label><input type="radio" name="optionsRadios" id="optionsRadios2" name="sort" value="post_comments">依評論數排序</label>
            <label><input type="radio" name="optionsRadios" id="optionsRadios3" name="sort" value="post_shares">依分享數排序</label>
            <label><input type="radio" name="optionsRadios" id="optionsRadios4" name="sort" value="post_date">依時間排序</label>
          </div>
      </div>
    </div>
  </div>
</div>
<!--panel-group-->
<button type="submit" class="btn btn-primary">秀出動態牆</button>
</form>
<!--form-->
<?php
include_once("footer.php");
?>