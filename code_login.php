<?php
session_start();
require_once('db/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the form values are empty
    if (!isset($username) || !isset($password) || empty($username) || empty($password)) {
        $_SESSION['error'] = "Kindly Fill All the Details";
        header("location:login.php");
    } else {
        // Check if the user exists in the user table
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            // Check if the password matches the hashed password in the database
            if (password_verify($password, $user['password'])) {
                // Log the user in and redirect them to their respective page
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] == 0) {
                    header("location:blood_receiver.php");
                } else if ($user['user_type'] == 1) {
                    header("location:hospital.php");
                }
            } else {
                $_SESSION['error'] = "Invalid Credentials";
                header("location:login.php");
            }
        } else {
            $_SESSION['error'] = "User Not Found";
            header("location:login.php");
        }
    }
} else {
    header("location:login.php");
}
