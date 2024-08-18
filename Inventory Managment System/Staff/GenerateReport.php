<?php

session_start();

if (!isset($_SESSION['is_login'])) {
    header("Location: ../login.php");
    exit;
}

include('../dbConnection.php');

$rEmail = $_SESSION['rEmail'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $currentuser = $user['username'];
    $role = $user['role'];
} else {
    echo '<script>alert("No such user exists")</script>';
}

define('TITLE', 'Monthly Sales Report');
define('PAGE', 'generatereport');
include('../includes/header1.php');

?>

<div class="col-sm-9 col-md-10">
    <div class="row mx-5 text-center">
        <!-- Total Sales by Product -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-tan mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-chart-bar fs-3 text-white"></span></h1> Total Sales by Product
                </div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $sql = "SELECT COUNT(DISTINCT item_name) as product_count FROM inventory";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $productCount = $row['product_count'];
                            echo $productCount;
                        }
                        ?>
                    </h4>
                    <a class="btn text-white" href="TotalSalesByProduct.php">View</a>
                </div>
            </div>
        </div>
        <!-- Total Revenue by Month -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-calendar-alt fs-3 text-white"></span></h1> Total Revenue by Month
                </div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $sql = "SELECT COUNT(DISTINCT DATE_FORMAT(order_date, '%Y-%m')) as month_count FROM orders";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $monthCount = $row['month_count'];
                            echo $monthCount;
                        }
                        ?>
                    </h4>
                    <a class="btn text-white" href="TotalRevenueByMonth.php">View</a>
                </div>
            </div>
        </div>
        <!-- Inventory Status Report -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-box-open fs-3 text-white"></span></h1> Inventory Status Report
                </div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $sql = "SELECT COUNT(*) as inventory_count FROM inventory";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $inventoryCount = $row['inventory_count'];
                            echo $inventoryCount;
                        }
                        ?>
                    </h4>
                    <a class="btn text-white" href="InventoryStatusReport.php">View</a>
                </div>
            </div>
        </div>
        <!-- Customer Order Details -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-receipt fs-3 text-white"></span></h1> Customer Order Details
                </div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $sql = "SELECT COUNT(DISTINCT customer_id) as customer_order_count FROM orders";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $customerOrderCount = $row['customer_order_count'];
                            echo $customerOrderCount;
                        }
                        ?>
                    </h4>
                    <a class="btn text-white" href="CustomerOrderDetails.php">View</a>
                </div>
            </div>
        </div>
        <!-- Top 5 Best-Selling Products -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-star fs-3 text-white"></span></h1> Top 5 Best-Selling Products
                </div>
                <div class="card-body">
                    <h4 class="card-title">Top 5 Products</h4>
                    <a class="btn text-white" href="BestSellingProducts.php">View</a>
                </div>
            </div>
        </div>
        <!-- Order Fulfillment Rate -->
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header">
                    <h1><span class="fas fa-tasks fs-3 text-white"></span></h1> Order Fulfillment Rate
                </div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php
                        $sql = "SELECT COUNT(order_id) as order_count FROM orders";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $orderCount = $row['order_count'];
                            echo $orderCount;
                        }
                        ?>
                    </h4>
                    <a class="btn text-white" href="OrderFulfillmentRate.php">View</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('../includes/footer.php');
?>