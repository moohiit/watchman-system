<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$role = $_SESSION['role'];
$department = $_SESSION['department'];
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
  <link rel="stylesheet" href="LateEntry.css">
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
        <span class="dashboard">Late Entry Report</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="heading">
          <h1>Late Entry Report</h1>
        </div>
        <div class="table">
          <form class="form" method="post">
            <div class="form-row">
              <label for="from">From:</label>
              <input id="from" type="date" name="from">
            </div>
            <div class="form-row">
              <label for="to">To:</label>
              <input id="to" type="date" name="to">
            </div>
            <?php if ($role == 'admin') { ?>
              <div class="form-row">
                <label for="dprt">Department:</label>
                <select name="dprt">
                  <option value="select">Select Department</option>
                  <?php
                  $sql = "SELECT * from department";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <option value="<?php echo $row["department"] ?>">
                      <?php echo $row["department"] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            <?php } ?>
            <div class="form-row">
              <input type="submit" class="btn" name="btn">
            </div>
          </form>
          <?php
          // Initialize $fromDate and $toDate
          $fromDate = null;
          $toDate = null;
          $selectedDepartment = null;
          if (isset($_POST["btn"])) {
            $fromDate = $_POST['from'] ?? null;
            $toDate = $_POST['to'] ?? null;
            $selectedDepartment = $_POST['dprt'] ?? null;
          }
          ?>
          <table>
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Name</th>
                <th>Department</th>
                <th>Contact No.</th>
                <th>Reason</th>
                <th>Authorised BY</th>
                <th>Time</th>
                <th>Date</th>
                <th>Photo</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Use prepared statements to prevent SQL injection
              if ($fromDate && $toDate) {
                if ($role == 'admin' && $selectedDepartment != 'select') {
                  $sql = "SELECT * FROM inqury_data WHERE `date` BETWEEN '$fromDate' AND '$toDate' AND status='Late' AND dprt = '$selectedDepartment'";
                } else if ($role == 'admin') {
                  $sql = "SELECT * FROM inqury_data WHERE `date` BETWEEN '$fromDate' AND '$toDate' AND status='Late'";
                } else {
                  $sql = "SELECT * FROM inqury_data WHERE `date` BETWEEN '$fromDate' AND '$toDate' AND status='Late' AND dprt = '$department'";
                }
              } else {
                if ($role == 'admin') {
                  $sql = "SELECT * FROM inqury_data WHERE `date` = CURRENT_DATE AND status='Late'";
                } else {
                  $sql = "SELECT * FROM inqury_data WHERE `date` = CURRENT_DATE AND status='Late' AND dprt = '$department'";
                }
              }
              $result = mysqli_query($conn, $sql);
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                  <td>
                    <?php echo $i ?>
                  </td>
                  <td>
                    <a href="../seemore/studentDetails.php?id=<?php echo $row['student_id'] ?>&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>"
                      style="color:blue;">
                      <?php echo $row['name'] ?>
                    </a>
                  </td>
                  <td>
                    <?php echo $row["dprt"] ?>
                  </td>
                  <td>
                    <?php echo $row["contact"] ?>
                  </td>
                  <td>
                    <?php echo $row["reason"] ?>
                  </td>
                  <td>
                    <?php echo $row["authorisedBy"] ?>
                  </td>
                  <td>
                    <?php echo $row["currentime"] ?>
                  </td>
                  <td>
                    <?php echo $row["date"] ?>
                  </td>
                  <td class="image-column">
                    <img class="table-image" src="<?php echo $row["photo_url"] ?>" alt="Photo">
                  </td>
                </tr>
                <?php
                $i++;
              } ?>
            </tbody>
          </table>
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