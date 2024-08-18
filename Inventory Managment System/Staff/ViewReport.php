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
    $email = $user['email'];
} else {
    echo '<script>alert("No such user exists")</script>';
}

define('TITLE', 'View Report');
define('PAGE', 'report');
include('../includes/header1.php');

?>


<div class="col-sm-9 col-md-10 mt-5">
  <?php 
    $sql = "SELECT * FROM report_tb WHERE email = '$rEmail'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      echo '<table class="table">
      <thead>
        <tr>
          <th scope="col">File ID</th>
          <th scope="col">Report Generator Email</th>
          <th scope="col">Report Type</th>
          <th scope="col">Description</th>
          <th scope="col">Date</th>
          <th scope="col">File</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>';
      $counter = 1;
        while($row = $result->fetch_assoc()){
        ?><tr>
            <td scope="row"><?php echo $counter++?></td>
            <td scope="row"><?php echo $row["email"]?></td>
            <td scope="row"><?php echo $row["Report Type"]?></td>
            <td scope="row"><?php echo $row["desc"]?></td>
            <td scope="row"><?php echo $row["datee"]?></td>
            <td scope="row"><?php echo $row['pdf']?></td>
            <td>
            <form action="" method="POST" class="d-inline">
                <input type="hidden" name="id" value='. $row["report_id"] .'>
                <a href="../Reports/<?php echo $row["pdf"]?>">
                    <button type="button" class="btn bg-tan" name="view" value="view">View</button>
                </a>
            </form>
            </td>
          </tr><?php
   }
          echo '</tbody> </table>';
          } else {
            echo "0 Result";
          }
  ?>
      </div>
    </div>
  </div>

<?php
include('../includes/footer.php'); 
?>

