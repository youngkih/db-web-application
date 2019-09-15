<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$mode = "입력";
$action = "item_insert.php";
mysqli_autocommit($conn,FALSE);	//disable auto-commit
mysqli_query($conn, "set session transaction isolation level serializable");	// set session consistency level to serializable
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_ONLY);	// set transaction as read only because it only reads from table

if (array_key_exists("item_name", $_GET)) {
    $item_name = $_GET["item_name"];
    $query =  "select * from item where item_name = '".$item_name."'";
    $res = mysqli_query($conn, $query);
    if(!$res){
    	mysqli_rollback($conn);	// If failure happens, rollback
    	msg("Query Error");
    }else{
    	mysqli_commit($conn);	// If no error happens, leave commit
    }
    $item = mysqli_fetch_array($res);
    if(!$item) {
        msg($item_name."무기가 존재하지 않습니다.");
    }
    $mode = "수정";
    $action = "item_modify.php";
}

$items = array();

$query = "select * from item";
$res = mysqli_query($conn, $query);
if(!$res){
    	mysqli_rollback($conn);	// If failure happens, rollback
    	msg("Query Error");
}else{
    	mysqli_commit($conn);	// If no error happens, leave commit
}
while($row = mysqli_fetch_array($res)) {
    $items[$row['item_name']] = $row['item_name'];
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
        <form name="item_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="item_name" value="<?=$item['item_name']?>"/>
            <h3>무기 정보 <?=$mode?></h3>
            <p>
                <label for="item_name">Item Name</label>
                <input type="text" placeholder="무기명 입력" id="item_name" name="item_name" value="<?=$item['item_name']?>">
            </p>
            <p>
                <label for="power">Power</label>
                <input type="text" placeholder="공격력 입력" id="power" name="power" value="<?=$item['power']?>"/>
            </p>
            <p>
                <label for="weight">Weight</label>
                <input type="text" placeholder="무게 입력" id="weight" name="weight" value="<?=$item['weight']?>"/>
            </p>
            <p>
                <label for="distance">Distance</label>
                <input type="text" placeholder="사거리" id="distance" name="distance" value="<?=$item['distance']?>" />
            </p>

            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                    if(document.getElementById("item_name").value == "") {
                        alert ("무기명을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("power").value == "-1") {
                        alert ("데미지를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("weight").value == "-1") {
                        alert ("무게를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("distance").value == "-1") {
                        alert ("사거리를 입력해 주십시오"); return false;
                    }
                    return true;
                }
            </script>

        </form>
    </div>
<? include("footer.php") ?>
