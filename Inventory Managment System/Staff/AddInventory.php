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

define('TITLE', 'Add Inventory');
define('PAGE', 'addinventory');
include('../includes/header1.php');

$msg = ''; // Initialize $msg variable

if (isset($_POST['submitrequest'])) { // Change from $_REQUEST to $_POST
    try {
        // Checking for Empty Fields
        if (
            empty($_POST['item_name']) ||
            empty($_POST['quantity']) ||
            empty($_POST['unitprice']) ||
            empty($_FILES['file']['name'])
        ) {
            // Message displayed if required field is missing
            $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
        } else {
            // Assigning User Values to Variables
            $item_name = $_POST['item_name'];
            $quantity = $_POST['quantity'];
            $unit_price = $_POST['unitprice']; // Change from unit_price to unitprice

            // File upload handling
            $picture = $_FILES['file']['name'];
            $picture_type = $_FILES['file']['type'];
            $picture_size = $_FILES['file']['size'];
            $picture_loc = $_FILES['file']['tmp_name'];
            $picture_store = "../Product Image/" . $picture;

            // Create the destination directory if it doesn't exist
            if (!is_dir("../Product Image")) {
                mkdir("../Product Image");
            }

            if (move_uploaded_file($picture_loc, $picture_store)) {
                $sql = "INSERT INTO inventory (`item_name`, `quantity`, `unit_price`, `picture`) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $item_name, $quantity, $unit_price, $picture);

                if ($stmt->execute()) {
                    // Success message to display on form submit
                    $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Inventory Uploaded Successfully </div>';
                } else {
                    // Error message to display on form submit
                    $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to upload The Product because of duplicate Entry for Item Name. </div>';
                }
            } else {
                // Error message to display on form submit
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to upload The Product because of duplicate Entry for Item Name. </div>';
            }
        }
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to upload The Product because of duplicate Entry for Item Name. </div>';
    }
}
?>

<br><br>
<div class="col-sm-6 mt-5 mx-3 jumbotron mx-auto"><br>
    <h3 class="text-center">Add Inventory</h3>
    <br><br>
    <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype="multipart/form-data" for file upload -->

        <?php if ($msg !== '') { ?> <!-- Check if $msg is not empty -->
            <div class="text-center mx-auto">
                <div class="d-flex justify-content-center">
                    <?= $msg; ?> <!-- Short PHP echo syntax -->
                </div>
            </div>
        <?php } ?>

        <div class="form-group col-md-6 mx-auto">
            <label for="item_name"><b>Item Name :</b></label>
            <input required type="text" class="form-control" id="item_name" name="item_name">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="quantity"><b>Quantity :</b></label>
            <input required type="number" class="form-control" id="quantity" name="quantity">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="unitprice"><b>Unit Price :</b></label>
            <input required type="number" class="form-control" id="unitprice" name="unitprice"> <!-- Change from unit_price to unitprice -->
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="file"><b>Picture :</b></label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="file" accept="image/*">
                <label class="custom-file-label" for="file">Choose an image file...</label>
            </div>
        </div>

        <br>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" name="submitrequest"><i class="fas fa-save"></i> SAVE</button>
            <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </form>
</div>
</div>
</div>
<?php include('../includes/footer.php'); ?>

<script>
    document.getElementById('file').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>