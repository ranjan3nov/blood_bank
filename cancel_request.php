<?php
require_once('db/db.php');

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $update_query = "UPDATE blood_request SET status='cancelled' WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
