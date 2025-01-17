<?php
session_start();
include('../config/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT `tbl_user_id`, `password` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $user_id = $row['tbl_user_id'];

        //password hashing for security
        if ($password === $stored_password) {
            $_SESSION['user_id'] = $user_id;

            echo "
            <script>
                alert('Login Successfully!');
                window.location.href = '../public/user-page.php';
            </script>
            "; 
        } else {
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = 'login.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = 'login.php';
            </script>
            ";
    }
}
?>
