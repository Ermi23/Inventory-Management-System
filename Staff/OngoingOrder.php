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

if ( isset($_POST['approve'])) {
    $order_id = $_POST['id'];

    // Update the status of the order to 'confirmed'
    $updateOrderSql = "UPDATE orders SET status = 'Completed' WHERE order_id = ?";
    $updateOrderStmt = $conn->prepare($updateOrderSql);
    $updateOrderStmt->bind_param("i", $order_id);
    $updateOrderStmt->execute();

    echo "<script>window.location.href = 'OngoingOrder.php';</script>";
}

if (isset($_POST['decline'])) {
    $order_id = $_POST['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the status of the order to 'Cancelled'
        $updateOrderSql = "UPDATE orders SET status = 'Cancelled' WHERE order_id = ?";
        $updateOrderStmt = $conn->prepare($updateOrderSql);
        $updateOrderStmt->bind_param("i", $order_id);
        $updateOrderStmt->execute();

        // Retrieve the quantities of the canceled items from order_items
        $selectOrderItemsSql = "SELECT inventory_id, quantity FROM order_items WHERE order_id = ?";
        $selectOrderItemsStmt = $conn->prepare($selectOrderItemsSql);
        $selectOrderItemsStmt->bind_param("i", $order_id);
        $selectOrderItemsStmt->execute();
        $result = $selectOrderItemsStmt->get_result();

        // Update the inventory table with the new quantities
        while ($row = $result->fetch_assoc()) {
            $inventory_id = $row['inventory_id'];
            $canceled_quantity = $row['quantity'];

            $updateInventorySql = "UPDATE inventory SET quantity = quantity + ? WHERE inventory_id = ?";
            $updateInventoryStmt = $conn->prepare($updateInventorySql);
            $updateInventoryStmt->bind_param("ii", $canceled_quantity, $inventory_id);
            $updateInventoryStmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        echo "<script>window.location.href = 'OngoingOrder.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="col-sm-9 col-md-10 mt-5">
    <?php
    $sql = "
        SELECT 
            o.order_id, 
            o.customer_id, 
            u.username, 
            u.email, 
            COUNT(oi.order_item_id) AS no_of_products, 
            SUM(oi.quantity * oi.price) AS total_price
        FROM orders o
        JOIN users u ON o.customer_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.status = 'waiting_approval'
        GROUP BY o.order_id, o.customer_id, u.username, u.email
    ";
    $result = $conn->query($sql);
    $counter = 1;
    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">No of Products</th>
                <th scope="col">Total Price</th>
                <th scope="col">Aprove order</th>
                <th scope="col">Decline order</th>
                <th scope="col">View</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $counter++ . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td> <b>' . $row['no_of_products'] . ' </b> </td>';
            echo '<td>' . $row['total_price'] . ' Birr</td>';
            echo '<td>
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="' . $row['order_id'] . '">
                        <button type="submit" class="btn btn-success" name="approve"> <i class="fas fa-check"> </i> Approve </button>
                    </form>
                </td>';
            
                echo '<td>
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="' . $row['order_id'] . '">
                            <button type="submit" class="btn btn-danger" name="decline"> <i class="fas fa-times"> </i> Decline </button>
                    </form>
            </td>';
            echo '<td>                
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="id" value=' . $row['order_id'] . '>
                        <a href=" OrderDetail.php?id=' . $row['order_id'] . ' ?>">
                            <button type="button" class="btn bg-tan" name="view"> <i class="fas fa-eye"></i> View </button>
                        </a>
                    </form>
                </td>';

            echo '</tr>';
        }
        echo '</tbody>
        </table>';
    } else {
        echo '<p> <b> No orderis in pending status ... </b></p>';
    }
    ?>
</div>
</div>
</div>

<?php
include('../includes/footer.php');
?>