<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
mysqli_autocommit($conn,FALSE);	//disable auto-commit
mysqli_query($conn, "set session transaction isolation level serializable"); // set session consistency level to serializable
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);	// set transaction as read & write because it modifies table

$item_name = $_POST['item_name'];
$power = $_POST['power'];
$weight = $_POST['weight'];
$distance = $_POST['distance'];

$ret = mysqli_query($conn, "update item set power = $power, weight = $weight, distance = $distance where item_name = '$item_name'");

if(!$ret)
{
	mysqli_rollback($conn);	// If failure happens, rollback
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	mysqli_commit($conn);	// If no error happens, leave commit
    s_msg ('성공적으로 수정 되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=item_list.php'>";
}
mysqli_close($conn);	// Close connection
?>
