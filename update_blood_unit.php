<?php
require_once('db/db.php');
session_start();

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 1) {
    header("location:index.php");
    exit();
}

$id = $_POST['id'];
$units_available = $_POST['units_available'];

if ((!isset($id)) || (!isset($units_available)) || $units_available < 0 || empty($id)) {
    echo "Inavlid Input";
} else {
    $sql = "UPDATE `blood_detail` SET `units_available` = ? WHERE `blood_detail`.`id` = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $units_available, $id);
    $stmt->execute();
    if ($stmt) {
        echo "Sucessfully Updated";
    } else {
        echo "Failed to Update";
    }
}
