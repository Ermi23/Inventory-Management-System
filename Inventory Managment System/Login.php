<?php
session_start(); // Start session at the very beginning

include('dbConnection.php'); // Include database connection

$msg = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rEmail = $_POST['rEmail'];
  $rPassword = $_POST['rPassword'];

  // Protect against SQL injection
  $rEmail = $conn->real_escape_string($rEmail);
  $rPassword = $conn->real_escape_string($rPassword);

  $sql = "SELECT * FROM users WHERE email='$rEmail' AND password='$rPassword'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // Fetch the row data
    $row = $result->fetch_assoc();

    $_SESSION['is_login'] = true;
    $_SESSION['rEmail'] = $rEmail;
    $_SESSION['role'] = $row['role'];

    // Redirect based on role
    if ($row['role'] == 'Manager') {
      header("Location: Manager/Dashboard.php");
    } elseif ($row['role'] == 'Staff') {
      header("Location: Staff/profile.php");
    } elseif ($row['role'] == 'Customer') {
      header("Location: Customer/profile.php");
    }
    exit;
  } else {
    $msg = '<div class="alert alert-warning mt-2 mx-auto text-center font-weight-bold" role="alert">Enter Valid Email and Password</div>';
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

  <style>
    .logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo img {
      max-width: 150px;
      height: auto;
    }

    .btn-outline {
      transition: background-color 0.3s ease;
    }

    .btn-outline:hover {
      background-color: #D2B48C;
      /* Light brown color */
      border-color: #D2B48C;
      color: white;
    }
  </style>
  <title>Login</title>
</head>

<body>
  <div class="mb-3 text-center mt-5" style="font-size: 30px;">
    <!--<i class="fas fa-stethoscope"></i>-->
    <span><b>LOGIN</b></span>
  </div>
  <p class="text-center" style="font-size: 20px;">

    <!--<i class="fas fa-user-secret text-primary"></i>-->
  </p>
  <div class="container-fluid mb-5">
    <div class="row justify-content-center custom-margin">
      <div class="col-sm-6 col-md-4">

        <div class="logo">
          <img src="images/logo.jpg" alt="Company Logo">
        </div>


        <form action="" class="shadow-lg p-4" method="POST">
          <?php if (isset($msg)) {
            echo $msg;
          } ?>
          <div class="form-group">
            <i class="fas fa-user"></i><label for="email" class="pl-2 font-weight-bold">Email</label><input type="email" class="form-control" placeholder="Enter Email" name="rEmail">
            <!--Add text-white below if want text color white-->

          </div>
          <div class="form-group">
            <i class="fas fa-key"></i><label for="pass" class="pl-2 font-weight-bold">Password</label><input type="password" class="form-control" placeholder="Enter Password" name="rPassword">
          </div>

          <div class="container">
            <button type="submit" class="btn btn-outline mt-3 btn-block shadow-sm font-weight-bold">Login</button>
          </div>

        </form>
        <div class="text-center"><a class="btn btn-info mt-3 shadow-sm font-weight-bold" href="index.php">Back
              to Home</a></div>
      </div>
    </div>
  </div>

  <!-- Boostrap JavaScript -->
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/all.min.js"></script>
</body>

</html>