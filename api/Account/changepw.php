<?php
include "db.php";

if(isset($_POST['change']))
{
    //GET USER EMAIL FROM SESSION
    $uemail = $_SESSION['email'];

    //GET OLD AND NEW PASSWORD FROM FORM ACTION
    $oldpw=$_POST['old_password'];
    $newpw=$_POST['new_password'];
   
    //HASH THE NEW PASSWORD 
    $hashed_newpassword = password_hash($newpw, PASSWORD_DEFAULT);

    //RUN QUERY TO GATHER CURRENT USER INFORMATION
    $result = $con->query("SELECT * FROM users WHERE email='$uemail'");
    $user = $result->fetch_assoc();

    //VERIFY THE CURRENT PASSWORD WITH THE PASSWORD IN THE DATABASE
    if(password_verify($oldpw, $user['passwrd'])){
        //IF VALID CHANGE THE PASSWORD
        mysqli_query($con, "UPDATE `users` SET passwrd='$hashed_newpassword' WHERE email='$uemail'") or die(mysql_error());
        echo json_encode("valid");
        die();
    }
    else{
        echo json_encode("not valid");
        die();
    }


}

?>