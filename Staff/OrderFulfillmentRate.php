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

define('TITLE', 'Order Fulfillment Rate');
define('PAGE', 'generatereport');
include('../includes/header1.php');

?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">Order Fulfillment Rate</p>
    <?php
    $sql = "
    SELECT 
        o.order_id,
        o.order_date,
        o.status,
        SUM(oi.quantity * oi.price) AS total_order_value
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY o.order_id, o.order_date, o.status
    ORDER BY o.order_date;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">Order ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total Order Value (Birr)</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td>' . $row['order_date'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>' . $row['total_order_value'] . ' Birr</td>';
        }

        echo '</tbody>
    </table>';
    echo'<a href="OrderFulfillmentRate.php" onclick="window.print(); return false;">
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
