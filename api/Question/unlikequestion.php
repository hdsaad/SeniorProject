<?php
include "../Account/db.php";

$uID = $_SESSION['user_ID'];
$qID=$_POST['Question_ID'];

//DELETE FROM LIKE QUESTIONS TABLE
$r=mysqli_query($con,"DELETE FROM `like_questions` WHERE question_ID='$qID'");

//IF QUERY, DISPLAY NOTIFICATION, ELSE QUERY ERROR
if($r){
    echo json_encode("Question has been unliked");
    die();
}else{
    echo json_encode("query failed");
    die();
}

 ?>