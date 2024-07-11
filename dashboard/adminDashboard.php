<?php
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
$department=$_SESSION['department'];
$role=$_SESSION['role'];

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
  <link rel="stylesheet" href="adminDashboard.css">
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
        <?php
        // Check if the user is an admin
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hod') {
          ?>
          <div class=access-denied>
            <?php
            echo "<div>You do not have permission to access this page.</div>";
            // You may also redirect to a limited access page or the login page.
            ?>
            <div>
              <a style="text-decoration: none;" href="../Search/search.php">Go to Homepage</a>
            </div>
          </div>
          <?php
        } else {
          ?>
          <h1>
            <p>Welcome,
              <?php echo $_SESSION['fullname']; ?>!
            </p>
          </h1>
          <div class="heading">
            <h1><?php if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
              echo $department." ";
            }?>Dashboard</h1>
          </div>

          <div class="dashboard-container">
            <div class="tab">
              <div class="tab-content">
                <h1>
                  <center>Today's Report</center>
                </h1>
                <div class="content">
                  <div class="content-row">
                    <p>Total Late Entry: <span>
                        <?php
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Late' AND dprt='$department';";
                        }else{
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Late';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $entryCount = $row["count"];
                        ?>
                      </span>
                    </p>
                    <p>Total Early Exit: <span>
                        <?php
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Early' AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status='Early';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $exitCount = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                  <div class="content-row">
                    <p>Total Other Checking Count: <span>
                        <?php
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status NOT IN ('Late', 'Early') AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE date=CURRENT_DATE AND status NOT IN ('Late', 'Early');";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $otherCount = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="pie-chart">
                <canvas id="myPieChart" width="300" height="300"></canvas>
                <script>
                  // Get the counts from your PHP code
                  var lateEntryCount = <?php echo $entryCount; ?>;
                  var earlyExitCount = <?php echo $exitCount; ?>;
                  var otherCount = <?php echo $otherCount; ?>;

                  // Get the canvas element
                  var ctx = document.getElementById("myPieChart").getContext("2d");

                  // Create a pie chart
                  var myPieChart = new Chart(ctx, {
                    type: "pie",
                    data: {
                      labels: ["Late Entry", "Early Exit", "Other Reason"],
                      datasets: [{
                        data: [lateEntryCount, earlyExitCount, otherCount],
                        backgroundColor: ['#ef233c',
                          '#ccd5ae', '#0C356A'],
                      }],
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      legend: {
                        position: "bottom",
                      },
                    },
                  });
                </script>
              </div>
            </div>
            <div class="tab">
              <div class="tab-content">
                <h1>
                  <center>Monthly Summary Report</center>
                </h1>
                <div class="content">
                  <div class="content-row">
                    <p>
                      Total Late Entry:
                      <span>
                        <?php
                        // Create connection
                        include '../database.php';
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status='Late' AND dprt='$department'";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status='Late';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $entryCount1 = $row["count"];
                        ?>
                      </span>
                    </p>
                    <p>
                      Total Early Exit: <span>
                        <?php
                        // Create connection
                        include '../database.php';
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status='Early' AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status='Early';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $exitCount1 = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                  <div class="content-row">
                    <p>Total Other Checking Count: <span>
                        <?php
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status NOT IN ('Late', 'Early') AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND status NOT IN ('Late', 'Early');";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $otherCount1 = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="pie-chart">
                <canvas id="myPieChart1" width="300" height="300"></canvas>
                <script>
                  // Get the counts from your PHP code
                  var lateEntryCount1 = <?php echo $entryCount1; ?>;
                  var earlyExitCount1 = <?php echo $exitCount1; ?>;
                  var otherCount1 = <?php echo $otherCount1; ?>;

                  // Get the canvas element
                  var ctx1 = document.getElementById("myPieChart1").getContext("2d");

                  // Create a pie chart
                  var myPieChart = new Chart(ctx1, {
                    type: "doughnut",
                    data: {
                      labels: ["Late Entry", "Early Exit", "Other Reason"],
                      datasets: [{
                        data: [lateEntryCount1, earlyExitCount1, otherCount1],
                        backgroundColor: ['#ef233c',
                          '#ccd5ae', '#0C356A'],
                        // borderColor: ['black'],
                      }],
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      legend: {
                        position: "bottom",
                      },
                    },
                  });
                </script>
              </div>
            </div>
            <div class="tab">
              <div class="tab-content">
                <h1>
                  <center>Yearly Summary Report</center>
                </h1>
                <div class="content">
                  <div class="content-row">
                    <p>
                      Total Late Entry:
                      <span>
                        <?php
                        // Create connection
                        include '../database.php';
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status='Late' AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status='Late';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $entryCount2 = $row["count"];
                        ?>
                      </span>
                    </p>
                    <p>
                      Total Early Exit: <span>
                        <?php
                        // Create connection
                        include '../database.php';
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status='Early' AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status='Early';";
                        }
                        //select only all student number whose status is late
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $exitCount2 = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                  <div class="content-row">
                    <p>Total Other Checking Count: <span>
                        <?php
                        //select only all student number whose status is late
                        if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status NOT IN ('Late', 'Early') AND dprt='$department';";
                        } else {
                          $sql = "SELECT COUNT(*) as count FROM inqury_data WHERE YEAR(date) = YEAR(CURDATE()) AND status NOT IN ('Late', 'Early');";
                        }
                        $result = mysqli_query($conn, $sql);
                        $row = $result->fetch_assoc();
                        echo $row["count"];
                        $otherCount2 = $row["count"];
                        ?>
                      </span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="pie-chart">
                <canvas id="myPieChart2" width="300" height="300"></canvas>
                <script>
                  // Get the counts from your PHP code
                  var lateEntryCount2 = <?php echo $entryCount2; ?>;
                  var earlyExitCount2 = <?php echo $exitCount2; ?>;
                  var otherCount2 = <?php echo $otherCount2; ?>;

                  // Get the canvas element
                  var ctx2 = document.getElementById("myPieChart2").getContext("2d");
                  // Create a bar chart
                  var myBarChart = new Chart(ctx2, {
                    type: "bar",
                    data: {
                      labels: ["Late Entry", "Early Exit", "Other Reason"],
                      datasets: [{
                        data: [lateEntryCount2, earlyExitCount2, otherCount2],
                        backgroundColor: ['#ef233c', '#ccd5ae', '#0C356A'],
                      }],
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      legend: {
                        display: true, // Set to true to display the legend
                        position: "bottom",
                      },
                    },
                  });
                </script>
              </div>
            </div>
          </div>
        <?php } ?>
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