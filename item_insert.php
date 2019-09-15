<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$connect = dbconnect($host,$dbid,$dbpass,$dbname);

$item_name = $_POST['item_name'];
$power = $_POST['power'];
$weight = $_POST['weight'];
$distance = $_POST['distance'];

mysqli_autocommit($connect,FALSE);	//disable auto-commit
mysqli_query($connect, "set session transaction isolation level serializable"); // set session consistency level to serializable
mysqli_begin_transaction($connect, MYSQLI_TRANS_START_READ_WRITE);	// set transaction as read & write because it inserts to table

$ret = mysqli_query($connect, "insert into item (item_name, power, weight, distance) values('$item_name', '$power', '$weight', '$distance')");
if(!$ret)
{
	mysqli_rollback($connect);	// If failure happens, rollback
    msg('Query Error : '.mysqli_error($connect));
}
else
{
	mysqli_commit($connect);	// If no error happens, leave commit
    s_msg ('성공적으로 입력 되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=item_list.php'>";
}

?>
