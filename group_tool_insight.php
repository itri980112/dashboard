<?php
include_once("./header.php");
$conn = getConnection('fb_group');
$sort_query = "select likes,comments,shares,group_id from feeds where (date between '".date("Y-m-01")."' and '".date("Y-m-d")."')";
$sort = mysql_query($sort_query,$conn) or die('MySQL query error '.mysql_error().' '.$sort_query);
while($row = mysql_fetch_array($sort)){
	$month_likes[$row[3]] = $month_likes[$row[3]] + $row[0];
	$month_comments[$row[3]] = $month_comments[$row[3]] + $row[1];
	$month_shares[$row[3]] = $month_shares[$row[3]] + $row[2];
//    print_r($row);
}
arsort($month_likes);
arsort($month_comments);
arsort($month_shares);
?>
<br>
<div style="font-size:20px">
	<a href="./group_tool.php">社團工具選單</a>
	<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
	<a href="./group_tool_insight.php">社團個別洞察分析</a></div>
<hr>
<div class="bs-example bs-example-tabs" role="tabpanel">
	<div class="btn-group" role="group" aria-label="...">
		<button href="#likes"    id="CCClikes"    onClick="reply_click(this);" role="button" class="btn btn-success btn-large" aria-controls="likes" data-toggle="tab">依本月按讚增加數排序</button>
		<button href="#comments" id="CCCcomments" onClick="reply_click1(this);" role="button" class="btn btn-warning btn-large" aria-controls="comments"  data-toggle="tab">依本月評論增加數排序</button>
		<button href="#shares"   id="CCCshares"   onClick="reply_click2(this);" role="button" class="btn btn-danger btn-large" aria-controls="shares"  data-toggle="tab">依本月分享增加數排序</button>
	</div>
	<div id="myTabContent" class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="likes">
        		<br>
	        	<?php
		foreach($month_likes as $k => $v)
		{
		        	$likes_sort_query ="select name from groups where id = '$k'";
		        	$likes_sort = mysql_query($likes_sort_query,$conn) or die('MySQL query error '.mysql_error().' '.$likes_sort_query);
		        	$row = mysql_fetch_array($likes_sort);
		       	echo '<div class="col-sm-3 col-md-2">';
		        		echo '<div class="thumbnail">';
				        	echo '<a href="./group_insight.php?id='.$k.'" class="thumbnail">';
				        		echo '<img style="height:200px" data-src="holder.js/158x105" src="./assets/images/'.$k.'.jpg" alt="...">';
				        	echo '</a>';
				        	echo '<div style="height:40px">';
					        	echo '<h4 style="text-align:center"><b>'.$row[0].'</b></h4>';
					echo '</div>';
					echo '<div style="height:20px">';
					        	echo '<h5 style="text-align:center">本月增加 '.number_format($v, 0, '.' ,',').' 個按讚數</h5>';
					echo '</div>';
		        		echo '</div>';
		        	echo '</div>';      
	    	}
		?>
 		</div>
        		<div role="tabpanel" class="tab-pane" id="comments">
        		<br>
	        	<?php
		foreach($month_comments as $k => $v)
		{
			$comments_sort_query ="select name from groups where id = '$k'";
			$comments_sort = mysql_query($comments_sort_query,$conn) or die('MySQL query error '.mysql_error().' '.$comments_sort_query);
			$row = mysql_fetch_array($comments_sort);
			echo '<div class="col-sm-3 col-md-2">';
			        	echo '<div class="thumbnail">';
				        	echo '<a href="./group_insight.php?id='.$k.'" class="thumbnail">';
				        		echo '<img style="height:200px" data-src="holder.js/158x105" src="./assets/images/'.$k.'.jpg" alt="...">';
				        	echo '</a>';
				        	echo '<div style="height:40px">';
					        	echo '<h4 style="text-align:center"><b>'.$row[0].'</b></h4>';
					echo '</div>';
					echo '<div style="height:20px">';
					        	echo '<h5 style="text-align:center">本月增加 '.number_format($v, 0, '.' ,',').' 個評論數</h5>';
					echo '</div>';
			        	echo '</div>';
			echo '</div>';
	   	}
	 	?>
        		</div>
        		<div role="tabpanel" class="tab-pane" id="shares">
        		<br>
	        	<?php
		foreach($month_shares as $k => $v)
		{
		    	$shares_sort_query ="select name from groups where id = '$k'";
		        	$shares_sort = mysql_query($shares_sort_query,$conn) or die('MySQL query error '.mysql_error().' '.$shares_sort_query);
		        	$row = mysql_fetch_array($shares_sort);
		        	echo '<div class="col-sm-3 col-md-2">';
		        		echo '<div class="thumbnail">';
				        	echo '<a href="./group_insight.php?id='.$k.'" class="thumbnail">';
				        		echo '<img style="height:200px" data-src="holder.js/158x105" src="./assets/images/'.$k.'.jpg" alt="...">';
				        	echo '</a>';
				        	echo '<div style="height:40px">';
					        	echo '<h4 style="text-align:center"><b>'.$row[0].'</b></h4>';
					echo '</div>';
					echo '<div style="height:20px">';
					        	echo '<h5 style="text-align:center">本月增加 '.number_format($v, 0, '.' ,',').' 個分享數</h5>';
					echo '</div>';
		        		echo '</div>';
		        	echo '</div>';
	   	}
	 	?>
        		</div>
	</div>
</div>
<script type="text/javascript">
function reply_click(d)
{
     d.style.border  = '3px black dashed';
	 document.getElementById('CCCcomments').style.border  = '';
	 document.getElementById('CCCshares').style.border  = '';
}
function reply_click1(d)
{
     document.getElementById('CCClikes').style.border  = '';
	 d.style.border  = '3px black dashed';
	 document.getElementById('CCCshares').style.border  = '';
}
function reply_click2(d)
{
     document.getElementById('CCClikes').style.border  = '';
	 document.getElementById('CCCcomments').style.border  = '';
	 d.style.border  = '3px black dashed';
}
</script>
<?php
include_once("./footer.php");
?>
