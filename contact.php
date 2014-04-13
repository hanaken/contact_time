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
		<div class="row">
			<div class="span9">
			<?php
				require("logic.php");
				
				if (isset($_GET["name_id"])) {
					$result = checkin($_GET["name_id"]);
				} else if (isset($_GET["id"])) {
					$result = checkout($_GET["id"]);
				}
			
				
				$result = getNames();
				$i = 0;
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
							//秒数の計算
							$time_start = strtotime($time_row["start"]);
							$time_end = strtotime($time_row["end"]);
							$time_add += abs($time_end - $time_start);
						}
					}

					if ($i%3 === 0) echo '<br><div class="row">';
					$i += 1;
					$time_sum_h = (int)((int)($time_add / 60) / 60);
					$time_sum_m = (int)(($time_add - $time_sum_h*60*60) / 60);
					$time_sum_s = ($time_add - $time_sum_h*60*60 - $time_sum_m*60);
					echo '<div class="span3"><p><i class="icon-user"></i>'.$row["name"].'</p>
						<p>合計 : '.$time_sum_h.'時間'.$time_sum_m.'分'.$time_sum_s.'秒</p>';

					if ($flag === 0) {
						//来た時の表示
						echo '<a class="btn" href="./contact.php?name_id='.$row["id"].'"><i class="icon-off"></i> 開始</a>';
					} else {
						//帰るときの表示
						echo '<p>開始 : '.$start.'</p>';
						echo '<a class="btn btn-danger" href="./contact.php?id='.$flag.'"><i class="icon-ok icon-white"></i> 終了</a>';
					}
					echo '</div>';
					if ($i%3 === 0) echo '</div><br>';
				}
				
			?>
			</div>
		</div>
	</div>
</html>