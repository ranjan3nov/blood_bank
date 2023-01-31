<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 0) {
    header("location:login.php");
}

require 'db/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $hospital_id = $_POST["hospital_id"];
    $blood_detail_id = $_POST["blood_detail_id"];
    $request_unit = $_POST["request_unit"];

    if (!isset($user_id) || !isset($hospital_id) || (!isset($blood_detail_id)) || !isset($request_unit) || empty($user_id) || empty($hospital_id) || empty($blood_detail_id) || empty($request_unit)) {
        $_SESSION["error"] = "Unable to Process Request Try Again !!";
        header("location:blood_receiver.php");
    } else {

        $sql = "INSERT INTO `blood_request` (user_id, hospital_id, blood_detail_id, request_unit) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user_id, $hospital_id, $blood_detail_id, $request_unit);
        $stmt->execute();
        if (!$stmt) {
            $_SESSION["error"] = "Unable to Process Request Try Again !!";
        } else {
            $_SESSION["success"] = "Scuccessfully Requested Wait for the response";
            header("location:blood_receiver.php");
        }
    }
}
