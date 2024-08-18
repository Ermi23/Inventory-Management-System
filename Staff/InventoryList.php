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

define('TITLE', 'Inventory List');
define('PAGE', 'inventorylist');
include('../includes/header1.php');

$counter = 1;
$id = null;
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!--Table-->
    <p class="bg-dark text-white p-2">List of Product</p>
    <?php

    // SQL query to join complaints and users tables
    $sql = "SELECT * FROM inventory";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Item Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Unit Price</th>
                <th scope="col">Picture</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1; // Initialize counter for ID column
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row" class="align-middle">' . $counter++ . '</th>';
            echo '<td class="align-middle"><b>' . $row["item_name"] . '</b></td>';
            echo '<td class="align-middle"><b>' . $row["quantity"] . '</b></td>';
            echo '<td class="align-middle"><b>' . $row["unit_price"] . '</b></td>';
            echo '<td>'
                .   '<div class="logo">
                        <img src="../Product Image/' . $row["picture"] . ' " alt="Company Logo">
                    </div>' .
                '</td>';
            echo '<td class="align-middle">
                    <form action="UpdateInventory.php" method="POST" class="d-inline">
                        <input type="hidden" name="id" value=' . $row["inventory_id"] . '>
                        <button type="submit" class="btn bg-tan mr-3" name="view" value="View">
                            <i class="fas fa-eye"></i>
                        </button>
                    </form>  

                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="id" value=' . $row["inventory_id"] . '>
                        <button type="submit" class="btn btn-danger" name="delete" value="Delete">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>

                </td>
            </tr>';
        }
        echo '</tbody>
    </table>';
    } else {
        echo "0 Result";
    }

    if (isset($_REQUEST['delete'])) {
        $id = $_REQUEST['id'];
        $sqla = "DELETE FROM inventory WHERE inventory_id = '$id'";
        if ($conn->query($sqla) === TRUE) {
            // Display success message on form submit success
            $passmsg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Deleted Successfully </div>';
            echo "<script>window.location.href = 'InventoryList.php';</script>";
        } else {
            // Display error message on form submit failure
            $passmsg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
        }

        if (isset($passmsg)) { ?>
            <div class="d-flex justify-content-center">
                <?php echo $passmsg; ?>
            </div>
    <?php
        }
    }
    ?>

</div>
</div>
</div>

<?php
include('../includes/footer.php');
?>