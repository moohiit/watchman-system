<?php
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}
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
  <link rel="stylesheet" href="showStudent.css">
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
        <span class="dashboard">Student Details</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="card-container">
          <?php
          $sql = "SELECT * from student where id='{$id}'";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $studentName = $row['name'];
            $department = $row['department'];
          }?>
          <!-- Card Start here -->
          <a class="card" href="../studentSection/studentProfile.php?id=<?php echo $id?>">
            <p>Student Profile</p>
            <p><?php echo $studentName;?></p>
            <p><?php echo $department;?></p>
          </a>
          <a class="card" href="../studentSection/studentReport.php?id=<?php echo $id ?>">
            <p>Student Report</p>
          </a>
          <a class="card" href="../ReasonForm/proctorForm.php?id=<?php echo $id ?>">
            <p>File Issue</p>
          </a>
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