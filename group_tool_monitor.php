<?php
include_once("./header.php");
$conn=getConnection('fb_group');
$sql="select id,name from groups";
$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
$row = mysql_fetch_array($result);

if (empty($_POST["page"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
    $page_id = array(0);
	$page_id[0]="530458443691498";
}else{
	$page_id = $_POST['page'];
}
if (empty($_POST["like"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
    $like = 0;
}else{
	$like = $_POST['like'];
}
if (empty($_POST["comment"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
    $comment = 0;
}else{
	$comment = $_POST['comment'];
}
if (empty($_POST["share"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
    $share = 0;
}else{
	$share = $_POST['share'];
}
if (empty($_POST["post"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
    $post = 5;
}else{
	$post = $_POST['post'];
}
if (empty($_POST["sort"])==1){
	$sort = "likes";
}else{
	$sort = $_POST['sort'];
}
/*
echo ' c ';
var_dump($page_id);
echo ' c  ';
var_dump($comment);
echo ' a  ';
var_dump($like);
echo ' a  ';
var_dump($share);
echo ' a  ';
var_dump($post);
echo ' a  ';
 var_dump($sort);
*/
?>
<br>
<div style="font-size:20px">
    <a href="./group_tool.php">社團工具選單</a>
	<span class="glyphicon glyphicon-arrow-right" aria-hidden="true">
	</span> <a href="./group_tool_monitor.php">社團社群監控</a></div>
<hr>
<form action="./group_tool_monitor.php" method="post" id="mform" class="form-horizontal">
<!--panel-group-->
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<!--panel1-->
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a style="text-decoration: none" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						社團選擇
					</a>
					&nbsp;
					&nbsp;
					<label><input type="checkbox" class="check" id="checkAll">&nbsp;選擇全部</label>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<div class="checkbox">

					<?php
						while($row = mysql_fetch_array($result)){
							echo '<label style="display : block"><input class="check" type="checkbox" name="page[]" value="'.$row[0].'">'.$row[1].'</label>';
						}
					?>
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
	<!--
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
		-->
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
	<button type="submit" class="btn btn-primary">秀出動態牆</button>
</form>
<!--form-->

<div class="well well-sm">動態牆 : 您可以透過以下區塊了解所有粉絲團最新與最熱門之動態文章</div>

<?php
/*
SQL:
select id,page_id from posts 
where (date between '2015-12-01' and '2015-12-04') AND
 page_id IN (209251989898) AND likes >= 0 AND comments >= 0 AND
 shares >= 0 order by likes DESC limit 5
*/

	$query_str = "select id,group_id,message,likes,shares,comments from feeds where (date between '".date("Y-m-01")."' and '".date("Y-m-d")."') AND group_id IN (";
	foreach($page_id as $id){
		$str = $str.$id.",";
	}
	$str = rtrim($str, ",");
	#echo $str;
	$query_str = $query_str.$str;
	$query_str = $query_str.") AND likes >= ".$like." AND comments >= ".$comment." AND shares >= ".$share." order by ".$sort." DESC limit ".$post;
	//echo 'FFF_';
	//echo  $query_str;
	//echo '_LLL';
	$sql_hot_posts = $query_str;
	
	$result_hot_posts = mysql_query($sql_hot_posts,$conn) or die('MySQL query error '.mysql_error().' '.$sql_hot_posts);
	 echo '<table class="table table-striped table-bordered table-hover">';
	 echo '<thead><tr><th>#</th><th>標題</th><th>按讚數</th><th>分享數</th><th>評論數</th><th>連結</th></tr></thead>';
	 echo '<tbody>';
	 $numb_temp=1;
	 while($row = mysql_fetch_array($result_hot_posts)){
		 $temp_message='';
		 
		 if(strlen ( $row[2])>140){
			$temp_message=substr( $row[2] , 0, 140 ).'......';
		 }else{
			 $temp_message=$row[2];
		 }
		echo '<tr><td>'.$numb_temp.'</td><td>'.$temp_message.'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td><a href="https://www.facebook.com/'.$row[0].'">連結<a/></td></tr>';
		//echo '<div class="fb-post" data-href="https://www.facebook.com/'.$row[1].'/posts/'.$row[0].'" data-width="500"></div>';
		$numb_temp++;
	}
	echo '</tbody>';
	echo '</table>';
	
?>
<!--
<table>
 <tr><td><a href="https://www.facebook.com/209251989898_10154397616144899">test<a/></td> </tr>
</table>
-->
<script>
$("#checkAll").click(function () {
    $(".check").prop('checked', $(this).prop('checked'));
});
</script>
<script src="http://connect.facebook.net/zh_TW/sdk.js"></script> 
<script>
/*
	window.fbAsyncInit = function() {
		FB.init({
		appId      : '929363660479873',
		xfbml      : true,
		version    : 'v2.4' // or v2.0, v2.1, v2.2, v2.3
		});
	};

	(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/zh_TW/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	*/
</script>
<?php
include_once("./footer.php");
?>

