<?php include("./include/header.php") ?>
<?php include("./include/UserClass.php");

$user = new UserClass();
if (isset($_POST['username'])) {
    // echo($_POST['username']);
    $user->signup($_POST['username'],  $_POST['email'], $_POST['password'] );
    // echo $user->errors;
}
?>

    <!--------------- Signup Menu --------------->
    <section>
        <div class="form">
            <h2>Signup</h2>
            <div class="form-group">
                <form action="signup_setup.php" method="POST">
                    <input type="text" placeholder="Username" name="username" required>
                    <input type="email" placeholder="Email" name = email required>
                    <input type="password" placeholder="Password" name="password" required>
                    <!-- <input type="password" placeholder="Repeat Password" name="password2" required> -->
                    <input class="btn" type="submit" value="Signup">
                </form>
            </div>
        </div>
    </section>



    <!--------------- Signup Menu --------------->
    <?php include("./include/footer.php") ?>
