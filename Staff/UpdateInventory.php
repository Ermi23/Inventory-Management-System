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

define('TITLE', 'update Inventory');
define('PAGE', 'inventorylist');
include('../includes/header1.php');

if (isset($_REQUEST['empupdate'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $inventory_id = $_POST['inventory_id'];

    // Using prepared statements to prevent SQL injection
    $sql = "UPDATE inventory SET item_name = ?, quantity = ?, unit_price = ? WHERE inventory_id = ?";

    // Assuming you have a database connection $conn
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // Handle error here
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters
    // "sdii" means string, double, integer, integer
    $stmt->bind_param("sdii", $item_name, $quantity, $unit_price, $inventory_id);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
        echo "<script>window.location.href = 'InventoryList.php';</script>";
    } else {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
    }

    // Close the statement
    $stmt->close();
}

?>
<br> <br>
<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
    <h3 class="text-center">Update Item</h3>
    <br> <br>
    <?php
    if (isset($_REQUEST['view'])) {
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM inventory WHERE inventory_id = '$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="POST">
        <?php if (isset($msg)) {
            echo $msg;
        } ?>

        <div class="logo">
            <img src="../Product Image/<?php echo $row['picture']; ?>" alt="Logo">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="inventory_id"><b>Inventory ID :</b></label>
            <input type="text" class="form-control" id="inventory_id" name="inventory_id" value="<?php if (isset($row['inventory_id'])) {
                                                                                                        echo $row['inventory_id'];
                                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="item_name"><b>Item Name :</b></label>
            <input type="text" class="form-control" id="item_name" name="item_name" value="<?php if (isset($row['item_name'])) {
                                                                                                echo $row['item_name'];
                                                                                            } ?>">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="quantity"><b>Quantity :</b></label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php if (isset($row['quantity'])) {
                                                                                                echo $row['quantity'];
                                                                                            } ?>">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="unit_price"><b>Unit Price :</b></label>
            <input type="number" class="form-control" id="unit_price" name="unit_price" value="<?php if (isset($row['unit_price'])) {
                                                                                                    echo $row['unit_price'];
                                                                                                } ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" id="empupdate" name="empupdate">Update</button>
            <a href="Inventorylist.php" class="btn btn-secondary">Close</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
?>