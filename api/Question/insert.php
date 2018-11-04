<?php
    include "../Account/db.php";
    //INSERT A QUESTION
    header('Content-type: application/json');
    
    if((isset($_POST['insert']))&&(isset($_SESSION['active'])))
    {
        $Description=$_POST['Description'];
        $Subject=$_POST['Subject'];
        $topic=$_POST['topic'];
        $type=$_POST['question_type'];
        $uID = $_SESSION['user_ID'];
        $active = $_SESSION['active'];
        $anonymous = $_POST['anonymous'];
        $hide = $_POST['hide'];
    
        //has user verified email
        if($active == 1){
			
			//query table to get points of user
			$points=mysqli_query($con,
			"SELECT points
			FROM users
			WHERE user_ID = $uID");
	
			//subtract points for the question submit
			$points = $points- 20;
	
			//Insert new point value back into users table for specific user
			mysqli_query($con, 
			"UPDATE users 
			SET `points` = $points 
			WHERE users.user_ID = $uID");
			
        $q=mysqli_query($con,"INSERT INTO `Questions` (`user_id` ,`Description`,`Subject`,`topic`,`question_type`, `anonymous`, `hide`) VALUES ('$uID','$Description','$Subject','$topic','$type', '$anonymous', '$hide')");
            if($q){
                echo json_encode("success");
                die();
            }
            else{
                echo json_encode("error");
                die();
            }
        }
        else if($active == 0){
            echo json_encode("Not active");
            die();
        }    
    }
    else{
        echo json_encode("No account");
        die();
    }
    
?>