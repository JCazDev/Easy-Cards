<?php
include ("./include/BoardClass.php");
class UserClass
{
    public $username;
    public $errors;
    private $conn;
    private $member_since;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "easycards");
        session_start();
    }

    public function signup($username, $email, $password)
    {
        if (empty($username) || empty($email) || empty($password)) {
            $this->errors = "all the fields are required";
        } elseif ($this->check_user($username, $password) > 0) {
            $this->errors = "Username & email already exists";
        } elseif ($this->valid_email($email) == FALSE) {
            $this->errors = "Email address is not valid";
        } elseif ($this->check_password($password) == false) {
            return FALSE;
        } else {
            //Good to go in database
            $today_date = $this->current_date();
            $ip = $this->get_ip();
            $password = $this->secure_hash($password);

            $query = "insert into users (username, email, password, reg_date, ip) 
            values('$username','$email', '$password','$today_date','$ip')";

            mysqli_query($this->conn, $query);
            $this->errors = "<h1>Thank you $username registration done, now you can login.</h1> <a href = 'login.php'>Login</a>";
        }
    }

    public function check_user($username, $email)
    {
        $query = "select count(id) as total from users where username = '$username' and email = '$email' limit 1";
        $result = mysqli_query($this->conn, $query);

        while ($row = $result->fetch_assoc()) {
            return $row['total'];
        }
    }

    public function valid_email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return TRUE;
        }
    }

    public function check_password($password)
    {
        if (strlen($password < 8)) {
            $this->errors = "password must be more than 8 characters";
        } else if (!preg_match("#[0-9]+#", $password)) {
            $this->errors = "password must include at least one number";
        } else if (!preg_match("#[a-zA-Z]+#", $password)) {
            $this->errors = "password must include at least one letter";
        } else {
            return TRUE;
        }
    }

    public function get_ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->validate_ip($ip)) {
            return $ip;
        }
    }

    public function validate_ip($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return TRUE;
        }
    }

    public function current_date()
    {
        $date = new DateTime();
        return $date->format('Y/m/d/H:i:s');
    }

    public function secure_hash($password)
    {
        $secure = password_hash($password, PASSWORD_DEFAULT);
        return $secure;
    }

    public function login($username, $password)
    {
        $query = "select count(id) as total, username, password from users where (username = '$username' or email = '$username') limit 1";
        $result = mysqli_query($this->conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['total'] == 1 && $this->verify_password($password, $row['password']) == TRUE) {
                $_SESSION['username'] = $row['username'];

                if ($this->check_board_exists($username) == FALSE) {
                    $this->create_board($username);
                }

                $this->get_board($username);
                header("Location:dashboard.php");
            }
            return $row['total'];
        }
    }

    public function verify_password($password, $data_password)
    {
        if (password_verify($password, $data_password)) {
            return TRUE;
        }
    }

    public function create_board($username)
    {
        //fetch the created user and make a board for it
        $user_query = "select id, username from users where username = '$username' limit 1";
        
        $result = mysqli_query($this->conn, $user_query);
        
        $row = mysqli_fetch_assoc($result);
        
        $board_id = $row['id'];
        $board_title = $row['username'];

        $board_query = "insert into boards (id, title) values('$board_id', '$board_title')";
        mysqli_query($this->conn, $board_query);
    }

    public function get_board($username) {
        $user_query = "select id, username from users where username = '$username' limit 1";
        
        $result = mysqli_query($this->conn, $user_query);
        
        $row = mysqli_fetch_assoc($result);
        $board_id = $row['id'];
        
        $board_query = "select * from boards where id = '$board_id'";
        $result = mysqli_query($this->conn, $board_query);

        $row = mysqli_fetch_assoc($result);

        $board_title = $row['title'];
        $board_background = $row['background'];
        


        $board = new Board($board_id, $board_title, $board_background);

        $_SESSION['board'] = $board;
    }

    public function check_board_exists($username)
    {
        $user_query = "select id from users where username = '$username'";
        $result = mysqli_query($this->conn, $user_query);

        while ($row = mysqli_fetch_assoc($result)) {
            $board_id = $row['id'];
        }
        $board_query = "select * from boards where id = '$board_id'";

        $result2 = mysqli_query($this->conn, $board_query);

        if ($result2->num_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }
}
