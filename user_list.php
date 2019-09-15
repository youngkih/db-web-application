<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>

<div class="container">
    <?
    $connect = dbconnect($host, $dbid, $dbpass, $dbname);
    mysqli_autocommit($connect,FALSE);	//disable auto-commit
	mysqli_query($connect, "set session transaction isolation level serializable"); // set session consistency level to serializable
	mysqli_begin_transaction($connect, MYSQLI_TRANS_START_READ_ONLY);	// set transaction as read only because it only reads from table
    ?>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>No.</th>
            <th>Username</th>
            <th>Team</th>
            <th>Win rate</th>
            <th>K/D</th>
            <th>Most kill</th>
        </tr>
        </thead>
        <tbody>
        <?

        $result = mysqli_query($connect, "select * from user order by win_rate desc");
        if(!$result){
        	mysqli_rollback($connect);	// If failure happens, rollback
        }else{
        	mysqli_commit($connect);	// If no error happens, leave commit
        }
		$rownum = mysqli_num_rows($result);

		for($i = 0; $i<$rownum; $i++){
			$row = mysqli_fetch_array($result);
			echo "<tr>";
			echo "<td>$i</td>";
			echo "<td>
			<a href='user_detail.php?username=".$row['username']."'>{$row['username']}</a>
			</td>";
			echo "<td>
			<a href='team_detail.php?team=".$row['team']."'>{$row['team']}</a>
			</td>";
			// echo "<td>{$row['team']}</td>";
			echo "<td>{$row['win_rate']}</td>";
			echo "<td>{$row['kill_death']}</td>";
			echo "<td>{$row['most_kill']}</td>";
			echo "<td width='10%'>
				<a href='user_form.php?username=".$row['username']."'><button class='button primary small'>Modify</button></a>
				</td>";
			echo "<td width='10%'>
				<a href='user_delete.php?username=".$row['username']."'><button class='button danger small'>Delete</button></a>
				</td>";
			echo "</tr>";
		}

        ?>
        </tbody>
    </table>
</div>
<? include("footer.php") ?>
