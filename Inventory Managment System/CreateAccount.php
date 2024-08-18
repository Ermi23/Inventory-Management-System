<?php

include('dbConnection.php');


if (isset($_REQUEST['submitrequest'])) {
    // Checking for Empty Fields
    try {
        if (
            ($_REQUEST['username'] == " ") ||
            ($_REQUEST['email'] == " ") ||
            ($_REQUEST['password'] == " ") ||
            ($_REQUEST['role'] == " ")
        ) {
            // Message displayed if required field is missing
            $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
        } else {
            // Assigning User Values to Variables
            $username = $_REQUEST['username'];
            $EID = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            $role = $_REQUEST['role'];

            $sql = "INSERT INTO users(`username`, `password`, `email`, `role`) 
          VALUES ('$username', '$password', '$EID', '$role')";

            if ($conn->query($sql) === TRUE) {
                // Message displayed on form submit success
                $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Registered Successfully. </div>';
            } else {
                $error = $conn->error;
                // Message displayed on form submit failure
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register  Duplicate Entry For Email. </div>';
            }
        }
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register  Duplicate Entry For Email. </div>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="css/all.min.css">

    <!-- Custome CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <title>Talem Cake abd Beakery</title>

    <!-- Custom CSS -->
    <style>
        .bg-tan {
            background-color: #D2B48C;
            /* Light brown color */
            border-color: #D2B48C;
            color: black;
        }

        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .navbar-brand {
            white-space: nowrap;
            /* Prevent text wrapping */
            text-overflow: ellipsis;
            /* Add ellipsis for overflow text */
        }

        .user-role {
            margin-left: auto;
            padding-right: 15px;
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        .active {
            color: #D2B48C;
            background-color: #160d0e;
        }

        a:hover {
            color: #D2B48C;
        }

        /* Change Footer Icon Link Color */
        .fi-color {
            color: #D2B48C;
        }

        fi-color:hover {
            color: #D2B48C;
        }
    </style>
</head>

<body>
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-tan pl-5 fixed-top d-flex justify-content-center align-items-center">
        <a href="index.php" class="navbar-brand">
            <span class="navbar-brand-text text-center text-dark font-weight-bold">Talem Cake And Beakery Inventory Management System</span>
        </a>
    </nav>

    <br> <br>

    <div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
        <h3 class="text-center">Create Account As A Customer</h3>
        <br> <br>
        <form action="" method="POST">
            <?php if (isset($msg)) { ?>
                <div class="text-center mx-auto">
                    <div class="d-flex justify-content-center">
                        <?php echo $msg; ?>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group col-md-6 mx-auto">
                <label for="username"><b>Full Name :</b></label>
                <input required type="text" class="form-control" id="username" name="username">
            </div>

            <div class="form-group col-md-6 mx-auto">
                <label for="email"><b>Email :</b></label>
                <input required type="email" class="form-control" id="email" name="email">
            </div>

            <div class="form-group col-md-6 mx-auto">
                <label for="password"><b>Password :</b></label>
                <input required type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group col-md-6 mx-auto">
                <label for="role"><b>Role</b></label>
                <input type="text" class="form-control text-center" id="role" name="role" placeholder="Customer" value="Customer" readonly>
            </div>
            <br>

            <div class="text-center">
                <button type="submit" class="btn bg-tan" name="submitrequest"><i class="fas fa-save"></i> CREATE</button>
                &nbsp; &nbsp;
                <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
            </div>
        </form>
        <div class="text-center"><a class="btn btn-info mt-3 shadow-sm font-weight-bold" href="index.php">Back
                to Home</a>
        </div>
    </div>
    <?php
    $conn->close();
    ?>

    <!-- Bootstrap JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.min.js"></script>

</body>

</html>