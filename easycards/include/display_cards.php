<?php include("./include/connection.php") ?>
<?php include("./include/functions.php") ?>
<?php

$board_id = get_user_board_id($_SESSION['username'], $conn);

$get_cards_query = "select * from cards where board_id = $board_id";

$result = mysqli_query($conn, $get_cards_query);

$card_num = 1;


while ($row = mysqli_fetch_assoc($result)) {
    $card_pic_path = "userdata/cardpics/".$row['picture'];
    $card_title = $row['title'];
    echo "<button class='card-btn' onclick='toggle_edit_card($card_num)'>
    <div class='card'>
        <div class='overlay'>
            <i class='far fa-edit'></i>
        </div>
        <div class='pic'>
            <img src='$card_pic_path' alt=''>
        </div>
        <h4>$card_title</h4>
    </div>
</button>";
$card_num += 1;
}

?>

