<?php include("./include/connection.php") ?>
<?php include("./include/functions.php") ?>
<?php
session_start();

$board_id = get_user_board_id($_SESSION['username'], $conn);
$card_id = $_POST['card_edit_id'];

//Delete old picture file 
$get_old_picture_query = "select * from cards where id = '$card_id'";
$result = mysqli_query($conn, $get_old_picture_query);

$row = mysqli_fetch_assoc($result);
$old_pic_dir = $row['picture'];
$dir_to_remove = "userdata/cardpics/" . substr($old_pic_dir, 0, 15);
echo $dir_to_remove;
delete_directory($dir_to_remove);

$new_pic = upload_image("userdata/cardpics", "edit_card_pic");
$new_title = $_POST['edit_card_title'];

$update_card_query = "update cards set picture = '$new_pic', title = '$new_title' where id = '$card_id' && board_id = '$board_id'";

mysqli_query($conn, $update_card_query);

header("Location: dashboard.php");
