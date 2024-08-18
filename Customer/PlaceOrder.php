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

define('TITLE', 'Place Order');
define('PAGE', 'placeorder');
include('../includes/header2.php');

if (isset($_POST['add_to_cart'])) {
    $inventory_id = $_POST['inventory_id'];
    $user_id = $user['user_id'];
    $order_date = date('Y-m-d');

    // Check if there is a pending order for the current user and date
    $checkOrderSql = "SELECT * FROM orders WHERE customer_id = ? AND order_date = ? AND status = 'Pending'";
    $checkOrderStmt = $conn->prepare($checkOrderSql);
    $checkOrderStmt->bind_param("is", $user_id, $order_date);
    $checkOrderStmt->execute();
    $orderResult = $checkOrderStmt->get_result();

    if ($orderResult->num_rows == 0) {
        // Create a new order if no pending order exists
        $createOrderSql = "INSERT INTO orders (customer_id, order_date, status) VALUES (?, ?, 'Pending')";
        $createOrderStmt = $conn->prepare($createOrderSql);
        $createOrderStmt->bind_param("is", $user_id, $order_date);
        $createOrderStmt->execute();
        $order_id = $conn->insert_id;
    } else {
        // Get the existing order id
        $order = $orderResult->fetch_assoc();
        $order_id = $order['order_id'];
    }

    // Check if the item is already in the order
    $checkItemSql = "SELECT * FROM order_items WHERE order_id = ? AND inventory_id = ?";
    $checkItemStmt = $conn->prepare($checkItemSql);
    $checkItemStmt->bind_param("ii", $order_id, $inventory_id);
    $checkItemStmt->execute();
    $itemResult = $checkItemStmt->get_result();

    if ($itemResult->num_rows == 0) {
        // Add new item to the order_items table
        $inventorySql = "SELECT * FROM inventory WHERE inventory_id = ?";
        $inventoryStmt = $conn->prepare($inventorySql);
        $inventoryStmt->bind_param("i", $inventory_id);
        $inventoryStmt->execute();
        $inventory = $inventoryStmt->get_result()->fetch_assoc();
        $price = $inventory['unit_price'];

        $insertItemSql = "INSERT INTO order_items (order_id, inventory_id, quantity, price) VALUES (?, ?, 1, ?)";
        $insertItemStmt = $conn->prepare($insertItemSql);
        $insertItemStmt->bind_param("iid", $order_id, $inventory_id, $price);
        $insertItemStmt->execute();
    } else {
        // Update quantity of existing item in the order_items table
        $updateItemSql = "UPDATE order_items SET quantity = quantity + 1 WHERE order_id = ? AND inventory_id = ?";
        $updateItemStmt = $conn->prepare($updateItemSql);
        $updateItemStmt->bind_param("ii", $order_id, $inventory_id);
        $updateItemStmt->execute();
    }

    // Reduce quantity in inventory
    $updateInventorySql = "UPDATE inventory SET quantity = quantity - 1 WHERE inventory_id = ?";
    $updateInventoryStmt = $conn->prepare($updateInventorySql);
    $updateInventoryStmt->bind_param("i", $inventory_id);
    $updateInventoryStmt->execute();
}

// Display the inventory items
?>

<div class="col-sm-7">
    <br><br>
    <?php
    $sql = "SELECT * FROM inventory WHERE quantity > 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="container">';
        echo '<div class="row">';
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            if ($counter % 3 == 0 && $counter != 0) {
                echo '</div><div class="row">';
            }
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card">';
            echo '<img src="../Product Image/' . $row["picture"] . '" class="card-img-top img-fluid" style="height: 200px; object-fit: cover;" alt="Product Image">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title"><b>Name: ' . $row["item_name"] . '</b></h5>';
            echo '<p class="card-text"><b>Unit Price: ' . $row["unit_price"] . ' Birr</b></p>';
            echo '<p class="card-text"><b>Quantity Left: ' . $row["quantity"] . '</b></p>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="inventory_id" value="' . $row["inventory_id"] . '">';
            echo '<button type="submit" name="add_to_cart" class="btn btn-dark">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            $counter++;
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo "0 Results";
    }
    ?>
</div>

<div class="col-sm-3">
    <br><br>
    <div class="p-3 bg-light border">
        <h4 class="text-center">My Order</h4>
        <hr>
        <?php
        // Display user's order
        $orderSql = "SELECT * FROM orders WHERE customer_id = ? AND status = 'Pending'";
        $orderStmt = $conn->prepare($orderSql);
        $orderStmt->bind_param("i", $user['user_id']);
        $orderStmt->execute();
        $orderResult = $orderStmt->get_result();

        if ($orderResult->num_rows > 0) {
            $order = $orderResult->fetch_assoc();
            $order_id = $order['order_id'];

            $orderItemsSql = "SELECT oi.*, i.item_name, i.picture FROM order_items oi JOIN inventory i ON oi.inventory_id = i.inventory_id WHERE oi.order_id = ?";
            $orderItemsStmt = $conn->prepare($orderItemsSql);
            $orderItemsStmt->bind_param("i", $order_id);
            $orderItemsStmt->execute();
            $orderItemsResult = $orderItemsStmt->get_result();

            while ($item = $orderItemsResult->fetch_assoc()) {
                $total = $item['quantity'] * $item['price'];
                echo '<div class="row mb-2">';
                echo '<div class="col-sm-3">';
                echo '<img src="../Product Image/' . $item["picture"] . '" class="img-fluid" alt="Product Image">';
                echo '</div>';
                echo '<div class="col-sm-9">';
                echo '<p>' . $item['item_name'] . ' - ' . $item['quantity'] . ' x ' . $item['price'] . ' Birr</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="order_item_id" value="' . $item['order_item_id'] . '">';
                echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
                echo '<div class="d-flex justify-content-center">';
                echo '<button type="submit" name="decrease_quantity" class="btn btn-sm btn-warning">-</button>';
                echo '&nbsp;&nbsp;&nbsp;';
                echo '<span class="form-control" style="width: 50px; text-align: center;">' . $item['quantity'] . '</span>';
                echo '&nbsp;&nbsp;&nbsp;';
                echo '<button type="submit" name="increase_quantity" class="btn btn-sm btn-success">+</button>';
                echo '</div>';
                echo '<span class="form-control text-center mx-auto" style="width: 200px;">' . $total . '</span>';
                echo '</form>';
                echo '</div>';
                echo '</div><hr>';
            }

            echo '<form method="post" action="">';
            echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
            echo '<button type="submit" class="btn btn-primary btn-block" name="confirm_order">Confirm Order</button>';
            echo '<button type="submit" class="btn btn-danger btn-block" name="cancel_order">Cancel Order</button>';
            echo '</form>';

        } else {
            echo '<p text-center> <b> No Pending Order Left. </b> </p>';
        }


        // Handle form submissions from confirm order and cancel order
        if (isset($_POST['confirm_order'])) {
            $order_id = $_POST['order_id'];
        
            // Update order_items status to 'waiting_approval'
            $updateStatusSql = "UPDATE orders SET status = 'waiting_approval' WHERE order_id = ?";
            $updateStatusStmt = $conn->prepare($updateStatusSql);
            $updateStatusStmt->bind_param("i", $order_id);
            $updateStatusStmt->execute();

            echo "<script>window.location.href = 'PlaceOrder.php';</script>";
        }
        
        if (isset($_POST['cancel_order'])) {
            $order_id = $_POST['order_id'];
        
            // Delete rows with the specific order_id
            $deleteOrderItemsSql = "DELETE FROM order_items WHERE order_id = ?";
            $deleteOrderItemsStmt = $conn->prepare($deleteOrderItemsSql);
            $deleteOrderItemsStmt->bind_param("i", $order_id);
            $deleteOrderItemsStmt->execute();
        
            // Delete the order itself
            $deleteOrderSql = "DELETE FROM orders WHERE order_id = ?";
            $deleteOrderStmt = $conn->prepare($deleteOrderSql);
            $deleteOrderStmt->bind_param("i", $order_id);
            $deleteOrderStmt->execute();

            echo "<script>window.location.href = 'PlaceOrder.php';</script>";
        }        

        if (isset($_POST['decrease_quantity'])) {
            $order_item_id = $_POST['order_item_id'];
            $order_id = $_POST['order_id'];
        
            // Decrease quantity in order_items
            $decreaseQuantitySql = "UPDATE order_items SET quantity = quantity - 1 WHERE order_item_id = ? AND quantity > 0";
            $decreaseQuantityStmt = $conn->prepare($decreaseQuantitySql);
            $decreaseQuantityStmt->bind_param("i", $order_item_id);
            $decreaseQuantityStmt->execute();
        
            // Check if the updated quantity is zero and delete the item if it is
            $checkQuantitySql = "SELECT quantity FROM order_items WHERE order_item_id = ?";
            $checkQuantityStmt = $conn->prepare($checkQuantitySql);
            $checkQuantityStmt->bind_param("i", $order_item_id);
            $checkQuantityStmt->execute();
            $quantityResult = $checkQuantityStmt->get_result();
        
            if ($quantityResult->num_rows > 0) {
                $item = $quantityResult->fetch_assoc();
                if ($item['quantity'] == 0) {
                    $deleteItemSql = "DELETE FROM order_items WHERE order_item_id = ?";
                    $deleteItemStmt = $conn->prepare($deleteItemSql);
                    $deleteItemStmt->bind_param("i", $order_item_id);
                    $deleteItemStmt->execute();
                }
            }
        
            // Increase quantity in inventory
            $increaseInventorySql = "UPDATE inventory SET quantity = quantity + 1 WHERE inventory_id = (SELECT inventory_id FROM order_items WHERE order_item_id = ?)";
            $increaseInventoryStmt = $conn->prepare($increaseInventorySql);
            $increaseInventoryStmt->bind_param("i", $order_item_id);
            $increaseInventoryStmt->execute();

            echo "<script>window.location.href = 'PlaceOrder.php';</script>";
        }

        if (isset($_POST['increase_quantity'])) {
            $order_item_id = $_POST['order_item_id'];
            $order_id = $_POST['order_id'];

            // Increase quantity in order_items
            $increaseQuantitySql = "UPDATE order_items SET quantity = quantity + 1 WHERE order_item_id = ?";
            $increaseQuantityStmt = $conn->prepare($increaseQuantitySql);
            $increaseQuantityStmt->bind_param("i", $order_item_id);
            $increaseQuantityStmt->execute();

            // Decrease quantity in inventory
            $decreaseInventorySql = "UPDATE inventory SET quantity = quantity - 1 WHERE inventory_id = (SELECT inventory_id FROM order_items WHERE order_item_id = ?)";
            $decreaseInventoryStmt = $conn->prepare($decreaseInventorySql);
            $decreaseInventoryStmt->bind_param("i", $order_item_id);
            $decreaseInventoryStmt->execute();

            echo "<script>window.location.href = 'PlaceOrder.php';</script>";
        }
        ?>
    </div>
</div>

<?php
include('../includes/footer.php');
?>