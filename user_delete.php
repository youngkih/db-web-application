<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_autocommit($conn,FALSE);	//disable auto-commit
mysqli_query($conn, "set session transaction isolation level serializable"); // set session consistency level to serializable
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);	// set transaction as read & write because it deletes from table

$username = $_GET['username'];

$sql = ("DELETE FROM user WHERE username='".$username."'");

$ret  = mysqli_query($conn, $sql);

if(!$ret)
{
	mysqli_rollback($conn);	// If failure happens, rollback
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	mysqli_commit($conn);	// If no error happens, leave commit
    s_msg ('성공적으로 삭제 되었습니다');
	echo "<meta http-equiv='refresh' content='0;url=user_list.php'>";
}
mysqli_close($conn);	// Close connection
?>
