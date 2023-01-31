<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 1) {
    header("location:index.php");
}
require_once('db/db.php');

$sql = "SELECT blood_detail.id, blood_detail.blood_group, blood_detail.units_available, hospital_detail.user_id FROM blood_detail 
        JOIN hospital_detail ON blood_detail.hospital_id = hospital_detail.id 
        WHERE hospital_detail.user_id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
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
</head>

<body>
    <div class="wrapper ">
        <?php include_once('partials/navbar.php') ?>

        <?php
        if (isset($_SESSION['error'])) {
            echo '
            <div class="w-50 mx-auto  mt-3 alert alert-danger alert-dismissible show text-center" role="alert">
                <strong>' . $_SESSION['error'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        ?>
        <section class="mb-3">

            <div class="container align-items-center mt-4 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_2">
                    <h3 class="text-center ">Available Blood Details</h3>
                    <table class="table">
                        <thead>
                            <th>#</th>
                            <th>Blood Type</th>
                            <th>Available Unit</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($detail as $i => $row) {

                                echo '<tr>

                                    <td >' . ($i + 1) . '</td>
                                    <td >' . $row[1] . '</td>
                                    <td>' . $row[2] . '</td>
                                    <td>
                                 
                                        <button type="button" class="btn btn-warning update-btn" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="' . $row[0] . '" data-blood-group="' . $row[1] . '" data-available-unit="' . $row[2] . '">
                                            Update
                                        </button>

                                    </td>
                                    </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <?php
        include('partials/footer.php')
        ?>
    </div>

    <!-- Modal -->
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

            $('.update-btn').click(function() {
                let id = $(this).attr('data-id');
                let bloodGroup = $(this).attr('data-blood-group');
                let availableUnit = $(this).attr('data-available-unit');
                $('#updateModal input[name="id"]').val(id);
                $('#updateModal input[name="blood_group"]').val(bloodGroup);
                $('#updateModal input[name="units_available"]').val(availableUnit);
            });

            $('#update-blood-unit').click(function(e) {
                e.preventDefault();
                let unitsAvailable = $('#unitsAvailable').val();
                if (unitsAvailable == "") {
                    alert("Blood Unit is invalid");
                } else {
                    let formData = $('#update-form').serialize();
                    $.ajax({
                        type: 'POST',
                        url: 'update_blood_unit.php',
                        data: formData,
                        success: function(response) {
                            alert(response);
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>