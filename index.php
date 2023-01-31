<?php
session_start();
if (isset($_SESSION['id'])) {
    if ($_SESSION['user_type'] == 0)
        header("location:blood_receiver.php");
    elseif ($_SESSION['user_type'] == 1)
        header("location:hospital.php");
}

require_once('db/db.php');

$sql = "SELECT * FROM blood_detail 
        JOIN hospital_detail ON blood_detail.hospital_id = hospital_detail.id";
$stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_all();


// echo "<pre>";
// var_dump($detail);
// echo "</pre>";

?>
<!doctype html>
<html lang="en">

<head>
    <?php
    include('partials/header.php')
    ?>
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="wrapper ">
        <?php include_once('partials/navbar.php') ?>

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
        <section class="mb-3">

            <div class="container align-items-center mt-4 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_2">
                    <h3 class="text-center ">Available Blood Details</h3>
                    <div class="table-responsive">

                        <table class="table" id="myTable">
                            <thead>
                                <th>#</th>
                                <th>Blood Type</th>
                                <th>Available Unit</th>
                                <th>Hospital Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $iteration = 1;
                                foreach ($detail as $row) {
                                    if ($row[3] <= 0) {
                                        continue;
                                    }
                                    echo '<tr>
                                
                                <td >' . $iteration++ . '</td>
                                <td >' . $row[2] . '</td>
                                <td >' . $row[3] . '</td>
                                <td>' . $row[6] . '</td>
                                <td>' . $row[8] . '</td>
                                <td>
                                
                                <a class="btn btn-warning " href="blood_request_form.php">
                                Request Sample
                                </a>
                                
                                </td>
                                </tr>';
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

    <!-- Modal -->
    <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Blood Units</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <input type="hidden" name="id" value="">

                        <div class="form-group">
                            <label for="bloodType">Blood Type</label>
                            <input type="text" class="form-control" id="bloodType" name="blood_group" disabled>
                        </div>
                        <div class="form-group">
                            <label for="unitsAvailable">Units Available</label>
                            <input type="text" class="form-control" id="unitsAvailable" name="units_available" placeholder=" Enter new units available">
                        </div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-primary" id="update-blood-unit">Update</a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {
            // Adding the active class
            $('#home').addClass("active")
        });
        // Calling Data Tables
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>