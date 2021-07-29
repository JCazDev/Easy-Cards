<?php include("./include/header.php") ?>
<?php include("./include/UserClass.php");

$user = new UserClass();
if (isset($_POST['username'])) {
    $user->login($_POST['username'], $_POST['password'] );
}
?>

<!--------------- Login Menu --------------->
<section>
    <div class="form">
        <h2>Login</h2>
        <div class="form-group">
            <form action="login_setup.php" method="POST">
                <input type="text" placeholder="Username or Email" name = "username">
                <input type="password" placeholder="Password" name="password">
                <input class="btn" type="submit" value="Login">
            </form>
        </div>
    </div>
</section>
<!--------------- Login Menu --------------->

<?php include("./include/footer.php") ?>