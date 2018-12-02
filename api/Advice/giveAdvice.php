<?php
    include "../Account/db.php";
    header('Content-Type: application/json');
    //INSERT ADVICE ON SELECTED QUESTION
    $advice = $_POST["advice"];
    $Question_ID = $_POST["Question_ID"];
    $uID = $_SESSION['user_ID'];
    $active = $_SESSION['active'];

    if($active == '1'){
        $data = mysqli_query($con, "SELECT * FROM questions WHERE Question_ID='$Question_ID'");
        while($a = mysqli_fetch_object($data)){
            $userID = $a->user_id;
        }

        $data2 = mysqli_query($con, "SELECT * FROM users WHERE user_id=$uID");
        while($b = mysqli_fetch_object($data2)){
            $fname = $b->f_name;
            $lname = $b->l_name;
            $Name = $fname . ' ' . $lname;
        }

        //notification query and check so that user that owns the question wont get a notfication if respond to their question
        if($uID != $userID){
            mysqli_query($con,"INSERT INTO `notification` (`question_id`,`user_id`, `user_id2`, `name`) VALUES ('$Question_ID','$userID', '$uID', '$Name')");
        }
        //get points from database
        $result=mysqli_query($con, "SELECT * FROM users WHERE user_ID=$uID");
        while($row=mysqli_fetch_object($result))
        {
            $points=$row->points;
            $accumulatedpoints=$row->accumulated_points;
            $level=$row->level;
        }
        //check if user has passed the requirement for next level
        $accumulatedpoints=$accumulatedpoints+2;
        if($level == "Bronze" && $accumulatedpoints >= 1000)
        {
            $newlevel= "Silver";
        }
        else if($level == "Silver" && $accumulatedpoints >= 2000)
        {
            $newlevel= "Gold";
        }
        else
        {
            $newlevel = $level;
        }
        //update accumulated points
        mysqli_query($con, "UPDATE users SET accumulated_points=$accumulatedpoints WHERE user_ID=$uID");
        //update level
        mysqli_query($con, "UPDATE users SET level='$newlevel' WHERE user_ID=19");

        //check if user has too many points
        if($points == 99)
        {
            //add to points
            $points=$points + 1;
            //update points
            mysqli_query($con, "UPDATE users SET points=$points WHERE user_ID=$uID");
        }
        else if($points < 99)
        {
            //add to points
            $points=$points + 2;
            //update points
            mysqli_query($con, "UPDATE users SET points=$points WHERE user_ID=$uID");
        }
        $dataquery = mysqli_query($con,"INSERT INTO `advice` (`advice`,`question_id`, `user_id`) VALUES ('$advice','$Question_ID', '$uID')");
        
        if($dataquery){
            echo json_encode("success");
            die();
        }
        else{
            echo(mysqli_error($con));
            echo json_encode("error");
            die();
        }
    }
    else{
        echo json_encode("You have not verified your email");
        die();
    }

?>