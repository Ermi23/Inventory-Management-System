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

define('TITLE', 'Add staff');
define('PAGE', 'addstaff');
include('../includes/header.php');


if (isset($_REQUEST['submitrequest'])) {
  // Checking for Empty Fields
  try {
    if (
      ($_REQUEST['username'] == " ") ||
      ($_REQUEST['email'] == " ") ||
      ($_REQUEST['password'] == " ") ||
      ($_REQUEST['role'] == " ")
    ) {
      // Message displayed if required field is missing
      $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
    } else {
      // Assigning User Values to Variables
      $username = $_REQUEST['username'];
      $EID = $_REQUEST['email'];
      $password = $_REQUEST['password'];
      $role = $_REQUEST['role'];

      $sql = "INSERT INTO users(`username`, `password`, `email`, `role`) 
          VALUES ('$username', '$password', '$EID', '$role')";

      if ($conn->query($sql) === TRUE) {
        // Message displayed on form submit success
        $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Registered Successfully. </div>';
      } else {
        $error = $conn->error;
        // Message displayed on form submit failure
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register  Duplicate Entry For Email. </div>';
      }
    }
  } catch (Exception $e) {
    $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register  Duplicate Entry For Email. </div>';
  }
}
?>

<br> <br>
<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
  <h3 class="text-center">Add User Detail</h3>
  <br> <br>
  <form action="" method="POST">

    <?php if (isset($msg)) { ?>
      <div class="text-center mx-auto">
        <div class="d-flex justify-content-center">
          <?php echo $msg; ?>
        </div>
      </div>
    <?php } ?>

    <div class="form-group col-md-6 mx-auto">
      <label for="username"><b>Full Name :</b></label>
      <input required type="text" class="form-control" id="username" name="username">
    </div>

    <div class="form-group col-md-6 mx-auto">
      <label for="email"><b>Email :</b></label>
      <input required type="email" class="form-control" id="email" name="email">
    </div>

    <div class="form-group col-md-6 mx-auto">
      <label for="password"><b>Password :</b></label>
      <input required type="password" class="form-control" id="password" name="password">
    </div>

    <div class="form-group col-md-6 mx-auto">
      <label for="role"><b>Role</b></label>
      <select class="form-control text-center" id="role" name="role" required>
        <option value="" disabled selected> -- Select --</option>
        <option value="Manager">Manager</option>
        <option value="Customer">Customer</option>
        <option value="Staff">Staff</option>
      </select>
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
<?php
include('../includes/footer.php');
$conn->close();
?>