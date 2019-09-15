<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>

<div class="container">
    <?
		$conn = dbconnect($host,$dbid,$dbpass,$dbname);
		mysqli_autocommit($conn,FALSE);	//disable auto-commit
		mysqli_query($conn, "set session transaction isolation level serializable"); // set session consistency level to serializable
		mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_ONLY);	// set transaction as read only because it only reads from tables

		$username = $_GET['username'];
		$join_sql = ("SELECT * FROM user NATURAL JOIN rank WHERE username='".$username."'");
		$join_res  = mysqli_query($conn, $join_sql);

		if(!$join_res){
			mysqli_rollback($conn);	// If failure happens, rollback
    		msg('Query Error : '.mysqli_error($conn));
		}else{
			mysqli_commit($conn);	// If no error happens, leave commit
		}
		$sql = ("SELECT * FROM user WHERE username='".$username."'");
		$res  = mysqli_query($conn, $sql);

		if(!$res){
			mysqli_rollback($conn);	// If failure happens, rollback
    		msg('Query Error : '.mysqli_error($conn));
		}else{
			mysqli_commit($conn);	// If no error happens, leave commit
		}
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		h1 {
			padding: 0;
    		position: relative;
	    	left: 0;
    		outline: none;
    		background-color: black;
		 	color: white;
			text-align: center;
    		font-size: 50px;
		}
		img {
			float: left;
			padding-top: 30px;
			padding-right: 30px;
		}
		li {
			padding-top: 10px;

		}
	</style>
	<div class="user_information">
		<h1>
			<?php
				$row = mysqli_fetch_array($res);
				echo $row['username'];
			?>
		</h1>
		<img src="images/user_icon.jpeg"/>
		<?php
			$rownum = mysqli_num_rows($res);
			for($i = 0; $i<$rownum; $i++){
				echo "<li>Team : {$row['team']}</li>";
				echo "<li>승률 : {$row['win_rate']}</li>";
				echo "<li>킬뎃 : {$row['kill_death']}</li>";
				echo "<li>최다킬 : {$row['most_kill']}</li>";
			}

			$join_row = mysqli_fetch_array($join_res);
			$rownum = mysqli_num_rows($join_res);
			for($i = 0; $i<$rownum; $i++){
				echo "<li>타입 : {$join_row['type']}</li>";
				echo "<li>순위 : {$join_row['ranking']}</li>";
				echo "<li>시즌 : {$join_row['season']}</li>";
			}
		?>

		<p>
			<?php
				$join_sql = ("SELECT * FROM user NATURAL JOIN game WHERE user.username='".$username."' and user.username=game.winner");
				$join_res  = mysqli_query($conn, $join_sql);

				if(!$join_res){
					mysqli_rollback($conn);	// If failure happens, rollback
	    			msg('Query Error : '.mysqli_error($conn));
				}else{
					mysqli_commit($conn);	// If no error happens, leave commit
				}
				$rownum = mysqli_num_rows($join_res);

				if($rownum!=0){
					echo "<li>우승한 경기 : ";
					for($i = 0; $i<$rownum; $i++){
						$join_row = mysqli_fetch_array($join_res);
						echo"{$join_row['game_ID']}번 경기 ";
						echo"(주무기: {$join_row['winner_item']})";
						if($i+1 != $rownum){
							echo ",";
						}
					}
				echo "</li>";
				}



			?>
		</p>
	</div>

</div>
<? include("footer.php") ?>
