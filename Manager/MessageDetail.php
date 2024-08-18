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

define('TITLE', 'Visitor Message');
define('PAGE', 'messagelist');
include('../includes/header.php');



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

<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> 
    <h3 class="text-center">Visitor Message Detail</h3>
    <br>
    <?php
    if (isset($_REQUEST['view'])) {
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM contact_us WHERE contact_id = '$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="POST">
        <?php if (isset($msg)) {
            echo $msg;
        } ?>
        <div class="form-group col-md-6 mx-auto">
            <label for="date"><b>Submited Date :</b></label>
            <input type="text" class="form-control" id="date" name="date" value="<?php if (isset($row['date'])) {
                                                                                                    echo $row['date'];
                                                                                                } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="name"><b>Visitor Full Name :</b></label>
            <input type="text" class="form-control" id="name" name="name" value="<?php if (isset($row['name'])) {
                                                                                        echo $row['name'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="phone_number"><b>Visitor phone_number :</b></label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php if (isset($row['phone_number'])) {
                                                                                                        echo $row['phone_number'];
                                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="email"><b>Visitor Email :</b></label>
            <input type="email" class="form-control" id="email" name="email" value="<?php if (isset($row['email'])) {
                                                                                        echo $row['email'];
                                                                                    } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="Subject"><b>Visitor Subject :</b></label>
            <input type="Subject" class="form-control" id="Subject" name="Subject" value="<?php if (isset($row['Subject'])) {
                                                                                                echo $row['Subject'];
                                                                                            } ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="Message"><b> Visitor Message :</b></label>
            <textarea class="form-control" id="Message" name="Message" rows="3" readonly><?php if (isset($row['Message'])) {
                                                                                    echo $row['Message'];
                                                                                } ?></textarea>
        </div>

        <div class="text-center">
            <!-- <button type="submit" class="btn bg-tan" id="empupdate" name="empupdate">Update</button> -->
            <a href="MessageList.php" class="btn bg-tan">Close</a>
        </div>
    </form>
</div>

<?php
include('../includes/footer.php');
?>