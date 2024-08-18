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

define('TITLE', 'Top 5 Best-Selling Products');
define('PAGE', 'generatereport');
include('../includes/header1.php');

?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">Top 5 Best-Selling Products</p>
    <?php
    $sql = "
    SELECT 
        i.item_name,
        SUM(oi.quantity) AS total_quantity_sold
    FROM order_items oi
    JOIN inventory i ON oi.inventory_id = i.inventory_id
    GROUP BY i.item_name
    ORDER BY total_quantity_sold DESC
    LIMIT 5;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td>' . $row['item_name'] . '</td>';
            echo '<td>' . $row['total_quantity_sold'] . '</td>';
        }

        echo '</tbody>
    </table>';
    echo'<a href="BestSellingProducts.php" onclick="window.print(); return false;">
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
