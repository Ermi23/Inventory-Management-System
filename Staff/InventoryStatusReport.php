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

define('TITLE', 'Inventory Status Report');
define('PAGE', 'generatereport');
include('../includes/header1.php');

?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">Inventory Status Report</p>
    <?php
    $sql = "
    SELECT 
        i.item_name,
        i.quantity AS current_stock,
        IFNULL(SUM(oi.quantity), 0) AS total_sold,
        (i.quantity - IFNULL(SUM(oi.quantity), 0)) AS stock_remaining
    FROM inventory i
    LEFT JOIN order_items oi ON i.inventory_id = oi.inventory_id
    GROUP BY i.item_name;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th>Product Name</th>
                <th>Current Stock</th>
                <th>Total Sold</th>
                <th>Stock Remaining</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td>' . $row['item_name'] . '</td>';
            echo '<td>' . $row['current_stock'] . '</td>';
            echo '<td>' . $row['total_sold'] . '</td>';
            echo '<td>' . $row['stock_remaining'] . '</td>';
        }

        echo '</tbody>
    </table>';
    echo'<a href="InventoryStatusReport.php" onclick="window.print(); return false;">
            <form class="d-print-none">
                <input class="btn bg-tan" type="submit" value="Print">
            </form>
        </a>';
    } else {
        echo "0 Result";
    }
?>

<?php
include('../includes/footer.php');
?>
