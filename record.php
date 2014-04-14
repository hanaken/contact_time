<!DOCTYPE html>
<html>
	<head>
		<title>コンタクトタイムシステム</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <!-- Bootstrap -->
	    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<div class="container">
			<table class="table table-bordered table-hover">
				<thead >
					<tr>
						<th>開始</th>
						<th>終了</th>
					</tr>
				</thead>
				<tbody>
				<?php
					require("logic.php");
					if (isset($_GET["name_id"])){
						$connect = connectDB();
						$db = mysql_select_db('contacttime' , $connect);
						if (!$db){
						    die('データベース選択失敗です。'.mysql_error());
						}
						
						//前に開始していない場合にエラーを出す
						$result = mysql_query("select * from time where name_id = ".$_GET["name_id"]." order by id asc");
						while($row = mysql_fetch_assoc($result)){?>
						<tr>
							<th><?php echo $row["start"]; ?></th>
							<th><?php echo $row["end"]; ?></th>
						</tr>
						<?php
						}
						mysql_close($connect);
					}
				?>
				</tbody>
			</table>
		</div>
	</body>
</html>