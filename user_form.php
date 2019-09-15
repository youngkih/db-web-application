<?php
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
mysqli_autocommit($conn,FALSE);	//disable auto-commit
mysqli_query($conn, "set session transaction isolation level serializable"); // set session consistency level to serializable
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_ONLY);	// set transaction as read only because it only reads from table

$mode = "입력";
$action = "user_insert.php";

if (array_key_exists("username", $_GET)) {
    $username = $_GET["username"];
    $query =  "select * from user where username = '".$username."'";
    $res = mysqli_query($conn, $query);
    $user = mysqli_fetch_array($res);
    if(!$user) {
    	mysqli_rollback($conn);	// If failure happens, rollback
        msg($username."유저가 존재하지 않습니다.");
    }else{
    	mysqli_commit($conn);	// If no error happens, leave commit
    }
    $mode = "수정";
    $action = "user_modify.php";
}

$users = array();

$query = "select * from user";
$res = mysqli_query($conn, $query);
if(!$res){
	mysqli_rollback($conn);	// If failure happens, rollback
}else{
	mysqli_commit($conn);	// If no error happens, leave commit
}
while($row = mysqli_fetch_array($res)) {
    $users[$row['username']] = $row['username'];
}
?>

	<style>
		h3{
			text-align:center;
		}
        label {
            color: blue;
            text-align:center;
        }
		input {
			width: 375px;
			height: 25px;

			position: center;

		}
    </style>
    <div class="container">
        <form name="user_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="username" value="<?=$user['username']?>"/>
            <h3>유저 정보 <?=$mode?></h3>
            <p>
                <label for="username">Username</label>
                <input type="text" placeholder="유저명 입력" id="username" name="username" value="<?=$user['username']?>">
            </p>
            <p>
                <label for="team">Team</label>
                <input type="text" placeholder="팀명 입력" id="team" name="team" value="<?=$user['team']?>"/>
            </p>
            <p>
                <label for="win_rate">Win rate</label>
                <input type="text" placeholder="승률 입력" id="win_rate" name="win_rate" value="<?=$user['win_rate']?>"/>
            </p>
            <p>
                <label for="kill_death">K/D</label>
                <input type="text" placeholder="킬/뎃입력" id="kill_death" name="kill_death" value="<?=$user['kill_death']?>" />
            </p>
			<p>
                <label for="most_kill">Most kill</label>
                <input type="number" placeholder="최대 킬" id="most_kill" name="most_kill" value="<?=$user['most_kill']?>" />
            </p>

            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                    if(document.getElementById("username").value == "") {
                        alert ("유저명을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("win_rate").value == "") {
                        alert ("승률을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("kill_death").value == "") {
                        alert ("킬뎃을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("most_kill").value == "-1") {
                        alert ("최대 킬을 입력해 주십시오"); return false;
                    }
                    return true;
                }
            </script>

        </form>
    </div>
<? include("footer.php") ?>
