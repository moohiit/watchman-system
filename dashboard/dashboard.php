<?php
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';

$username = $_SESSION['username'];
// Prepare the SQL query
$sql = "SELECT department FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("s", $username);

// Execute the query
$stmt->execute();

// Bind the result variables
$stmt->bind_result($department);


// Fetch the result
$stmt->fetch();
//store the department in the session variable
$_SESSION['department'] = $department;

// Close the statement
$stmt->close();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Boxicons CDN Link -->
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="dashboard.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php
  include "../sidebar/sidebar.php"
    ?>
  <section class="home-section">
    <!-- Navbar start Here -->
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Dashboard</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="card-container">
          <!-- Card Start here -->
          <a class="card" href="./adminDashboard.php">
            <p>Full Report<?php if ($_SESSION['role'] == 'hod' || $_SESSION['role']=='classIncharge') {
              echo " of ".$department;
            } ?></p>

            <p class="report-count">
              <?php
              if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                //select only all student number 
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND dprt ='$department';";
              }else {
                //select only all student number
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE;";
              }
              $result = mysqli_query($conn, $sql);
              $row = $result->fetch_assoc();
              echo $row["count"];
              ?>
            </p>
          </a>
          <a class="card" href="../LateEntry/LateEntry.php">
            <p>LateEntry Report</p>
            <p class="report-count">
              <?php
              if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                //select only all student number whose status is late
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Late' AND dprt ='$department';";
              } else {
                //select only all student number whose status is late
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Late';";
              }
              //select only all student number whose status is late
              $result = mysqli_query($conn, $sql);
              $row = $result->fetch_assoc();
              echo $row["count"];
              ?>
            </p>
          </a>
          <a class="card" href="../EarlyExit/EarlyExit.php">
            <p>EarlyExit Report</p>
            <p class="report-count">
              <?php
              if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                //select only all student number whose status is late
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Early' AND dprt ='$department';";
              } else {
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Early';";
              }
              $result = mysqli_query($conn, $sql);
              $row = $result->fetch_assoc();
              echo $row["count"];
              ?>
            </p>
          </a>
          <a class="card" href="../otherReason/otherReason.php">
            <p>Misc. Report</p>
            <p class="report-count">
              <?php
              if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                //select only all student number whose status is late
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status NOT IN ('Late', 'Early') AND dprt ='$department';";
              } else {
                $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status NOT IN ('Late', 'Early');";
              }
              //select only all student number whose status is late
              $result = mysqli_query($conn, $sql);
              $row = $result->fetch_assoc();
              echo $row["count"];
              ?>
            </p>
          </a>
          <?php
          // Check if the user is an admin
          if ($_SESSION['role'] == 'admin') {
            ?>
            <a class="card" href="../manageUsers/manageUsers.php">
              <p>Manage Users</p>
              <p class="user-count">Active Users:
                <?php
                //select only users who are active
                $sql = "SELECT COUNT(*) as count FROM users WHERE status='active'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
              <p class="user-count">Inactive Users:
                <?php
                //select only users who are inactive
                $sql = "SELECT COUNT(*) as count FROM users WHERE status='inactive'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
            </a>
          <?php } ?>

          <!-- Card End here -->
        </div>

      </div>
      <!-- Main Content Ends Here -->
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script src="../scripts.js"></script>
</body>

</html>