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
            <th>Item</th>
            <th>Power</th>
            <th>Weigth</th>
            <th>Distance</th>
        </tr>
        </thead>
        <tbody>
        <?

        $result = mysqli_query($connect, "select * from item");

        if($result){
        	mysqli_commit($connect);	// If no error happens, leave commit
			$rownum = mysqli_num_rows($result);

			for($i = 0; $i<$rownum; $i++){
				$row = mysqli_fetch_array($result);
				echo "<tr>";
				echo "<td>{$row['item_name']}</td>";
				echo "<td>{$row['power']}</td>";
				echo "<td>{$row['weight']}</td>";
				echo "<td>{$row['distance']}</td>";
				echo "<td width='10%'>
					<a href='item_form.php?item_name=".$row['item_name']."'><button class='button primary small'>Modify</button></a>
					</td>";
				echo "<td width='20%'>
					<a href='item_delete.php?item_name=".$row['item_name']."'><button class='button danger small'>Delete</button></a>
					</td>";
				echo "</tr>";
			}
        }else{
        	mysqli_rollback($connect);	// If failure happens, rollback
    		msg('Query Error : '.mysqli_error($connect));
        }

        ?>
        </tbody>
    </table>

    <table class="table">
    	<tbody>
    		 <?
        	$result = mysqli_query($connect, "select item_name, count(*) from game natural join item where winner_item=item_name group by item_name order by count(*) desc;");

        	if($result){
        		mysqli_commit($connect);	// If no error happens, leave commit
        		$rownum = mysqli_num_rows($result);

        		echo "<h3>우승자들이 사용한 무기(횟수)</h3>";
        		for($i=0;$i<$rownum;$i++){
	        		$row = mysqli_fetch_array($result);
		        	echo "<th>{$row['item_name']} ({$row['count(*)']})";
    		    	echo "</th>";
	    		}
        	}else{
        		mysqli_rollback($connect);	// If failure happens, rollback
    			msg('Query Error : '.mysqli_error($connect));
        	}
        ?>

    	</tbody>
    </table>


</div>
<? include("footer.php") ?>
