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
		<h1>コンタクトタイム計測システム</h1>
		<table class="table table-bordered table-hover">
			<thead >
				<tr>
					<th>id</th>
					<th>名前</th>
					<th>合計</th>
					<th>履歴</th>
					<th>#</th>
				</tr>
			</thead>
			<tbody>
			<?php
				require("logic.php");
				
				if (isset($_GET["name_id"])) {
					$result = checkin($_GET["name_id"]);
				} else if (isset($_GET["id"])) {
					$result = checkout($_GET["id"]);
				}
			
				
				$result = getNames();
				while ($row = mysql_fetch_assoc($result)) {	
					$time_result = getTimes($row["id"]);
					$time_add = 0;
					$flag = 0;
					while ($time_row = mysql_fetch_assoc($time_result)) {
						if ($time_row["end"] === NULL) {
							$flag = $time_row["id"];
							$start = date('Y-m-d H:i:s' ,strtotime($time_row["start"]));
						}
						else {
							$end = $time_row["end"];
						
							//秒数の計算
							$time_start = strtotime($time_row["start"]);
							$time_end = strtotime($time_row["end"]);
							$time_add += abs($time_end - $time_start);
						}
					}

					$i += 1;
					$time_sum_h = (int)((int)($time_add / 60) / 60);
					$time_sum_m = (int)(($time_add - $time_sum_h*60*60) / 60);
					$time_sum_s = ($time_add - $time_sum_h*60*60 - $time_sum_m*60); ?>
					<?php
					if ($flag === 0) :
						//来た時の表示
					?>
					<tr class="error">
						<td><?php echo $row["id"] ?>
						<td><i class="icon-user"></i><?php echo $row["name"] ?>
						<td><?php echo $time_sum_h.'時'.$time_sum_m.'分'.$time_sum_s.'秒' ?>
						<td><?php echo $end ?> まで </td>
						<td><a class="btn" href="./contact.php?name_id=<?php echo $row["id"]?>"><i class="icon-off"></i> 開始</a></td>
					<?php
					else:
						//帰るときの表示
					?>
					<tr class="success">
						<td><?php echo $row["id"] ?>
						<td><i class="icon-user"></i><?php echo $row["name"] ?>
						<td><?php echo $time_sum_h.'時'.$time_sum_m.'分'.$time_sum_s.'秒' ?>
						<td><?php echo $start?> から </td>
						<td><a class="btn btn-danger" href="./contact.php?id=<?php echo $flag ?>"><i class="icon-ok icon-white"></i> 終了</a></td>
					<?php
					endif;
				}
			?>
			</tbody>
		</table>
	</div>
</html>