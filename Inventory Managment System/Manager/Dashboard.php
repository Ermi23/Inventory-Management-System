<?php
session_start();

if (!isset($_SESSION['is_login'])) {
  header("Location: index.php");
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

define('TITLE', 'Dashboard');
define('PAGE', 'dashboard');
include('../includes/header.php');

?>

<div class="col-sm-9 col-md-10">
  <div class="row mx-5 text-center">
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-tan mb-3" style="max-width: 18rem;">
        <div class="card-header">
          <h1><span class="fas fa-dollar-sign fs-3 text-white"></span></h1> Total Number of customer
        </div>
        <div class="card-body">
          <h4 class="card-title">
            <?php
            $sql = "SELECT COUNT(*) as Customer_count FROM users WHERE role = 'Customer'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $CustomerCount = $row['Customer_count'];
              echo  $CustomerCount;
            }
            ?>
          </h4>
          <a class="btn text-white" href="StaffList.php">View</a>
        </div>
      </div>
    </div>
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
        <div class="card-header">
          <h1> <span class="fa fa-user-tie fs-3 text-white"></span></h1> Total Number of Staff
        </div>
        <div class="card-body">
          <h4 class="card-title">
            <?php
            $sql = "SELECT COUNT(*) as staff_count FROM users WHERE role = 'Staff'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $staffCount = $row['staff_count'];
              echo $staffCount;
            }
            ?>
          </h4>
          <a class="btn text-white" href="StaffList.php">View</a>
        </div>
      </div>
    </div>
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
        <div class="card-header"> <span class="fas fa-exclamation-triangle fa-4x text-danger"></span><br> Number of Complains</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php
            $sql = "SELECT COUNT(*) as staff_count FROM complaints";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $staffCount = $row['staff_count'];
              echo $staffCount;
            }
            ?>
          </h4>
          <a class="btn text-white" href="ComplainList.php">View</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="mx-5 mt-5 text-center">
  <!--Table-
    <p class=" bg-dark text-white p-2">List of Employee</p> -->
  <?php
  //     $sql = "SELECT * FROM staff";
  //     $result = $conn->query($sql);
  //     if($result->num_rows > 0){
  //  echo '<table class="table">
  //   <thead>
  //    <tr>
  //     <th scope="col">Employee ID</th>
  //     <th scope="col">Name</th>
  //     <th scope="col">Department</th>
  //    </tr>
  //   </thead>
  //   <tbody>';
  //   while($row = $result->fetch_assoc()){
  //    echo '<tr>';
  //     echo '<th scope="row">'.$row["EmployeeID"].'</th>';
  //     echo '<td>'. $row["NameOfStaff"].'</td>';
  //     echo '<td>'.$row["Department"].'</td>';
  //   }
  //  echo '</tbody>
  //  </table>';
  // } else {
  //   echo "0 Result";
  // }
  ?>
</div>
</div>
</div>
</div>

<?php
include('../includes/footer.php');
?>