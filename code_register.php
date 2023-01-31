<?php
session_start();
require_once('db/db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    // Check if the email already exists in the database
    $sql = "SELECT username FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email Already Exist";
        header("location:login.php");
    } else {
        $password = $_POST["password"];
        $cnfm_password = $_POST["cnfm_password"];
        $user_type = $_POST["user_type"];

        $receiver_name = $_POST["receiver_name"];
        $receiver_address = $_POST["receiver_address"];
        $receiver_phone = $_POST["receiver_phone"];
        $receiver_blood_group = $_POST["receiver_blood_group"];

        // Check If the Blood Type is Valid
        $validBloodTypes = array("A+", "B+", "AB+", "O+", "A-", "B-", "AB-", "O-");

        if (!in_array($receiver_blood_group, $validBloodTypes)) {
            $_SESSION['loginError'] = "Invalid Blood Types";
            header("location:register.php");
        }


        $hospital_name = $_POST["hospital_name"];
        $hospital_address = $_POST["hospital_address"];
        $hospital_phone = $_POST["hospital_phone"];

        // Check if the form values are empty
        if (!isset($username) || !isset($password) || !isset($user_type)) {
            $_SESSION['loginError'] = "Kindly Fill All the Details";
            header("location:register.php");
        }
        // Check if the Password Matches
        else if ($password != $cnfm_password) {
            $_SESSION['loginError'] = "Passwords Do Not Match";
            header("location:register.php");
        }
        // Check if the user is a receiver and the additional data fields are filled out
        else if ($user_type == 0 && (empty($receiver_name) || empty($receiver_address) || empty($receiver_phone) || empty($receiver_blood_group))) {
            $_SESSION['loginError'] = "Kindly Fill All the Details";
            header("location:register.php");
        }
        // Check if the user is a hospital and the additional data fields are filled out
        else if ($user_type == 1 && (empty($hospital_name) || empty($hospital_address) || empty($hospital_phone))) {
            $_SESSION['loginError'] = "Kindly Fill All the Details";
            header("location:register.php");
        } else {
            // Hash the password for security
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the user table
            $sql = "INSERT INTO user (username, password, user_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $password, $user_type);
            $stmt->execute();
            // Getting the user_id after inserting the data
            $user_id = $conn->insert_id;


            // Check the user type and insert the data into the corresponding table
            if ($user_type == 0) {
                // Insert the receiver data into the receiver table
                $sql = "INSERT INTO receiver_detail (user_id, name, blood_group, contact_number, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issis", $user_id, $receiver_name, $receiver_blood_group, $receiver_phone, $receiver_address);
                $stmt->execute();
                $_SESSION['success'] = true;
                header("location:login.php");
            } else if ($user_type == 1) {
                // Insert the hospital data into the hospital table
                $sql = "INSERT INTO hospital_detail (user_id, name, contact_number, address) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isis", $user_id, $hospital_name, $hospital_phone, $hospital_address);
                $stmt->execute();

                $hospital_id = $conn->insert_id;
                // Inset into the blood_detail Table with available unit 0
                $validBloodTypes = array("A+", "B+", "AB+", "O+", "A-", "B-", "AB-", "O-");
                foreach ($validBloodTypes as $bloodType) {
                    $sql = "INSERT INTO blood_detail (hospital_id, blood_group, units_available) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $zero = 0;
                    $stmt->bind_param("iss", $hospital_id, $bloodType, $zero);
                    $stmt->execute();
                }
                $_SESSION['success'] = true;
                header("location:login.php");
            }
        }
    }
} else {
    header("location:register.php");
}
