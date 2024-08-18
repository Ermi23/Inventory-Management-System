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


        .receipt {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .receipt h2 {
            margin-bottom: 20px;
        }

        .receipt hr {
            margin: 20px 0;
        }

        .receipt table {
            width: 100%;
            margin-bottom: 20px;
        }

        .receipt .text-right {
            text-align: right;
        }

        .receipt .text-center {
            text-align: center;
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
                            <a class="nav-link <?php if (PAGE == 'profile') {
                                                    echo 'active';
                                                } ?>" href="../staff/Profile.php">
                                <i class="fas fa-user"></i>
                                Profile <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'complainlist') {
                                                    echo 'active';
                                                } ?>" href="../staff/CustomerComplainList.php">
                                <i class="fas fa-comments"></i>
                                Customer Complaints <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'complains') {
                                                    echo 'active';
                                                } ?>" href="../staff/complains.php">
                                <i class="fas fa-comment-alt"></i>
                                Send Complaint <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'addinventory') {
                                                    echo 'active';
                                                } ?>" href="../staff/AddInventory.php">
                                <i class="fas fa-boxes"></i>
                                Add Inventory <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'inventorylist') {
                                                    echo 'active';
                                                } ?>" href="../staff/InventoryList.php">
                                <i class="fas fa-list-alt"></i>
                                Inventory List <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'ongoingorderlist') {
                                                    echo 'active';
                                                } ?>" href="../staff/OngoingOrder.php">
                                <i class="fas fa-clock"></i>
                                Pending Orders <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'completedorderlist') {
                                                    echo 'active';
                                                } ?>" href="../staff/CompletedOrderList.php">
                                <i class="fas fa-history"></i>
                                Orders History <span class="sr-only">(current)</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'generatereport') {
                                                    echo 'active';
                                                } ?>" href="../staff/GenerateReport.php">
                                <i class="fas fa-file-alt"></i>
                                Generate Report
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'upload') {
                                                    echo 'active';
                                                } ?>" href="../staff/Upload.php">
                                <i class="fas fa-upload"></i>
                                Upload
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'report') {
                                                    echo 'active';
                                                } ?>" href="ViewReport.php">
                                <i class="fas fa-table"></i>
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