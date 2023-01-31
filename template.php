<?php

if (isset($_SESSION['id'])) {
    if ($result[0]['user_type'] == 0)
        header("location:receiver.php");
    elseif ($result[0]['user_type'] == 1)
        header("location:hospital.php");
}

require_once('db/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userid = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM `user` WHERE username =? AND password=?";

    $sql = $conn->prepare($query);
    $sql->bind_param("ss", $userid, $password);
    $sql->execute();
    $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($result) > 0) {
        // Verify the password
        if (password_verify($password, $result[0]['password'])) {
            // Password is correct, start a new session and save the user's ID
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $result[0]['id'];
            if ($result[0]['user_type'] == 0)
                header("location:receiver.php");
            elseif ($result[0]['user_type'] == 1)
                header("location:hospital.php");
        } else {
            // Password is incorrect, set an error message
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: login.php");
        }
    } else {
        $_SESSION['login_error'] = "User Not Found !!";
    }
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
        if (isset($_SESSION['login_error'])) {
            echo '
            <div class="w-50 mx-auto  mt-3 alert alert-danger alert-dismissible show text-center" role="alert">
                <strong>' . $_SESSION['login_error'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        ?>
        <!-- login box -->
        <section>

            <div class="container h-100 d-flex align-items-center m-5 p-5 justify-content-center">
                <div class="shadow-lg p-4 rounded" style="background-color: rgba(236, 213, 213, 0.301);">
                    <h3 class="text-center">Login</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
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
    </div>
    <?php
    include('partials/footer.php')
    ?>
    </div>

</body>

</html>