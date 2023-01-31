<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 0) {
    header("location:login.php");
}

require_once('db/db.php');

$sql = "SELECT blood_request.*, hospital_detail.name, receiver_detail.blood_group FROM blood_request 
        JOIN hospital_detail ON blood_request.hospital_id = hospital_detail.id
        JOIN receiver_detail ON blood_request.user_id = receiver_detail.user_id
        WHERE blood_request.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_all();

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
      
        <section>

            <div class="container  align-items-center mt-5 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_2">
                <?php
                    if(!empty($detail)){
                    echo ' <h3 class="text-center">Request Status for Blood Group'. $detail[0][8] .')</h3> ';
                    }
                    else{
                        echo '<h3 class="text-center">NO Request Found</h3>';
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead>
                                <th hidden>id</th>
                                <th>#</th>
                                <th>Hospital Name</th>
                                <th>Request Unit</th>
                                <th>Request Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($detail as $i => $row) {
                                    echo ' 
                                <tr>
                                    <td hidden>' . $row[0] . '</td>
                                    <td>' . ($i + 1) . '</td>
                                    <td>' . $row[7] . '</td>
                                    <td>' . $row[4] . '</td>
                                    <td class="text-uppercase">' . $row[5] . '</td>
                                    <td>';
                                    if (($row[5] == "no update") || ($row[5] == "accepted")) {
                                        echo '<a href="#" class="btn btn-danger cancel-request">Cancel</a>';
                                    }
                                    echo '
                                    </td>
                                </tr>
                                ';
                                }
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
        $(document).ready(function() {
            // Highlighting the add blood Button in the navbar
            $('#receiver_request').addClass("active")
            // Calling Data Tables
            $('#myTable').DataTable();
        });

        $(document).on('click', '.cancel-request', function() {
            // get the request id from the table
            let request_id = $(this).closest('tr').find('td:first-child').text();
            $.ajax({
                url: 'cancel_request.php',
                type: 'POST',
                data: {
                    request_id: request_id
                },
                success: function(response) {
                    if (response.trim() == "success") {
                        location.reload();
                        alert('Request Cancelled!');
                    } else {
                        alert('Something went wrong. Please try again.');
                        location.reload();
                    }
                }
            });
        });
    </script>
</body>

</html>