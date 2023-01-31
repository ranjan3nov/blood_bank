<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 1) {
    header("location:index.php");
}
require_once('db/db.php');

function getBloodRequests($conn, $user_id)
{
    $sql = "SELECT br.*, rd.name, rd.address, bd.blood_group, bd.units_available
            FROM blood_request br
            JOIN receiver_detail rd ON br.user_id = rd.user_id
            JOIN blood_detail bd ON br.blood_detail_id = bd.id
            WHERE br.hospital_id = (SELECT id FROM hospital_detail WHERE user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blood_requests = array();
    while ($row = $result->fetch_assoc()) {
        $blood_requests[] = $row;
    }
    return $blood_requests;
}
$blood_requests = getBloodRequests($conn, $_SESSION['id']);

// Working

// $sql = "SELECT id FROM `hospital_detail` WHERE `user_id` = ?";
// $stmt1 = $conn->prepare($sql);
// $stmt1->bind_param("i", $_SESSION['id']);
// $stmt1->execute();
// $result1 = $stmt1->get_result();
// $hospital_id = $result1->fetch_assoc();

// $sql1 = "SELECT * FROM `blood_request` WHERE `hospital_id` = ?";
// $stmt2 = $conn->prepare($sql1);
// $stmt2->bind_param("i", $hospital_id['id']);
// $stmt2->execute();
// $result2 = $stmt2->get_result();
// $blood_request_detail = $result2->fetch_all();


// foreach ($blood_request_detail as $blood_request_user_id) {
//     $sql = "SELECT name, address FROM `receiver_detail` WHERE `user_id` = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $blood_request_user_id[1]);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $user_detail = $result->fetch_all();
// }


// foreach ($blood_request_detail as $row) {
//     $sql1 = "SELECT blood_group, units_available FROM `blood_detail` WHERE `id` = ?";
//     $stmt2 = $conn->prepare($sql1);
//     $stmt2->bind_param("i", $row[3]);
//     $stmt2->execute();
//     $result2 = $stmt2->get_result();
//     $blood_detail = $result2->fetch_all();
// }

?>

<!doctype html>
<html lang="en">

<head>
    <?php
    include('partials/header.php')
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <div class="wrapper">

        <?php include_once('partials/navbar.php') ?>
        <!-- Login Error -->
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '
            <div class="w-50 mx-auto  mt-3 alert alert-danger alert-dismissible show text-center" role="alert">
                <strong>' . $_SESSION['login_error'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        ?>
        <section>

            <div class="container  align-items-center mt-5 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_2">
                    <h3 class="text-center">Blood Request Details</h3>
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead>
                                <th hidden></th>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Address</th>
                                <th>Blood Group</th>
                                <th>Available Unit</th>
                                <th>Requested Unit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($blood_requests as $i => $row)
                                    echo '
                                <tr>
                                    <td hidden>' . $row['id'] . '</td>
                                    <td>' . ($i + 1) . '</td>
                                    <td>' . $row['name'] . '</td>
                                    <td>' . $row['address'] . '</td>
                                    <td>' . $row['blood_group'] . '</td>
                                    <td>' . $row['units_available'] . '</td>
                                    <td>' . $row['request_unit'] . '</td>
                                    <td class="text-uppercase">' . $row['status'] . '</td>
                                    <td>
                                        <a href="#" class="btn btn-success">Aprrove</a>
                                        <a href="#" class="btn btn-danger">Deny</a>
                                    </td>
                                </tr>
                            ';
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>
        <?php
        include('partials/footer.php')
        ?>
    </div>

    <script>
        // Highlighting the add blood Button in the navbar
        $(document).ready(function() {
            $('#blood_request').addClass("active")
        });
        // Calling Data Tables
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>