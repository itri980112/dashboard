<?php
include_once("./commonlib.php");
include_once("./simple_html_dom.php");//
if (empty($_POST["MyKeyword"])==1 || empty($_POST["MyStart"])==1|| empty($_POST["MyEnd"])==1){//CCC:不要用is_null來判斷post變數內容，用empty比較有效
	//echo '尚未指定關鍵字，關鍵字格式:key1';
}else{
	$MyStart=$_POST["MyStart"];
	$MyEnd=$_POST["MyEnd"];
	$MyKeyword=$_POST["MyKeyword"];
	ignore_user_abort(true);
	set_time_limit(0);
	
	if (function_exists('pcntl_fork')) {
		$pid = pcntl_fork();
		if ($pid === -1) {
			die('fork fail.');
			$myfile = fopen("./CCCError.txt", "a") or die("Unable to open file!");
			fwrite($myfile, sprintf("%s => work fail: \n", date('H:i:s')));
			fwrite($myfile, "work fail \n");
			fclose($myfile);
			echo 'pcntl_fork error!抓取工作未執行';
			
		} else if ($pid) {
			$is_true=FALSE;
			$myfile = fopen("./CCCError.txt", "a") or die("Unable to open file!");
			for ($sec1 = 0; $sec1 < 60; ++$sec1) {
				echo 'success!';
				fwrite($myfile, sprintf("%s => main thread waiting: \n", date("Y-m-d H:i:s",time())  ));
				sleep(1);
				fwrite($myfile,'\n\n child processid:'.$pid );
				fwrite($myfile,'\n\n my(father) pid:'.getmypid() );
				
				$conn = getConnection("news_db");
				$sql="select status from crawler_task where id='1'";
				$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
				$row = mysql_fetch_array($result);
				mysql_free_result($result);
				if($row[0]=='running'){
					$is_true=TRUE;
					fwrite($myfile,'工作狀態已從stop 轉為 running\n');
					echo 'success!';
					 
					break;
				}
			}
			if($is_true== FALSE){
				echo 'DB error!';
				
			}
			
			$status = null;
			 
			pcntl_waitpid($pid, $status);

              
			fwrite($myfile,'father exit()\n');
			fwrite($myfile,'killing process:'.$pid.'\n');
 
			passthru('kill -9 ' . getmypid());
			exec('kill -9 ' . getmypid());
			fclose($myfile);
			exit(0);

		} else {
			if(strlen($MyKeyword)>0  ){
				$conn = getConnection("news_db");
				$sql='TRUNCATE TABLE old_news_keywords';
				mysql_query($sql,$conn);
				$keyword_array=explode (",",$MyKeyword);
				for($i=0;$i<sizeof($keyword_array);$i++){
					if( strlen($keyword_array[$i])>0   ){
						$sql="INSERT INTO old_news_keywords (keyword) VALUES ('".$keyword_array[$i]."'   )"; //date("Y-m-d",strtotime($news_date))    //strip_tags($g_element->parent()->parent()->childNodes(1))
						mysql_query($sql,$conn);
					}
				}
	
				//$keyword_array=explode (",",$MyKeyword);
				for($i=0;$i<sizeof($keyword_array);$i++){
					if( strlen($keyword_array[$i])>0   ){
					 job('"'.$keyword_array[$i].'"',$MyStart ,$MyEnd);//V3
					}
				}
			}
			passthru('kill -9 ' . getmypid());
			exec('kill -9 ' . getmypid());
			exit(0);
			//V1  job('"'.$keyword_array[$i].'"',(string)$yest_minus1_str ,(string)$today_str);
			//V2  job('"'.$MyKeyword.'"',$MyStart ,$MyEnd);
		}
	} else {
		echo 'server不支援pcntl_fork ，請調整server設定';
	}
}

function DB_stop_task($handle){
	$conn = getConnection("news_db");

	$sql = "UPDATE crawler_task SET  status='stop' WHERE id='1'";
	if (mysql_query($sql,$conn)) {
		 
	} else {
		 fwrite($handle, 'DB_stop_task SQL ERROR! \n');
		fclose($handle);
		//exit(0);
	}
}
function DB_start_task($MyKeyword,$handle){
	$conn = getConnection("news_db");
	$sql = "UPDATE crawler_task SET  `status`='running',`task_start_time` ='". date("Y-m-d H:i:s",time())."' WHERE id='1'";
	
	if (mysql_query( $sql,$conn)) {
	} else {
		fwrite($handle, 'DB_start_task SQL ERROR! \n');
		fclose($handle);
		//exit(0);
	}
}
function job($MyKeyword,$MyStart ,$MyEnd) {
	$handle = fopen('./CCCnewfile1.txt', 'a');
	fwrite($handle,'\n\n my(child) pid:'.getmypid() );

	DB_start_task($MyKeyword,$handle);
	$encoded_keyword=urlencode($MyKeyword);
	$num_of_article=0;
    
    while (true) {
		try{
			$conn = getConnection("news_db");
			$sql="select status from crawler_task where id='1'";
			$result = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
			$row = mysql_fetch_array($result);
			mysql_free_result($result);
			if($row[0]=='stop' || $num_of_article>100){
				DB_stop_task($handle);
				fwrite($handle,'已經抓取'.$num_of_article.'筆資料，結束正常工作\n');
				break;
			}
			
			fwrite($handle, sprintf("logtime: %s =>  %s\n", date('Y-m-d  H:i:s'), $MyKeyword));

			$temp_search_url='https://www.google.com.tw/search?q='.$encoded_keyword.'&es_sm=122&biw=960&bih=506&tbs=cdr:1,cd_min:'.$MyStart.',cd_max:'.$MyEnd.'&tbm=nws&start='.$num_of_article.'&sa=N&dpr=1.5&gws_rd=ssl';

			fwrite($handle, sprintf("temp_url:   %s\n",   $temp_search_url));
			$rand_sec=rand(1,20);
			sleep(20+$rand_sec);
			$curl=curl_init();
			curl_setopt($curl,CURLOPT_URL,$temp_search_url);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
 
			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0');
			curl_setopt($curl, CURLOPT_HEADER, 0);

			curl_setopt($curl, CURLOPT_COOKIEJAR,  "/var/www/html/dashboard/cookie.txt");
			curl_setopt($curl, CURLOPT_COOKIEFILE, "/var/www/html/dashboard/cookie.txt");

			$data=curl_exec($curl);
			curl_close($curl);
			while(($pos3 = stripos($data,"<script"))!==false){
				$end_pos = stripos($data,"</script>");
				$start = substr($data, 0, $pos3);
				$end = substr($data, $end_pos+strlen("</script>"));
				$data = $start.$end;
			}
		
			while(($pos3 = stripos($data,"<style"))!==false){
				$end_pos = stripos($data,"</style>");
				$start = substr($data, 0, $pos3);
				$end = substr($data, $end_pos+strlen("</style>"));
				$data = $start.$end;
			}
		
			$pos0 = strpos($data, '<div class="med" id="res" role="main">');
			$pos7 = strpos($data, '<div data-jibp="h" data-jiis="uc" id="bottomads">');
			
			
			//fwrite($handle, "data:".substr($data,$pos0,$pos7-$pos0));
			
			$html001 = str_get_html(substr($data,$pos0,$pos7-$pos0));
			$g_class = $html001->find('.g a');
			foreach($g_class  as $g_element){
				fwrite($handle, "class data:".$g_element->attr['class'].":::");
				
				if( strlen($g_element->plaintext)>0  && (  $g_element->attr['class']=="l _HId") ){
					
					if( strpos($g_element->plaintext, '深入瞭解')  >0){
						fwrite($handle, "並非正確新聞\n");
					}else{
						$source_and_date_str=strip_tags($g_element->parent()->parent()->childNodes(1));
						if(    strlen($source_and_date_str)>0   ){
							$year_pos=strpos($source_and_date_str, '年'); 
							$month_pos=strpos($source_and_date_str, '月'); 
							$date_pos=strpos($source_and_date_str, '日'); 
							if($year_pos>0 && $month_pos>0 &&  $date_pos>0 &&  $year_pos<$month_pos &&  $month_pos<$date_pos ){
								$news_date=substr($source_and_date_str,$year_pos-4,($date_pos-$year_pos+4));
								//$string = "This is a test";
								/*
								$today_str=;
								date('Y-m-d H:i:s',$NowTime)
									*/
								$news_date=str_replace ("年", "-", $news_date); // 
								$news_date=str_replace ("月", "-", $news_date); // 
								
								$news_source=substr($source_and_date_str,0,($year_pos-4));
								fwrite($handle, "my link:");
								fwrite($handle,$g_element->href."\n\n\n");
								$sql="select url from newsarticles where url='".$g_element->href."'";
								$result1 = mysql_query($sql,$conn) or die('MySQL query error '.mysql_error().' '.$sql);
								$temp_num_of_res=mysql_num_rows($result1);
								$row1 = mysql_fetch_array($result1);
								mysql_free_result($result1);
								fwrite($handle, "temp_num_of_res:".$temp_num_of_res."\n\n\n");
								if($temp_num_of_res>0) {
									fwrite($handle,"發現重複:".$row1[0]."\n\n\n");
								}else{
									
									//$conn = getConnection("news_db");
									$sql="INSERT INTO newsarticles (url, search_keyword, title,src,ndate) VALUES ('".$g_element->href."','".$MyKeyword."','".$g_element->plaintext."', '".$news_source."', '".date("Y-m-d",strtotime($news_date))."')"; //date("Y-m-d",strtotime($news_date))    //strip_tags($g_element->parent()->parent()->childNodes(1))
									fwrite($handle, "before insert \n\n\n");
									mysql_query($sql,$conn);// or die('MySQL query error '.mysql_error().' '.$sql);
									fwrite($handle, "after insert \n\n\n");
								}
								fwrite($handle, "title:");
								fwrite($handle,$g_element->plaintext."\n\n\n");
								fwrite($handle, "src:".$news_source.",date=". $news_date ."\n\n\n" );//CCC:$g_element是php 字串格式
							}else{
								fwrite($handle, $source_and_date_str."日期資訊不合格式\n");
							}
						}else{
							fwrite($handle, "無日期資訊\n");
						}
					}
				}
			}

			$html001->clear(); 
			unset($g_class);

			unset($html001);
			unset($curl, $data);
			fwrite($handle, "\n");
			fwrite($handle,'memory usage:'.memory_get_usage().'\n');
			fwrite($handle, "\n");

			$num_of_article=$num_of_article+10;
		}catch(Exception $e){

			fwrite($handle,'已經抓取'.num_of_article.'筆資料，工作異常結束\n');
			fwrite($handle,'exception:'.$e->getMessage().' \n');
			DB_stop_task($handle);
			break;
		}
    }
	
	flush();
	ob_flush();
	fwrite($handle,' 抓取單一工作真正要結束了 \n');
	DB_stop_task($handle);
    fclose($handle);

}
 
?>
 


