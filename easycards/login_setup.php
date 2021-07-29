<?php include("./include/UserClass.php");

$user = new UserClass();
if (isset($_POST['username'])) {
    $user->login($_POST['username'], $_POST['password'] );
}
?>
