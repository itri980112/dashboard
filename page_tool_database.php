<?php
include_once("./header.php");
$conn=getConnection('fb_page');
$query_pages='select id,name,oldest_datetime,latest_datetime from pages';
$pages = mysql_query($query_pages,$conn) or die('MySQL query error '.mysql_error().' '.$query_pages);
$record_count = 0;
while($row = mysql_fetch_array($pages)){
    $page_id_arr[$record_count] = $row[0];
    $page_name_arr[$record_count] = $row[1];
    $oldest_datetime_arr[$record_count] = $row[2];
    $latest_datetime_arr[$record_count] = $row[3];
    $record_count ++;
    }
$query_posts='select id,page_id from posts';
$posts = mysql_query($query_posts,$conn) or die('MySQL query error '.mysql_error().' '.$query_posts);
while($row = mysql_fetch_array($posts)){
    $group_posts_arr[$row[1]] ++;
}
?>
<br>
<div style="font-size:20px"><a href="./page_tool.php">工具選單</a> <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> <a href="./page_tool_database.php">資料庫管理</a></div>
<hr>
<h1>[<?php echo '共'.$record_count.'個粉絲團';?>][<?php echo '共'.mysql_num_rows($posts).'篇文章';?>]</h1>
<table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <!--<th style = "width:200px">#</th>!-->
                <th>名稱</th>
                <th class="col-md-1">文章數量</th>
                <th class="col-md-2">文章最舊時間</th>
                <th class="col-md-2">文章最新時間</th>
            </tr>
        </thead>
        <tbody>
                <?php  
                    for ($i = 0; $i <= $record_count-1; $i++) 
                    {
                        echo '<tr>';
                        echo '<td><a href="https://www.facebook.com/'.$page_id_arr[$i].'">'.$page_name_arr[$i].'</a></td>';
                        #echo '<td>'.$page_about_arr[$i].'</td>';
                        echo '<td>'.number_format($group_posts_arr[$page_id_arr[$i]], 0, '.' ,',').'</td>';
                        #echo '<td>'.number_format($page_fans_arr[$i], 0, '.' ,',').'</td>';
                        #echo '<td>'.number_format($page_posts_arr[$i], 0, '.' ,',').'</td>';
                        #echo '<td>'.number_format($page_likes_arr[$i], 0, '.' ,',').'</td>';
                        #echo '<td>'.number_format($page_comments_arr[$i], 0, '.' ,',').'</td>';
                        #echo '<td>'.number_format($page_shares_arr[$i], 0, '.' ,',').'</td>';
                        echo '<td>'.$oldest_datetime_arr[$i].'</td>';
                        echo '<td>'.$latest_datetime_arr[$i].'</td>';
                        echo '</tr>';
                    }
                    ?>
      
        </tbody>
    </table>
    <?php
include_once("./footer.php");
?>