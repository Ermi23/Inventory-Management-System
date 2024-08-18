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

define('TITLE', 'Complain List');
define('PAGE', 'complainlist');
include('../includes/header1.php');

if (isset($_REQUEST['empupdate'])) {
    $user = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET name = '$name', email='$email', phone_number = '$phone_number' WHERE user_id = '$user'";

    if ($conn->query($sql) === TRUE) {
        $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
        echo "<script>window.location.href = 'StaffList.php';</script>";
    } else {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
    }
}

?>

<div class="col-sm-6 mt-5 mx-3 jumbotron mx-auto">
    <h3 class="text-center">Customer Complain Detail</h3>
    <br>
    <?php
    if (isset($_REQUEST['view'])) {
        $id = $_REQUEST['id'];
        // SQL query to join complaints and users tables
        $sql = "
        SELECT 
            complaints.*, 
            users.username AS user_full_name, 
            users.email AS user_email,
            users.role AS user_role
        FROM 
            complaints 
        JOIN 
            users 
        ON 
            complaints.user_id = users.user_id
        WHERE
            complaints.complaints_id = '$id'
        ";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="POST">
        <?php if (isset($msg)) {
            echo $msg;
        } ?>
        <div class="form-group col-md-6 mx-auto text-center">
            <label for="date"><b>Submitted Date :</b></label>
            <input type="text" class="form-control" id="date" name="date" value="<?php if (isset($row['date'])) {
                                                                                        echo $row['date'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto text-center">
            <label for="name"><b>Full Name :</b></label>
            <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($row['user_full_name'])) {
                                                                                        echo $row['user_full_name'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto text-center">
            <label for="name"><b>Complainer Role :</b></label>
            <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($row['user_role'])) {
                                                                                        echo $row['user_role'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto text-center">
            <label for="email"><b>Email :</b></label>
            <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($row['user_email'])) {
                                                                                        echo $row['user_email'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto text-center">
            <label for="Subject"><b>Subject :</b></label>
            <input type="text" class="form-control" id="Subject" name="Subject" value="<?php if (isset($row['subject'])) {
                                                                                                echo $row['subject'];
                                                                                            } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto text-center">
            <label for="Message"><b>Message :</b></label>
            <textarea class="form-control" id="Message" name="Message" rows="3" readonly><?php if (isset($row['description'])) {
                                                                                                echo $row['description'];
                                                                                            } ?></textarea>
        </div>

        <div class="text-center">
            <a href="CustomerComplainList.php" class="btn bg-tan">Close</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
?>
