
<?php

	$session    = session_id();
	$time       = time();
	$time_check = $time-300;     //We Have Set Time 5 Minutes

	$result = $mysqli->query("SELECT * FROM user_online WHERE session='$session'");
	$count = $result->num_rows;

	if($count=="0"){
		$mysqli->query("INSERT INTO user_online(session, time)VALUES('$session', '$time')");
	} else {
		$mysqli->query("UPDATE user_online SET time='$time' WHERE session = '$session'");
	}

	$result3 = $mysqli->query("SELECT * FROM user_online");
	$count_user_online = $result3->num_rows;

	$mysqli->query("DELETE FROM user_online WHERE time<$time_check");

	$end_time = microtime(TRUE);
	$time_taken =($end_time - $start_time)*1;
	$time_taken = round($time_taken,3);
?>


<footer class="main-footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<p>
				<i class="fa fa-fw fa-clock-o"></i><?php echo $time_taken;?>'s | &copy; <b style="color:var(--primary-color);">StayFrosty</b>
					<?php echo '<b>2018</b> - <b>'.date("Y").'</b>';?> | Users online: <b><?php echo $count_user_online?></b>
				</p>
			</div>
			<div class="col-sm-6 text-right">
				<p>Developed with <i style="color:red" class="fa fa-heart"></i> by <a href="https://instagram.com/<?php echo DEVELOPER_INSTAGRAM;?>/" class="external"><b style="color:var(--primary-color);"><?php echo DEVELOPER;?></b></a>.</p>
			</div>
		</div>
	</div>
</footer>
