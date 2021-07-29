<?php include("./include/connection.php") ?>
<?php include("./include/BoardClass.php"); ?>
<?php include("./include/functions.php") ?>

<?php
session_start();


if (isset($_POST['update_board'])) {
    $username = $_SESSION['username'];

    $new_title = $_POST['update_title'];

    $user_query = "select id from users where username = '$username'";
    $result = mysqli_query($conn, $user_query);

    $row = mysqli_fetch_assoc($result);

    $board_id = $row['id'];

    $board_query = "update boards set title = '$new_title' where id = '$board_id'";

    mysqli_query($conn, $board_query);

    $_SESSION['board'] = new Board($board_id, $new_title, '');

    if (isset($_FILES['update_background'])) {
        if ((@$_FILES['update_background']['type'] == 'image/jpeg' ||
                @$_FILES['update_background']['type'] == 'image/png' ||
                @$_FILES['update_background']['type'] == 'image/gif') &&
            (@$_FILES['update_background']['size'] < 1048576)
        ) {

            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $rand_dir_name = substr(str_shuffle($chars), 0, 15);
            mkdir("userdata/backgrounds/$rand_dir_name", 0777, true);

            if (file_exists("userdata/backgrounds/$rand_dir_name/" . @$_FILES["update_background"]["name"])) {
                echo @$_FILES["update_background"]["name"] . "Already exists";
            } else {
                move_uploaded_file(@$_FILES["update_background"]["tmp_name"], "userdata/backgrounds/$rand_dir_name/" . $_FILES["update_background"]["name"]);

                $background_name = @$_FILES["update_background"]["name"];

                //Delete old background file 
                $get_old_background_query = "select * from boards where id = '$board_id'";
                $result = mysqli_query($conn, $get_old_background_query);

                $row = mysqli_fetch_assoc($result);
                $old_backgr_dir = $row['background'];
                $dir_to_remove = "userdata/backgrounds/".substr($old_backgr_dir, 0, 15);
                echo $dir_to_remove;
                delete_directory($dir_to_remove);

                $update_background_query = "update boards set background = '$rand_dir_name/$background_name' where id = '$board_id'";

                mysqli_query($conn, $update_background_query);
            }
            $_SESSION['board'] = new Board($board_id, $new_title, $rand_dir_name . '/' . $background_name);
        }
    }
    header("Location: dashboard.php");
}


?>