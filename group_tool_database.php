<?php
include_once("./header.php");
$conn=getConnection('fb_group');
$query_groups ='select id,name,privacy,oldest_feed_time,latest_feed_time from groups';
$groups = mysql_query($query_groups,$conn) or die('MySQL query error '.mysql_error().' '.$query_groups);
$record_count = 0;
while($row = mysql_fetch_array($groups)){
    $group_id_arr[$record_count] = $row[0];
    $group_name_arr[$record_count] = $row[1];
    $group_privacy_arr[$record_count] = $row[2];
    $group_oldest_feed_time_arr[$record_count] = $row[3];
    $group_latest_feed_time_arr[$record_count] = $row[4];
    $record_count = $record_count  + 1;
    }
$query_feeds='select id,group_id from feeds';
$feeds = mysql_query($query_feeds,$conn) or die('MySQL query error '.mysql_error().' '.$query_feeds);
while($row = mysql_fetch_array($feeds)){
    $group_feeds_arr[$row[1]] ++;
}
?>
<br>
<div style="font-size:20px"><a href="./group_tool.php">工具選單</a> <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> <a href="./group_tool_database.php">資料庫管理</a></div>
<hr>
<h1>[<?php echo '共'.$record_count.'個社團';?>][<?php echo '共'.mysql_num_rows($feeds).'篇討論串';?>]</h1>
<table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
                <!--<th style = "width:200px">#</th>!-->
                <th>名稱</th>
                <th>權限</th>
                <th class="col-md-1">討論串數量</th>
                <th class="col-md-2">討論串最舊時間</th>
                <th class="col-md-2">討論串最新時間</th>
            </tr>
        </thead>
        <tbody>
                <?php  
                    for ($i = 0; $i <= $record_count-1; $i++) 
                    {
                        echo '<tr>';
                        echo '<td><a href="https://www.facebook.com/'.$group_id_arr[$i].'">'.$group_name_arr[$i].'</a></td>';
                        echo '<td>'.$group_privacy_arr[$i].'</td>';
                        echo '<td>'.number_format($group_feeds_arr[$group_id_arr[$i]], 0, '.' ,',').'</td>';
                        echo '<td>'.$group_oldest_feed_time_arr[$i].'</td>';
                        echo '<td>'.$group_latest_feed_time_arr[$i].'</td>';
                        echo '</tr>';
                    }
                    ?>
      
        </tbody>
    </table>
    <?php
include_once("footer.php");
?>