<?php
session_start();
if (!isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
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
  <link rel="stylesheet" href="manager.css">
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
    <?php include "../navbar/navbar.php" ?>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="heading">
          <h1>Manage Databases</h1>
        </div>
        <div class="card-container">
          <!-- Card Start here -->
          <?php
          // Check if the user is an admin
          if ($_SESSION['role'] == 'admin') {
            ?>
            <a class="card" href="../manager/manageUsers.php">
              <p>Manage Users</p>
              <p style="line-height: 1rem;" class="user-count">Active Users:
                <?php
                //select only users who are active
                $sql = "SELECT COUNT(*) as count FROM users WHERE status='active'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
              <p style="line-height: 1rem;" class="user-count">Inactive Users:
                <?php
                //select only users who are inactive
                $sql = "SELECT COUNT(*) as count FROM users WHERE status='inactive'";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
            </a>
            <a class="card" href="../manager/manageReasons.php">
              <p>Manage Reasons</p>
              <p style="line-height: 1rem;" class="user-count">Total Reasons:
                <?php
                $sql = "SELECT COUNT(*) as count FROM reasons ;";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
            </a>
            <a class="card" href="../manager/manageDepartments.php">
              <p>Manage Department</p>
              <p style="line-height: 1rem;" class="user-count">Total Department:
                <?php
                $sql = "SELECT COUNT(*) as count FROM department ;";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
            </a>
            <a class="card" href="../manager/manageStudents.php">
              <p>Manage Students</p>
              <p style="line-height: 1rem;" class="user-count">Total Students:
                <?php
                $sql = "SELECT COUNT(*) as count FROM student ;";
                $result = mysqli_query($conn, $sql);
                $row = $result->fetch_assoc();
                echo $row["count"];
                ?>
              </p>
            </a>
            <a class="card" href="../manager/manageCourses.php">
              <p>Manage Courses</p>
              <p style="line-height: 1rem;" class="user-count">Total Students:
                <?php
                $sql = "SELECT COUNT(*) as count FROM courses ;";
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