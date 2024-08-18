<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo TITLE ?>
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">

    <!-- Custome CSS -->
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        .pic {
            text-align: center;
        }

        .pic img {
            max-width: 250px;
            height: 100px;
        }

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

        .vh-100 {
            height: 100vh;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Top Navbar -->
    <nav class="navbar navbar-dark fixed-top bg-tan flex-md-nowrap p-0 shadow">
        <div class="navbar-brand-wrapper">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0 text-dark" href="Dashboard.php">
                Inventory Management System
            </a>
            <a class="navbar-brand user-role text-dark" href="staffpage.php">
                <?php echo $currentuser . " | " . $role; ?>
            </a>
        </div>
    </nav>

    <!-- Side Bar -->
    <div class="container-fluid mb-5 " style="margin-top:40px;">
        <div class="row">
            <nav class="col-sm-2 bg-light sidebar py-5 d-print-none no-print">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">

                        <!-- <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'dashboard') {
                                                    echo 'active';
                                                } ?> " href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'profile') {
                                                    echo 'active';
                                                } ?>" href="../Customer/Profile.php">
                                <i class="fas fa-user"></i>
                                Profile <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'complains') {
                                                    echo 'active';
                                                } ?>" href="../Customer/complains.php">
                                <i class="fas fa-comment-dots"></i>
                                Send Complain <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'placeorder') {
                                                    echo 'active';
                                                } ?>" href="../Customer/PlaceOrder.php">
                                <i class="fas fa-shopping-cart"></i>
                                Place Order <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'orderhistory') {
                                                    echo 'active';
                                                } ?>" href="../Customer/OrderHistory.php">
                                <i class="fas fa-history"></i>
                                Order History
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>