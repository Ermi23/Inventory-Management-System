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
    $user_id = $user['user_id'];
} else {
    echo '<script>alert("No such user exists")</script>';
}

define('TITLE', 'Order History');
define('PAGE', 'orderhistory');
include('../includes/header2.php');

?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!--Table-->
    <p class="bg-dark text-white p-2">Order History </p>
    <?php
    $total = 0;
    // Fetch the order history for the logged-in user
    $sql = "
    SELECT 
        o.order_id, 
        o.order_date, 
        o.status,
        COUNT(oi.order_item_id) AS no_of_products, 
        SUM(oi.quantity * oi.price) AS total_price
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.customer_id = ?
    GROUP BY o.order_id, o.order_date, o.status
    ORDER BY o.order_date DESC;
";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th>Order Date</th>
                <th>No. of Products</th>
                <th>Total Price (Birr)</th>
                <th> status </th>
                <th class = "d-print-none"> Action </th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1; // Initialize counter for ID column
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td>' . $row['order_date'] . '</td>';
            echo '<td>' . $row['no_of_products'] . '</td>';
            echo '<td>' . number_format((float)$row['total_price']) . ' Birr</td>';
            $total += $row['total_price'];
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>
                    <form action="Receipt.php" method="GET" class="d-print-none d-inline">
                        <input type="hidden" name="id" value=' . $row['order_id'] . '>
                        <button type="submit" class="btn btn-primary">View</button>
                    </form>
                  </td>';
            echo '</tr>';
        }

        echo '<tr>';
        echo '<th scope="row" class="align-middle">Total</th>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>' . number_format((float)$total) . ' Birr</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
        echo '</tbody>
    </table>';
        echo '<a href="OngoingOrder.php" onclick="window.print(); return false;">
            <form class="d-print-none">
                <input class="btn bg-tan" type="submit" value="Print">
            </form>
        </a>';
    } else {
        echo "0 Result";
    }
    ?>
</div>

<?php
include('../includes/footer.php');
?>