#!/usr/bin/php -q
<?php
    echo  'erter';
    $handle = fopen('/var/www/html/dashboard/CCCnewfile1.txt', 'a');
    fwrite($handle,'my(child) pid:'.getmypid()."\n" );
    fclose($handle);
?>
