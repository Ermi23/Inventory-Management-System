<?php // Check if the form is submitted 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cake_and_beakery"; // Create connection 
    $conn = new mysqli($servername, $username, $password, $dbname); // Check connection 
}

if (isset($_REQUEST['submitrequest'])) {
    // Checking for Empty Fields
    try {
        $date = date('Y-m-d');
        if (
            ($_REQUEST['name'] == " ") ||
            ($_REQUEST['email'] == " ") ||
            ($_REQUEST['message'] == " ") ||
            ($_REQUEST['subject'] == " ") ||
            ($_REQUEST['phone'] == " ")
        ) {
            // Message displayed if required field is missing
            $msg = '<div class="alert alert-warning col-sm-6 mx-auto mt-2" role="alert"> Fill All Fields </div>';
        } else {
            // Assigning User Values to Variables
            $username = $_REQUEST['name'];
            $EID = $_REQUEST['email'];
            $message = $_REQUEST['message'];
            $subject = $_REQUEST['subject'];
            $phone = $_REQUEST['phone'];

            $sql = "INSERT INTO `contact_us`(`name`, `email`, `phone_number`, `Subject`, `Message`, `date`)
              VALUES ('$username', '$EID', '$phone', '$subject', '$message', '$date' )";

            if ($conn->query($sql) === TRUE) {
                // Message displayed on form submit success
                $msg = '<div class="alert alert-success col-sm-6 mx-auto mt-2" role="alert"> Sent Successfully. </div>';
            } else {
                $error = $conn->error;
                // Message displayed on form submit failure
                $msg = '<div class="alert alert-danger col-sm-6 mx-auto mt-2" role="alert" "mx-auto"> Unable to send. </div>';
            }
        }
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger col-sm-6 mx-auto mt-2" role="alert" "mx-auto"> Unable to send </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Inventory Management System
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="css/all.min.css">

    <!-- Custome CSS -->
    <link rel="stylesheet" href="css/custom.css">

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
        .nav-outline {
            background-color: #D2B48C; /* Light brown color */
            border-color: #D2B48C;
            color: black;
        }
    </style>

</head>

<body>
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-sm navbar-dark nav-outline pl-5 fixed-top d-flex justify-content-center align-items-center">
        <a href="index.php" class="navbar-brand">
            <span class="navbar-brand-text text-center text-dark font-weight-bold">Talem Cake And Beakery Inventory Management System</span>
        </a>
    </nav>
    <br>

    <form class="mx-5" action="" method="POST">
        <br><br>
        <br>
        <?php if (isset($msg)) {
            echo $msg;
        } ?>

        <h2 class="text-center">Contact Us :</h2>
        < <div class="form-row">
            <div class="form-group col-md-6 mx-auto">
                <h3 for="name">User Name :</h3>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            </div>

            <div class="form-row">

                <div class="form-group col-md-6 mx-auto">
                    <h3 for="email">Email :</h3>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 mx-auto">
                    <h3 for="subject">subject :</h3>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 mx-auto">
                    <h3 for="phone">phone :</h3>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 mx-auto">
                    <h3 for="message">Message :</h3>
                    <input type="text" class="form-control" id="message" name="message" required>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6 mx-auto">
                    <h3 for="subject">Subject :</h3>
                    <textarea class="form-control" id="subject" name="subject" rows="5" required></textarea>
                </div>

            </div>

            <div class="container ">
                <div class="mx-auto text-center">
                    <button type="submit" class="btn bg-tan" name="submitrequest"><i class="fas fa-paper-plane"></i> Send</button>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
                    <br>
                    <a href="index.php" class="btn btn-primary mt-2"><i class="fas fa-home"></i> Back to Home</a>
                </div>
            </div>

    </form>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.min.js"></script>
</body>

</html>
<script>
    function hideElements() {
        // Get references to the input box and submit button
        var inputBox = document.getElementById("inputBox");
        var submitButton = document.getElementById("searchsubmit");

        // Set the style property to hide the elements
        inputBox.style.display = "none";
        submitButton.style.display = "none";
    }
</script>