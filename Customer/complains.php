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

define('TITLE', 'Fill Complain Form');
define('PAGE', 'complains');
include('../includes/header2.php');


if (isset($_REQUEST['submitrequest'])) {
    // Checking for Empty Fields
    try {
        if (
            ($_REQUEST['subject'] == " ") ||
            ($_REQUEST['description'] == " ")
        ) {
            // Message displayed if required field is missing
            $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
        } else {
            // Assigning User Values to Variables

            $userID = $user['user_id'];
            $username = $_REQUEST['subject'];
            $description = $_REQUEST['description'];
            $date = date('Y-m-d');

            $sql = "INSERT INTO complaints(`user_id`, `subject`, `description`, `date`) 
          VALUES ('$userID', '$username', '$description', '$date')";

            if ($conn->query($sql) === TRUE) {
                // Message displayed on form submit success
                $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Registered Successfully. </div>';
            } else {
                $error = $conn->error;
                // Message displayed on form submit failure
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register. Error: ' . $error . '</div>';
            }
        }
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert" "mx-auto"> Unable to Register' . $error .  $e . '</div>';
    }
}
?>

<br> <br>
<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
    <h3 class="text-center">Complain Form</h3> <br>
    <form action="" method="POST">
        <?php if (isset($msg)) { ?>
            <div class="text-center mx-auto">
                <div class="d-flex justify-content-center">
                    <?php echo $msg; ?>
                </div>
            </div>
        <?php } ?>
        <br>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="username"><b>Full Name :</b></label>
            <input type="text" class="form-control" id="username" name="username" placeholder=<?php echo $user['user_id']; ?> value=<?php echo $user['username']; ?> readonly>
        </div>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="email"><b>Email :</b></label>
            <input type="email" class="form-control" id="email" name="email" placeholder=<?php echo $rEmail; ?> value=<?php echo $rEmail; ?> readonly>
        </div>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="subject"><b>Subject :</b></label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>

        <div class="form-group col-md-6 text-center mx-auto">
            <label for="description"><b>description :</b></label>
            <textarea class="form-control" id="description" name="description" rows="3" required> </textarea>
        </div>
        <br>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" name="submitrequest"><i class="fas fa-paper-plane"></i> Send</button>
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