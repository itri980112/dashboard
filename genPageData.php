<?php 
    function func1($data){
        return $data+1;
    }

    if (isset($_POST['callFunc1'])) {
		shell_exec("/usr/local/rvm/bin/ruby /var/www/html/tachen/mongodb/userrank.rb ".$_POST['callFunc1']);
error_log ("/usr/local/rvm/bin/ruby /var/www/html/tachen/mongodb/userrank.rb ".$_POST['callFunc1']);
        echo func1($_POST['callFunc1']);		
    }
?>
