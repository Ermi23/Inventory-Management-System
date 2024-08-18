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

define('TITLE', 'Total Revenue by Month');
define('PAGE', 'generatereport');
include('../includes/header1.php');

?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
    <p class="bg-dark text-white p-2">Total Revenue by Month</p>
    <?php
    $total = 0;

    $sql = "
    SELECT 
        DATE_FORMAT(o.order_date, '%Y-%m') AS month,
        SUM(oi.quantity * oi.price) AS total_revenue
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY DATE_FORMAT(o.order_date, '%Y-%m');
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th>Month</th>
                <th>Total Revenue (Birr)</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td>' . $row['month'] . '</td>';
            echo '<td>' . $row['total_revenue'] . ' Birr</td>';
            $total += $row['total_revenue'];
        }

        echo '<tr>';
        echo '<th scope="row" class="align-middle">Total</th>';
        echo '<td></td>';
        echo '<td>' . $total . ' Birr</td>';
        echo '</tr>';
        echo '</tbody>
    </table>';
    echo'<a href="RevenueByMonth.php" onclick="window.print(); return false;">
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
