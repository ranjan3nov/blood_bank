<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 0 || !isset($_SESSION["user_type"])) {
    header("location:login.php");
}
require_once('db/db.php');

$sql = "SELECT * FROM blood_detail 
        JOIN hospital_detail ON blood_detail.hospital_id = hospital_detail.id WHERE blood_detail.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_all();

$sql1 = "SELECT blood_group FROM receiver_detail where user_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $_SESSION['id']);
$stmt1->execute();
$result1 = $stmt1->get_result();
$user_blood_group = $result1->fetch_assoc();

if ($detail[0][2] != $user_blood_group['blood_group']) {
    $_SESSION["error"] = "Unable to Process !! Request Sample Does Not matches Your Blood Group  !!";
    header("location:blood_receiver.php");
}

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
        <section class="mb-3">

            <div class="container h-100 d-flex align-items-center m-5 p-5 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_1">
                    <h3 class="text-center">Request Blood</h3>
                    <form action="code_request_blood.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                        <input type="hidden" name="hospital_id" value="<?php echo $detail[0][1]; ?>">
                        <input type="hidden" name="blood_detail_id" value="<?php echo $_GET['id']; ?>">
                        <div class="form-group">
                            <label for="bloodType">Blood Type</label>
                            <input type="text" class="form-control" name="blood_group" value="<?php echo $detail[0][2] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="bloodType">Units available</label>
                            <input type="text" class="form-control" name="blood_group" value="<?php echo $detail[0][4] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="unitsAvailable">Request</label>
                            <input type="number" class="form-control" max=<?php echo $detail[0][4] ?> name="request_unit" placeholder=" Enter new units available">
                        </div>
                        <div class="mt-2 text-center">
                            <input type="submit" class="btn btn-success">
                        </div>
                    </form>

                </div>
            </div>
        </section>
        <?php
        include('partials/footer.php')
        ?>
    </div>

</body>

</html>