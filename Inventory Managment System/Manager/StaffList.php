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

define('TITLE', 'Staff List');
define('PAGE', 'stafflist');
include ('../includes/header.php');

$counter =1;
$id = null;
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!--Table-->
    <p class="bg-dark text-white p-2">List of User</p>
    <?php
    $sql = "SELECT * FROM users ORDER BY user_id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row">' . $counter++ . '</th>';
            echo '<td>' . $row["username"] . '</td>';
            echo '<td>' . $row["email"] . '</td>';
            echo '<td>' . $row["role"] . '</td>';
            echo '<td>
                <form action="UserUpdate.php" method="POST" class="d-inline">
                    <input type="hidden" name="id" value=' . $row["user_id"] . '>
                    <button type="submit" class="btn bg-tan mr-3" name="view" value="View">
                        <i class="fas fa-pen"></i>
                    </button>
                </form>  
                <form action="" method="POST" class="d-inline">
                    <input type="hidden" name="id" value=' . $row["user_id"] . '>
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
        $sqla = "DELETE FROM users WHERE user_id = '$id'";
        if ($conn->query($sqla) === TRUE) {
            echo '<meta http-equiv="refresh" content= "0;URL=?deleted" />';
        } else {
            echo '<div class="text-center mx-auto alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Delete </div>';
        }
    }


    ?>
</div>
</div>
</div>

<?php
include('../includes/footer.php');
?>