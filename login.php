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

        <!-- Login Error -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '
            <div class="w-50 mx-auto  mt-3 alert alert-danger alert-dismissible show text-center" role="alert">
                <strong>' . $_SESSION['error'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION['success'])) {
            echo '
            <div class="w-50 mx-auto alert alert-success alert-dismissible show text-center" role="alert">
            <strong>Successfully !!</strong> Registered.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            unset($_SESSION["success"]);
        }
        ?>
        <!-- login box -->
        <section>

            <div class="container h-100 d-flex align-items-center p-5 justify-content-center">
                <div class="shadow-lg p-4 rounded backgorund_1">
                    <h3 class="text-center">Login</h3>
                    <form action="code_login.php" method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa fa-solid fa-user"></i></span>
                            <input class="form-control" type="text" name="username" placeholder="Email id">
                        </div>
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
                        <div class="d-grid mb-3">
                            <input type="submit" name="" class="btn btn-success btn-lg" rounded-0 value="Login">
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="register.php" class="text-dark">Click here to Register</a>

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

</body>

</html>