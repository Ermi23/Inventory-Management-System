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

define('TITLE', 'update staff');
define('PAGE', 'stafflist');
include('../includes/header.php');



if (isset($_REQUEST['empupdate'])) {
    $user = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = '$username', email='$email', role = '$role' WHERE user_id = '$user'";

    if ($conn->query($sql) === TRUE) {
        $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
        echo "<script>window.location.href = 'StaffList.php';</script>";
    } else {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
    }
}

?>
<br> <br>
<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
    <h3 class="text-center">Update User Detail</h3>
    <br> <br>
    <?php
    if (isset($_REQUEST['view'])) {
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM users WHERE user_id = '$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="POST">
        <?php if (isset($msg)) {
            echo $msg;
        } ?>
        <div class="form-group col-md-6 mx-auto">
            <label for="user_id"><b>User ID :</b></label>
            <input type="text" class="form-control" id="user_id" name="user_id" value="<?php if (isset($row['user_id'])) {
                                                                                            echo $row['user_id'];
                                                                                        } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="username"><b>Full Name :</b></label>
            <input type="text" class="form-control" id="username" name="username" value="<?php if (isset($row['username'])) {
                                                                                                echo $row['username'];
                                                                                            } ?>">
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="role"><b>Role</b></label>
            <select class="form-control text-center" id="role" name="role">
                <?php $selected_role = $row['role']; ?>
                <option value="Manager" <?php echo ($selected_role == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                <option value="Customer" <?php echo ($selected_role == 'Customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="Staff" <?php echo ($selected_role == 'Staff') ? 'selected' : ''; ?>>Staff</option>
            </select>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="email"><b>Email :</b></label>
            <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($row['email'])) {
                                                                                        echo $row['email'];
                                                                                    } ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" id="empupdate" name="empupdate">Update</button>
            <a href="stafflist.php" class="btn btn-secondary">Close</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
?>