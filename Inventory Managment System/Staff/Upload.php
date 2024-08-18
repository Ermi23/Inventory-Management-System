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

define('TITLE', 'Upload Report');
define('PAGE', 'upload');
include('../includes/header1.php');

$msg = ''; // Initialize $msg variable

if (isset($_POST['upload'])) {
    // Checking for Empty Fields
    if (empty($_FILES['file']['name'])) {
      // msg displayed if required field is missing
      $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
    } else {
      // Assigning User Values to Variables
      $email = $_POST['email'];
      $rpos = $_POST['report_type'];
      $rdesc = $_POST['description'];
      $rdate = $_POST['date'];
  
      $pdf = $_FILES['file']['name'];
      $pdf_type = $_FILES['file']['type'];
      $pdf_size = $_FILES['file']['size'];
      $pdf_loc = $_FILES['file']['tmp_name'];
      $pdf_store = "../Reports/" . $pdf;
  
      // Create the destination directory if it doesn't exist
      if (!is_dir("../Reports")) {
        mkdir("../Reports");
      }
  
      if (move_uploaded_file($pdf_loc, $pdf_store)) {
        $sql = "INSERT INTO report_tb (`pdf`, `email`, `Report Type`, `desc`, `datee`) VALUES ('$pdf', '$email', '$rpos', '$rdesc', '$rdate')";
  
        if ($conn->query($sql) === TRUE) {
          // Success message to display on form submit
          $genid = mysqli_insert_id($conn);
          $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Report Uploaded Successfully </div>';
        } else {
          // Error message to display on form submit
          $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to upload your report </div>';
        }
      } else {
        // Error message to display on form submit
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to move the uploaded file </div>';
      }
    }
  }
?>

<br><br>
<div class="col-sm-6 mt-5 mx-3 jumbotron mx-auto"><br>
    <h3 class="text-center">Upload Report</h3>
    <br><br>
    <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype="multipart/form-data" for file upload -->

        <?php if ($msg !== '') { ?> <!-- Check if $msg is not empty -->
            <div class="text-center mx-auto">
                <div class="d-flex justify-content-center">
                    <?= $msg; ?> <!-- Short PHP echo syntax -->
                </div>
            </div>
        <?php } ?>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="email"><b>Email :</b></label>
            <input type="email" class="form-control" id="email" name="email" placeholder=<?php echo $rEmail; ?> value=<?php echo $rEmail; ?> readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="report_type"><b>Report Type :</b></label>
            <select class="form-control text-center" id="report_type" name="report_type" required>
                <option value="" disabled selected> -- Select --</option>
                <option value="Total Sales By Product">Total Sales By Product</option>
                <option value="Total Revenue By Month">Total Revenue By Month</option>
                <option value="Inventory Status Report">Inventory Status Report</option>
                <option value="Customer Order Details">Customer Order Details</option>
                <option value="Best selling Product">Best selling Product</option>
                <option value="Order Fulfillment Rate">Order Fulfillment Rate</option>
            </select>
        </div>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="description"><b>description :</b></label>
            <textarea class="form-control" id="description" name="description" rows="3" required> </textarea>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="file"><b>File :</b></label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                <label class="custom-file-label" for="file">Choose a file...</label>
            </div>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="input">Date</label>
            <input type="date" class="form-control" id="input" name="date" required>
        </div>

        <br>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" name="upload"><i class="fas fa-save"></i> Upload</button>
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