<?php
	$dbHost = 'localhost';
	$dbUser = 'root';
	$dbPass = 'hk08336';
	$dbName = 'contacttime';
	$fileName = date('ymd').'.txt';
	$command = "mysqldump --default-character-set=binary ".$dbName." --host=".$dbHost." --user=".$dbUser." --password=".$dbPass." > backup/".$fileName;
	system($command);
?>