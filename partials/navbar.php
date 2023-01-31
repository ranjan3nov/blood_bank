<nav class="navbar navbar-dark navbar-expand-lg bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Blood Bank</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php


                if (isset($_SESSION['id'])) {

                    if ($_SESSION['user_type'] == 0) {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link " href="blood_receiver.php" id="home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="receiver_request_status.php" id="receiver_request">Request Status</a>
                            </li>
                            ';
                    } else if ($_SESSION['user_type'] == 1) {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link " href="hospital.php" id="home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="hospital_request.php" id="blood_request">Request</a>
                            </li>
                            ';
                    }
                } else {
                    echo '
                        <li class="nav-item">
                            <a class="nav-link " href="index.php" id="home">Home</a>
                        </li>';
                }
                ?>
               
            </ul>

            <?php
            if (isset($_SESSION['id']))
                echo ' 
                        <a href="logout.php" class="btn btn-warning me-2">Logout</a>';
            if (!isset($_SESSION['id']))
                echo '
                        <a href="login.php" class="btn btn-info me-2">Login</a>
                        <a href="register.php" class="btn btn-success me-4">Register</a>';
            ?>
        </div>
    </div>
</nav>