<?php
	error_reporting(0);
	require('template/config.php');
	require('template/header.php');

	// HANDLE POST REQUESTS HERE


	// UNTIL HERE
	if (!isset($_GET['club_id'])) {
		echo "<br><br><br><br><br><h1>404 NOT FOUND</h1>";
    die;
	}

	$club_id = $_GET['club_id'];   
    $user_id = $_SESSION['user_id'];

    
?>


<!DOCTYPE html>

<html>
	<br><br><br><br><br><br>

	<div class="row">
		<div class="col s8">
            <?php
                $sql = "SELECT * FROM bc_post WHERE post_id IN (SELECT post_id FROM bc_post_belongs WHERE bc_id = $club_id) " ;
                if ($result = mysqli_query($conn, $sql)) {
                    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
                else{
                        echo "FAILED TO EXECUTE QUERY";
                }
                $sql1 = "SELECT * FROM book_club WHERE club_id = '$club_id'";
                if ($res1 = mysqli_query($conn, $sql1)) {
                   
                    $result1 = mysqli_fetch_array($res1, MYSQLI_ASSOC);
                    echo " <h1>" .$result1["name"]. "</h1>";
                    $sql2 = "SELECT book_cover FROM book WHERE book_id =(SELECT book_id FROM book_club WHERE club_id = $club_id)";
                    $res2 = mysqli_query($conn, $sql2);
                    $result2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
                    echo "<img src=\"" . $result2["book_cover"] . "\"  width=\"300\" height=\"405\"  alt= \"No image\"> ";
                    echo " <h4>" . "Member count = " . $result1["member_count"]. "</h4>";

                }
                else{
                        echo "FAILED TO EXECUTE QUERY";
                }
                
            ?> 
            <?php foreach($result as $res): ?>
                <?php
                $pid = $res["post_id"];
                //echo $pid;
                $sql3 = "SELECT * FROM user WHERE user_id IN(SELECT user_id FROM posts WHERE post_id = $pid) ";
                $res3 = mysqli_query($conn, $sql3);
                $result3 = mysqli_fetch_array($res3, MYSQLI_ASSOC);
                ?>
                <table style = "width:100%">
                    <tr>
                        <td style = "text-align:left"> <?php echo "<h4>" .$result3["first_name"]. "</h4>" ?></td>
                        <td style = "text-align:left"> <?php echo $res["content"];?> </td>		 
                    </tr>
                </table>

                
                
            <?php endforeach ?>
            
            <div class="col s8 blue lighten-2 vertical-align text center-align" style="border: 3px solid black; padding: 5px;">    
                <form action="" method="POST" >   
                    <input type="text" placeholder="Enter Your Comment..." class="text" name="reviewBox">
                    <input type="submit" name="comment" class="btn blue lighten-1" value="Post Comment" style="margin:auto">
                    
                </form> 
                <?php   
                    if (isset($_POST['comment'])) {
                        $sqlid = "SELECT MAX(post_id) as max FROM bc_post";
                        $resId = mysqli_query($conn, $sqlid);
                        $resultId = mysqli_fetch_array($resId, MYSQLI_ASSOC);
                        $maxId = $resultId['max'];
                        $maxId = $maxId + 1;
                        //echo "<h1>".$maxId."<\h1>";
                        $cmt = $_POST['reviewBox'];
                        $sql5 = "INSERT INTO bc_post(post_id, content) VALUES ( ' .$maxId. ' , ' .$cmt. ')";
                        if(mysqli_query($conn,$sql5)){
                            //echo "Query created"; 
                        }
                        else{
                           // echo "Error in insert";
                        }

                        $sql6 = "INSERT INTO bc_post_belongs(bc_id, post_id) VALUES ( '.$club_id.', '.$maxId.')";
                        if(mysqli_query($conn,$sql6)){
                            //echo "Query created"; 
                        }
                        else{
                            //echo "Error in insert";
                        }
                        $sql7 = "INSERT INTO posts(post_id, user_id) VALUES('.$maxId.', '.$user_id.') ";
                        if(mysqli_query($conn,$sql7)){
                            //echo "Query created"; 
                        }
                        else{
                            //echo "Error in insert";
                        }
                    }
                ?>
            </div>

		</div>
    
	</div>
		<div class="col s4">
			
		</div>
	</div>
	

</body>

</html>