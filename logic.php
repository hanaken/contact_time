<?php
	function connectDB(){
        require("setting.php");
        try {
			$connect = mysql_connect($DB_SET['host'],$DB_SET['user'],$DB_SET['pass']);
			if (!$connect) throw new Exception("Database connect error"); 
			return $connect;
		} catch (Exception $e) {
			die($e->getMessage() . " mysql_error->".mysql_error());
		}
	}
	
	function getNames(){
		$connect = connectDB();
		$db = mysql_select_db('contacttime' , $connect);
		if (!$db){
			mysql_close($connect);
		    die('データベース選択失敗です。'.mysql_error());
        }
        mysql_query('SET NAMES utf8', $connect );
		$result = mysql_query("select * from name");

		if (!$result) {
			die('クエリーが失敗しました。'.mysql_error());
		}
		mysql_close($connect);
		return $result;
	}
	
	function getTimes ($id) {
		$connect = connectDB();
		$db = mysql_select_db('contacttime' , $connect);
		if (!$db){
		    die('データベース選択失敗です。'.mysql_error());
		}
		$result = mysql_query("select * from time where name_id =".$id);
		if (!$result) {
			mysql_close($connect);
			die('クエリーが失敗しました。'.mysql_error());
		}
		mysql_close($connect);
		return $result;		
	}
	
	function checkin($name_id){
		$now = date('Y-m-d H:i:s');
		$connect = connectDB();
		$db = mysql_select_db('contacttime' , $connect);
		if (!$db){
			mysql_close($connect);
		    die('データベース選択失敗です。'.mysql_error());
		}
		
		//前に終了していない場合にエラーを出す
		$result = mysql_query("select * from time where name_id = ".$name_id." and end is NULL");
		$row = mysql_fetch_assoc($result);
		if (!empty($row)) echo '<div class="alert alert-block">id:'.$name_id.' 終了していません</div>';
		else {
			$result = mysql_query("insert into time(name_id, start) values('".$name_id."','".$now."')");
			if (!$result) {
				die('クエリーが失敗しました。'.mysql_error());
			}
		}
		mysql_close($connect);
	}
	
	function checkout($id){
		$now = date('Y-m-d H:i:s');
		$now_day = date('d',strtotime($now));
		$now_month = date('m',strtotime($now));
		$now_year = date('Y',strtotime($now));
		$connect = connectDB();
		$db = mysql_select_db('contacttime' , $connect);
		if (!$db){
		    die('データベース選択失敗です。'.mysql_error());
		}
		
		//前に開始していない場合にエラーを出す
		$result = mysql_query("select * from time where id = ".$id." and end is not NULL");
		$row = mysql_fetch_assoc($result);
		if (!empty($row)) echo '<div class="alert alert-block">すでに終了しています</div>';
		else{
			//その日の23：59：59秒までしか有効ではない
			$result = mysql_query("select * from time where id = ".$id);
			$row = mysql_fetch_assoc($result);
			$start = date('d', strtotime($row["start"]));
			$start_month = date('m', strtotime($row["start"]));
			$start_year = date('Y', strtotime($row["start"]));
			if (($now_day > $start)||($now_month > $start_month)||($now_year > $start_year)){
				$now = $start_year.'-'.$start_month.'-'.$start.' 23:59:59';
				var_dump($now);
			}
			$result = mysql_query("update time set end = '".$now."' where id = ".$id);
			if (!$result) {
				die('クエリーが失敗しました。'.mysql_error());
			}
		}
		mysql_close($connect);
	}

?>
