<?php include("./include/connection.php") ?>
<?php include("./include/functions.php") ?>

<?php
    session_start();

    $board_id = get_user_board_id($_SESSION['username'], $conn);

    if (isset($_FILES['card_pic']) && isset($_POST['card_title'])) {
        $card_pic_path = upload_image("userdata/cardpics", "card_pic");

        echo $board_id;
        $check_cards_query = "select * from cards where board_id = '$board_id'";

        $result = mysqli_query($conn, $check_cards_query);

        $card_id = $result->num_rows + 1;

        $card_title = $_POST['card_title'];

        $insert_card_query = "insert into cards (id, picture, title, board_id) values ('$card_id', '$card_pic_path', '$card_title', '$board_id')";

        mysqli_query($conn, $insert_card_query);
    }

    header("Location: dashboard.php");
?>