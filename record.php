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
			<table class="table table-bordered table-hover table-striped">
				<thead >
					<tr>
						<th>開始</th>
						<th>終了</th>
						<th>実施時間</th>
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
						$cnt = 0;
						$time = 0;
						$result = mysql_query("select * from time where name_id = ".$_GET["name_id"]." order by start asc");
						while($row = mysql_fetch_assoc($result)):
							if($cnt === 0) $start = date('Y-m-d H:i:s' ,strtotime($row["start"]));
							$cnt++;
							$time_start = strtotime($row["start"]);
							if($row["end"]===null) $time_end = strtotime($row["start"]);
							else $time_end = strtotime($row["end"]);
							$time_sub = abs($time_end - $time_start);
							if(date('d' ,strtotime($start)) === date('d' ,strtotime($row["end"]))):
								$time += $time_sub;
								$end = $row["end"];
							else :
								$time_sum_h = (int)((int)($time / 60) / 60);
								$time_sum_m = (int)(($time - $time_sum_h*60*60) / 60);
								$time_sum_s = ($time - $time_sum_h*60*60 - $time_sum_m*60);
								?>
					<tr>
						<td><?php echo $start; ?></td>
						<td><?php echo $end; ?></td>
						<td><?php echo $time_sum_h.'時間'.$time_sum_m.'分'.$time_sum_s.'秒' ?></td>
					</tr>
								<?php
								$start = date('Y-m-d H:i:s' ,strtotime($row["start"]));
								$end = $row["end"];
								$time = $time_sub;
							endif;
						endwhile;
						$time_sum_h = (int)((int)($time / 60) / 60);
						$time_sum_m = (int)(($time - $time_sum_h*60*60) / 60);
						$time_sum_s = ($time - $time_sum_h*60*60 - $time_sum_m*60);						
						?>
					<tr>
						<td><?php echo $start; ?></td>
						<td><?php echo $end; ?></td>
						<td><?php echo $time_sum_h.'時間'.$time_sum_m.'分'.$time_sum_s.'秒' ?></td>
					</tr>
						<?php mysql_close($connect);
					}
				?>
				</tbody>
			</table>
		</div>
	</body>
</html>
