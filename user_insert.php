<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$connect = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_autocommit($connect,FALSE);	//disable auto-commit
mysqli_query($connect, "set session transaction isolation level serializable"); // set session consistency level to serializable
mysqli_begin_transaction($connect, MYSQLI_TRANS_START_READ_WRITE);	// set transaction as read & write because it inserts to table

$username = $_POST['username'];
$team = $_POST['team'];
$win_rate = $_POST['win_rate'];
$kill_death = $_POST['kill_death'];
$most_kill = $_POST['most_kill'];

$ret = mysqli_query($connect, "insert into user (username, team, win_rate, kill_death, most_kill) values('$username', '$team', '$win_rate', '$kill_death', '$most_kill')");
if(!$ret)
{
	mysqli_rollback($conn);	// If failure happens, rollback
    msg('Query Error : '.mysqli_error($connect));
}
else
{
	mysqli_commit($conn);	// If no error happens, leave commit
    s_msg ('성공적으로 입력 되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=user_list.php'>";
}
mysqli_close($conn);	// Close connection
?>
