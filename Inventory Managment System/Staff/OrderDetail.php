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

define('TITLE', 'Pending Orders');
define('PAGE', 'ongoingorderlist');
include('../includes/header1.php');

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch order details and customer information
    $sql = "
        SELECT 
            o.order_id, 
            o.customer_id, 
            u.username, 
            u.email, 
            oi.order_item_id, 
            oi.quantity, 
            oi.price, 
            i.item_name 
        FROM orders o
        JOIN users u ON o.customer_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN inventory i ON oi.inventory_id = i.inventory_id
        WHERE o.order_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $customer_id = $order['customer_id'];
        $username = $order['username'];
        $email = $order['email'];

        echo '<br> <br> <div class="container mt-5">';
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<div class="receipt">';
        echo '<h2 class="text-center">Order Receipt</h2>';
        echo '<hr>';
        echo '<p><strong>Order ID:</strong> ' . $order_id . '</p>';
        echo '<p><strong>Customer Name:</strong> ' . $username . '</p>';
        echo '<p><strong>Email:</strong> ' . $email . '</p>';
        echo '<hr>';
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Item Name</th>';
        echo '<th>Quantity</th>';
        echo '<th>Unit Price (Birr)</th>';
        echo '<th>Total Price (Birr)</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $total_order_price = 0;
        do {
            $item_name = $order['item_name'];
            $quantity = $order['quantity'];
            $price = $order['price'];
            $total_price = $quantity * $price;

            $total_order_price += $total_price;

            echo '<tr>';
            echo '<td>' . $item_name . '</td>';
            echo '<td>' . $quantity . '</td>';
            echo '<td>' . $price . '</td>';
            echo '<td>' . $total_price . '</td>';
            echo '</tr>';
        } while ($order = $result->fetch_assoc());

        echo '<tr>';
        echo '<td colspan="3" class="text-right"><strong>Total Order Price:</strong></td>';
        echo '<td>' . $total_order_price . ' Birr</td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
        echo '<hr>';
        echo '<div class="text-center">';
        echo '<p>Thank you for your order! Talem Cake And Beakery</p>';
        echo '</div>';
        echo '<a href="OngoingOrder.php" onclick="window.print(); return false;">
                <form class="d-print-none">
                    <input class="btn bg-tan" type="submit" value="Print">
                </form>
            </a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<p>No details found for this order.</p>';
    }
} else {
    
    echo '<p> <br> <br> <br> <b> Order ID is missing.... </b> </p>';
}
?>

<?php
include('../includes/footer.php');
?>