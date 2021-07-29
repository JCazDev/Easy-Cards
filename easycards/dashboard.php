<?php include("./include/connection.php") ?>
<?php include("./include/BoardClass.php"); ?>
<?php include("./include/header_dashboard.php") ?>
<?php
session_start();
$username = $_SESSION['username'];

$board_background = $_SESSION['board']->background;

if (isset($_POST['update_board'])) {
    $new_title = $_POST['update_title'];

    $user_query = "select id from users where username = '$username'";
    $result = mysqli_query($conn, $user_query);

    $row = mysqli_fetch_assoc($result);

    $board_id = $row['id'];

    $board_query = "update boards set title = '$new_title' where id = '$board_id'";

    mysqli_query($conn, $board_query);

    $_SESSION['board'] = new Board($board_id, $new_title, '');

    header("Location: dashboard.php");
}
?>

<style>
    .main {
        position: absolute;
        width: 100%;
        z-index: 1;
        padding: 0;
    }

    .title {
        background-color: var(--bg-color-dark-3);
        color: var(--white);
        text-align: center;
        padding: 2px 0;
    }

    .overlay-background {
        /* z-index: -1; */
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .overlay-background img {

        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-btn {
        border-radius: 7px;
        border: none;
        margin: 20px;
    }

    .invisible {
        display: none;
    }
   
</style>

<div class="title">
    <h2><?php echo $_SESSION['board']->title; ?></h2>
</div>
<!-- card area -->
<div class="main">
    <?php
    if ($_SESSION['board']->background != "" || $_SESSION['board']->background != $board_background) {
        $board_background = $_SESSION['board']->background;
        echo "<div class='overlay-background'>
            <img src='./userdata/backgrounds/$board_background'>
        </div>";
    }
    ?>

    <div class="container">
        <?php include("./include/display_cards.php") ?>
        <button class="card-btn" onclick="togglemenu('pop_up_add')">
            <div class="card" id="add">
                <div class="overlay">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </button>

        <div class="pop_up" id="pop_up_add">
            <div class="form">
                <h2>Add Card</h2>
                <div class="form-group">
                    <button class="close-btn" onclick="togglemenu('pop_up_add')">
                        <i class="fas fa-times"></i>
                    </button>
                    <form action="add_card.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="card_pic" required>
                        <input type="text" placeholder="Title" name="card_title" required>
                        <input class="btn" type="submit" value="Add" name="add_card">
                    </form>
                </div>
            </div>
        </div>

        <div class="pop_up" id="pop_up_edit_card">
            <div class="form">
                <h2>Edit Card</h2>
                <div class="form-group">
                    <button class="close-btn" onclick="toggle_edit_card(0)">
                        <i class="fas fa-times"></i>
                    </button>
                    <form action="edit_card.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="edit_card_pic" required>
                        <input type="text" placeholder="Title" name="edit_card_title" required>
                        <input class="invisible" id="card_edit_id" name="card_edit_id" type="text" value="0">
                        <div class="inline">
                            <input class="btn inline" type="submit" value="Update">
                    </form>
                    <form action="delete_card.php" method="POST">
                        <input class="invisible" id="card_delete_id" name="card_delete_id" type="text" value="0">
                        <input class="btn inline delete" type="submit" value="Delete">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="pop_up" id="pop_up_edit_board">
        <div class="form">
            <h2>Edit Board</h2>
            <div class="form-group">
                <button class="close-btn" onclick="togglemenu('pop_up_edit_board')">
                    <i class="fas fa-times"></i>
                </button>
                <form action="update_board.php" method="POST" enctype="multipart/form-data">
                    <h4>Background:</h4>
                    <input type="file" name="update_background">
                    <h4>Title:</h4>
                    <input type="text" value="<?php echo $_SESSION['board']->title ?>" name="update_title">
                    <div class="inline">
                        <input class="btn inline" type="submit" value="Update" name="update_board">
                        <input class="btn inline delete" type="submit" value="Clear">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    var pop_up_add = document.getElementById('pop_up_add');
    var pop_up_edit_card = document.getElementById('pop_up_edit_card');
    var card_edit_id = document.getElementById('card_edit_id');
    var card_delete_id = document.getElementById('card_delete_id');    
    var pop_up_edit_board = document.getElementById('pop_up_edit_board');


    function togglemenu(menu) {
        if (menu == 'pop_up_add')
            pop_up_add.classList.toggle('active');

        if (menu == 'pop_up_edit_board')
            pop_up_edit_board.classList.toggle('active');
    }

    function toggle_edit_card(cardnum) {
        pop_up_edit_card.classList.toggle('active');
        card_edit_id.value = cardnum;
        card_delete_id.value = cardnum;
    }
</script>

</body>

</html>