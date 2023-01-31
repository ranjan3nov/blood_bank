<?php
session_start();

if (isset($_SESSION['id'])) {
    if ($result[0]['user_type'] == 0)
        header("location:receiver.php");
    elseif ($result[0]['user_type'] == 1)
        header("location:hospital.php");
}

?>
<!doctype html>
<html lang="en">

<head>
    <?php
    include('partials/header.php')
    ?>
</head>

<body>
    <div class="wrapper">

        <?php include_once('partials/navbar.php') ?>

        <!-- login box -->
        <section>

            <div class="container h-100 d-flex align-items-center justify-content-center">
                <div class="w-75 shadow-lg m-4 p-4 rounded backgorund_1">
                    <h3 class="text-center">Register</h3>
                    <!-- Login Error -->
                    <?php
                    if (isset($_SESSION['loginError'])) {
                        echo '
                        <div class="alert alert-danger alert-dismissible show text-center" role="alert">
                            <strong>Error!! </strong> ' . $_SESSION['loginError'] . '.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        unset($_SESSION["loginError"]);
                    }
                    ?>
                    <form action="code_register.php" method="POST">
                        <!-- email -->
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fa fa-solid fa-user"></i></span>
                                    <input class="form-control" type="email" name="username" placeholder="Email id">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <div class="input-group mb-3">
                                    <label for="user_type" class="input-group-text">Select User</label>
                                    <select class="form-select" name="user_type" id="user_type" required>
                                        <option> Select</option>
                                        <option value="0">Blood Receiver</option>
                                        <option value="1">Hospital</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fa fa-solid fa-lock"></i>
                                    </span>

                                    <input class="form-control" type="password" name="password" id="password" placeholder="Password">

                                    <span class="input-group-text">
                                        <a href="#" class="text-dark" id="icon-click">
                                            <i class="fa fa-solid fa-eye" id="icon"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <!-- Cofirm Password -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fa fa-solid fa-lock"></i>
                                    </span>

                                    <input class="form-control" type="password" name="cnfm_password" placeholder="Confirm Password">

                                </div>
                                <!-- End Cofirm Password -->
                            </div>
                        </div>

                        <!-- End Password -->

                        <!-- For Receiver -->
                        <div id="receiver">

                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input class="form-control" type="text" name="receiver_name" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa fa-solid fa-phone"></i></span>
                                        <input class="form-control" type="number" name="receiver_phone" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div>



                            <div class="mb-3">
                                <label for="address" class="form-label ">Addres</label>
                                <textarea class="form-control" name="receiver_address" id="address"></textarea>
                            </div>

                            <div class="input-group mb-3">
                                <label for="blood_group" class="input-group-text">Blood Group</label>
                                <select class="form-select" name="receiver_blood_group" id="blood_group">
                                    <option value="A+" selected>A RhD positive (A+) </option>
                                    <option value="A-">A RhD negative (A-) </option>
                                    <option value="B+">B RhD positive (B+) </option>
                                    <option value="B-">B RhD negative (B-) </option>
                                    <option value="O+">O RhD positive (O+) </option>
                                    <option value="O-">O RhD negative (O-) </option>
                                    <option value="AB+">AB RhD positive (AB+) </option>
                                    <option value="AB-">AB RhD negative (AB-) </option>
                                </select>
                            </div>

                        </div>
                        <!-- End Receiver -->

                        <!-- Hospital -->
                        <div id="hospital">

                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <i class="fa fa-hospital-o"></i>

                                        </span>
                                        <input class="form-control" type="text" name="hospital_name" placeholder="Full Hospital Name">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa fa-solid fa-phone"></i></span>
                                        <input class="form-control" type="number" name="hospital_phone" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label ">Addres</label>
                                <textarea class="form-control" name="hospital_address" id="address"></textarea>
                            </div>

                        </div>
                        <!-- End Hospital -->

                        <div class="d-grid mb-3">
                            <input type="submit" name="register" class="btn btn-success btn-lg" rounded-0 value="Register">
                        </div>
                    </form>


                    <div class="text-center">
                        <a href="login.php" class="text-dark">Click here to Login</a>
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
            // Get the select element
            const userTypeSelect = $("#user_type");
            // Get the receiver and hospital divs
            const receiverDiv = $("#receiver");
            const hospitalDiv = $("#hospital");
            hospitalDiv.hide();
            receiverDiv.hide();

            // Add a change event listener to the select element
            userTypeSelect.on("change", function() {
                // Check the selected option value
                if ($(this).val() === "0") {
                    // Show the receiver div, hide the hospital div
                    receiverDiv.show();
                    hospitalDiv.hide();
                } else if ($(this).val() === "1") {
                    // Show the hospital div, hide the receiver div
                    hospitalDiv.show();
                    receiverDiv.hide();
                }
            });
        });

        $(document).ready(function() {
            $('#icon-click').click(function() {
                $('#icon').toggleClass('fa-eye-slash');

                let input = $("#password");
                if (input.attr('type') === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
    </div>
</body>

</html>