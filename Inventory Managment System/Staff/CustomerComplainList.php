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

$counter = 1;
$id = null;
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!--Table-->
    <p class="bg-dark text-white p-2">List of Customer Complaint</p>
    <?php

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
        users.role = 'Customer'
    ORDER BY 
        complaints.complaints_id DESC
";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Full Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Subject</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>';

        $counter = 1; // Initialize counter for ID column
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row">' . $counter++ . '</th>';
            echo '<td>' . $row["user_full_name"] . '</td>';
            echo '<td>' . $row["user_email"] . '</td>';
            echo '<td>' . $row["user_role"] . '</td>';
            echo '<td>' . $row["subject"] . '</td>';
            echo '<td>' . $row["date"] . '</td>';
            echo '<td>
            <form action="CustomerComplainDetail.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value=' . $row["complaints_id"] . '>
                <button type="submit" class="btn bg-tan mr-3" name="view" value="View">
                    <i class="fas fa-eye"></i>
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

    // <form action="" method="POST" class="d-inline">
    //     <input type="hidden" name="id" value=' . $row["complaints_id"] . '>
    //     <button type="submit" class="btn btn-danger" name="delete" value="Delete">
    //         <i class="far fa-trash-alt"></i>
    //     </button>
    // </form>

    if (isset($_REQUEST['delete'])) {
        $id = $_REQUEST['id'];
        $sqla = "DELETE FROM complaints WHERE complaints_id = '$id'";
        if ($conn->query($sqla) === TRUE) {
            // Display success message on form submit success
            $passmsg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
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