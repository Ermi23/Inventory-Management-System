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

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/custom.css">

  <title>Talem Cake abd Beakery</title>
  <style>
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
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top" style="margin-top:70px;">
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="myMenu">
      <ul class="navbar-nav pl-5 custom-nav mx-auto" style="margin-left:30%;">
        <li class="nav-item"><a href="index.php" class="nav-link"><i class="fa fa-home"></i><b> Home</b></a></li>
        <li class="nav-item"><a href="#About" class="nav-link pl-5"><i class="fa fa-info"></i><b> About</b></a></li>
        <li class="nav-item"><a href="contact _us.php" class="nav-link pl-5"><i class="fa fa-phone"></i> <b>Contact Us</b></a></li>
        <li class="nav-item"><a href="Login.php" class="nav-link pl-5"><i class="fas fa-sign-in-alt"></i> <b>Login</b></a></li>
        <li class="nav-item"><a href="CreateAccount.php" class="nav-link pl-5"><i class="fas fa-user-plus"></i> <b>Create Account</b></a></li>
      </ul>
    </div>
  </nav> <!-- End Navigation -->

  <!-- Start Header Jumbotron-->
  <header class="jumbotron back-image size" style="background-image: url(images/capture.png);">
   
  </header> <!-- End Header Jumbotron -->

  <div class="container" id="About">
    <!--Introduction Section-->
    <div class="jumbotron">
      <h3 class="text-center">Welcome to Talem Cake And Beakery Inventory Management System </h3>
      <p>
      At Talem, we take pride in our delectable cakes, pastries, and baked goods. Our inventory management system ensures that every ingredient is meticulously tracked, 
      from the finest cocoa powder to the freshest strawberries. Whether you’re a baker, a confectioner, or simply a dessert enthusiast, our system streamlines your inventory processes, 
      so you can focus on creating mouthwatering treats. Explore our digital shelves, manage stock levels, and keep your kitchen stocked with the finest ingredients. Welcome to the heart of Talem’s sweet world! 
    </div>
  </div>
  <!--Introduction Section End-->
 
  <!--Start Contact Us-->
  <div class="container" id="Contact">
    <!--Start Contact Us Container-->
    <h2 class="text-center mb-4">Contact US</h2> <!-- Contact Us Heading -->
    <!--<div class="row">-->

      <!--Start Contact Us Row-->
     
      <!-- End Contact Us 1st Column -->

    <div class="col-md-4 text-center">
        <!-- Start Contact Us 2nd Column-->
      <strong> Talem Cake And Beakery:</strong> <br>
       <p  class="text-center mb-4"> Address: Gondar, piassa, around Tewodros round about <br>
        Phone: 011 629 8154 <br></p>
      </div> <!-- End Contact Us 2nd Column-->
    </div> <!-- End Contact Us Row-->
  </div> <!-- End Contact Us Container-->
  <!-- End Contact Us -->

  <!-- Start Footer-->
  <footer class="container-fluid bg-dark text-white mt-5" style="border-top: 3px solid #DC3545;">
    <div class="container">
        <!-- Start Footer Container -->
      <div class="row py-3">
          <!-- Start Footer Row -->
        

        <div class="col-md-6 text-right">
            <!-- Start Footer 2nd Column -->
          <small> Designed by GC &copy; 2024.
          </small>
            <!-- <small class="ml-2"><a href="Admin/login.php">Admin Login</a></small> -->
        </div> <!-- End Footer 2nd Column -->
      </div> <!-- End Footer Row -->
    </div> <!-- End Footer Container -->
  </footer> <!-- End Footer -->

  <!-- Boostrap JavaScript -->
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/all.min.js"></script>
</body>

</html>