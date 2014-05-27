<?php
	$NAME_DEFAULT = array(
		'Hanada' => '花田賢人',
		'Maruyama' => '丸山拓',
		'Miyazaki' => '宮崎晴夫',
		'Ishida' => '石田翔悟',
		'Inada' => '稲田詩央奈',
		'Sato' => '佐藤義慈',
		'Nakagawa' => '仲川浩樹',
		'Kajiwara' => '梶原慎太郎',
		'Harashita' => '原下聖貴',
		'Kanemaru' => '金丸高大',
		'Iijima' => '飯島洋一',
		'Yoshii' => '吉井孝太郎',
	);
?>
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
					<th>最終履歴</th>
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
				$time_add = array();
				$start = array();
				$end = array();
				$flag = array();
				$ids = array();
				while ($row = mysql_fetch_assoc($result)) {	
					$time_result = getTimes($row["id"]);
					$time_add[$row["name"]] = 0;
					$flag[$row["name"]] = 0;
					$ids[$row["name"]] = $row["id"];
					while ($time_row = mysql_fetch_assoc($time_result)) {
						if ($time_row["end"] === NULL) {
							$flag[$row["name"]] = $time_row["id"];
							$start[$row["name"]] = date('Y-m-d H:i:s' ,strtotime($time_row["start"]));
						}
						else {
							$end[$row["name"]] = $time_row["end"];
						
							//秒数の計算
							$time_start = strtotime($time_row["start"]);
							$time_end = strtotime($time_row["end"]);
							$time_add[$row["name"]] += abs($time_end - $time_start);
						}
					}
				}
				
				arsort($time_add);

				$rank = 0;
				foreach($time_add as $key => $value){
					$rank += 1;
					$time_sum_h = (int)((int)($time_add[$key] / 60) / 60);
					$time_sum_m = (int)(($time_add[$key] - $time_sum_h*60*60) / 60);
					$time_sum_s = ($time_add[$key] - $time_sum_h*60*60 - $time_sum_m*60); ?>
					<?php
					if ($flag[$key] === 0) :
						//来た時の表示
					?>
					<tr class="error">
						<td><?php echo $rank ?></td>
						<td><i class="icon-user"></i><a href="./record.php?name_id=<?php echo $ids[$key] ?>"><?php echo $NAME_DEFAULT[$key] ?></a></td>
						<td><?php echo $time_sum_h.'時間'.$time_sum_m.'分'.$time_sum_s.'秒' ?></td>
						<td><?php echo $end[$key] ?> まで </td>
						<td><a class="btn" href="./contact.php?name_id=<?php echo $ids[$key]?>"><i class="icon-off"></i> 開始</a></td>
					<?php
					else:
						//帰るときの表示
					?>
					<tr class="success">
						<td><?php echo $rank ?>
						<td><i class="icon-user"></i><a href="./record.php?name_id=<?php echo $ids[$key] ?>"><?php echo $NAME_DEFAULT[$key] ?>
						<td><?php echo $time_sum_h.'時'.$time_sum_m.'分'.$time_sum_s.'秒' ?>
						<td><?php echo $start[$key]?> から </td>
						<td><a class="btn btn-danger" href="./contact.php?id=<?php echo $flag[$key] ?>"><i class="icon-ok icon-white"></i> 終了</a></td>
					<?php
					endif;
				}
			?>
			</tbody>
		</table>
	</div>
</html>