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
    exit;
}

define('TITLE', 'User Profile');
define('PAGE', 'profile');
include('../includes/header.php');

if (isset($_POST['update'])) {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        $passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
    } else {
        // Assigning User Values to Variables
        $id = $_POST['id'];
        $rName = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "UPDATE users SET username = ?, password = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $rName, $password, $email, $id);

        if ($stmt->execute()) {
            // Display success message on form submit success
            $passmsg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
        } else {
            // Display error message on form submit failure
            $passmsg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
        }
    }
}

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = null;
}

?>
<div class="col-sm-6 mt-5  mx-3 jumbotron mx-auto"> <br>
    <h3 class="text-center">Profile</h3>
    <br> <br>
    <form action="" method="POST">

        <?php if (isset($passmsg)) { ?>
            <div class="text-center mx-auto">
                <?php echo $passmsg; ?>
            </div>
        <?php
        } ?>

        <!-- <label for="inputID">ID</label> -->
        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo isset($row['user_id']) ? $row['user_id'] : ''; ?>" readonly>

        <div class="form-group col-md-6 mx-auto">
            <label for="inputRole">Role : </label>
            <input type="text" class="form-control text-center mx-auto" id="role" name="role" value="<?php echo isset($row['role']) ? $row['role'] : ''; ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="inputEmail">Email : </label>
            <input type="email" class="form-control text-center mx-auto" id="email" name="email" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" readonly>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="inputFullName">Full Name : </label>
            <input type="text" class="form-control text-center mx-auto" id="username" name="username" value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" required>
        </div>

        <div class="form-group col-md-6 mx-auto">
            <label for="inputPassword">Password : </label>
            <input type="password" class="form-control text-center mx-auto" id="password" name="password" value="<?php echo isset($row['password']) ? $row['password'] : ''; ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-tan" name="update"><i class="fas fa-undo"></i> Update </button>
        </div>
    </form>
</div>
<?php
include('../includes/footer.php');
?>