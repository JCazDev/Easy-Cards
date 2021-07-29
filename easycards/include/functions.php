<?php

function get_user_board_id($username, $conn)
{

    $user_query = "select id from users where username = '$username'";
    $result = mysqli_query($conn, $user_query);

    $row = mysqli_fetch_assoc($result);

    return $row['id'];
}

function upload_image($folder_path, $filekey)
{
    if (isset($_FILES[$filekey])) {
        if ((@$_FILES[$filekey]['type'] == 'image/jpeg' ||
                @$_FILES[$filekey]['type'] == 'image/png' ||
                @$_FILES[$filekey]['type'] == 'image/gif') &&
            (@$_FILES[$filekey]['size'] < 1048576)
        ) {

            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $rand_dir_name = substr(str_shuffle($chars), 0, 15);
            mkdir("$folder_path/$rand_dir_name", 0777, true);

            if (file_exists("$folder_path/$rand_dir_name/" . @$_FILES[$filekey]["name"])) {
                echo @$_FILES[$filekey]["name"] . "Already exists";
            } else {
                move_uploaded_file(@$_FILES[$filekey]["tmp_name"], "$folder_path/$rand_dir_name/" . $_FILES[$filekey]["name"]);

                return "$rand_dir_name/" . @$_FILES[$filekey]["name"];
            }
        }
    }
}

function delete_directory($dirname)
{
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
