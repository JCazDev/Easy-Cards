<?php include("./include/UserClass.php");

$user = new UserClass();
if (isset($_POST['username'])) {
    // echo($_POST['username']);
    $user->signup($_POST['username'],  $_POST['email'], $_POST['password'] );
    // echo $user->errors;
}
?>