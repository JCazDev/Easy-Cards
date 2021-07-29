<?php include("./include/connection.php") ?>
<?php include("./include/functions.php") ?>
<?php
session_start();

$board_id = get_user_board_id($_SESSION['username'], $conn);


$card_id = $_POST['card_delete_id'];

//Delete old picture file 
$get_old_picture_query = "select * from cards where id = '$card_id'";
$result = mysqli_query($conn, $get_old_picture_query);

$row = mysqli_fetch_assoc($result);
$old_pic_dir = $row['picture'];
$dir_to_remove = "userdata/cardpics/" . substr($old_pic_dir, 0, 15);
echo $dir_to_remove;
delete_directory($dir_to_remove);

//Delete the card from db
$delete_card_query = "delete from cards where id = '$card_id' and board_id = '$board_id'";

mysqli_query($conn, $delete_card_query);


//make the cards above this card minus id by 1

$get_rest_cards_query = "select * from cards where id > '$card_id' and board_id = '$board_id'";
$result = mysqli_query($conn, $get_rest_cards_query);


while ($row = mysqli_fetch_assoc($result)) {
    echo $row['id'];

    $old_id = $row['id'];
    $new_id = $old_id - 1;

    $update_card_query = "update cards set id = '$new_id' where id = '$old_id' and board_id = '$board_id'";

    mysqli_query($conn, $update_card_query);
}

header("Location: dashboard.php");
