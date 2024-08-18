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
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'dashboard') {
                                                    echo 'active';
                                                } ?> " href="../Manager/dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'profile') {
                                                    echo 'active';
                                                } ?>" href="../Manager/Profile.php">
                                <i class="fas fa-user"></i>
                                Profile <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'addstaff') {
                                                    echo 'active';
                                                } ?>" href="../Manager/AddStaff.php">
                                <i class="fas fa-user-plus"></i>
                                Add User
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'stafflist') {
                                                    echo 'active';
                                                } ?>" href="Stafflist.php">
                                <i class="fas fa-users"></i>
                                List Of User
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'messagelist') {
                                                    echo 'active';
                                                } ?>" href="../Manager/MessageList.php">
                                <i class="fas fa-envelope"></i>
                                Visitor Message
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'complainlist') {
                                                    echo 'active';
                                                } ?>" href="../Manager/ComplainList.php">
                                <i class="fas fa-exclamation-circle"></i>
                                Complain List
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'report') {
                                                    echo 'active';
                                                } ?>" href="../Manager/ViewReport.php">
                                <i class="fas fa-chart-bar"></i>
                                View Report
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