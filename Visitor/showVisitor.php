<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="showVisitor.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Visitor Report</span>
      </div>
    </nav>
    <div class="home-content">
      <div class="main-content">
        <div class="heading">
          <h1>Visitor Report</h1>
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
            <div class="form-row">
              <input type="submit" class="btn" name="btn">
            </div>
          </form>
          <?php
          include "../database.php";
          $fromDate = null;
          $toDate = null;
          if (isset($_POST["btn"])) {
            $fromDate = $_POST['from'] ?? null;
            $toDate = $_POST['to'] ?? null;
          }
          if (isset($_POST['exit'])) {
            $visitorId = $_POST['visitor_id'];
            $currentTime = date('Y-m-d H:i:s');
            $updateSql = "UPDATE visitor SET status='visited', exit_time='$currentTime' WHERE visitor_id='$visitorId'";
            $conn->query($updateSql);
          }
          ?>
          <table>
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Name</th>
                <th>Contact No.</th>
                <th>Reason</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include '../database.php';
              if ($fromDate && $toDate) {
                $sql = "SELECT * FROM visitor WHERE `date` BETWEEN '$fromDate' AND '$toDate'";
              } else {
                $sql = "SELECT * FROM visitor WHERE `date`=CURRENT_DATE";
              }
              $result = mysqli_query($conn, $sql);
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                  <td><?php echo $i ?></td>
                  <td><?php echo $row["name"] ?></td>
                  <td><?php echo $row["mobile"] ?></td>
                  <td><?php echo $row["reason"] ?></td>
                  <td><?php echo $row["entry_time"] ?></td>
                  <td><?php echo $row["exit_time"] ?></td>
                  <td><?php echo $row["date"] ?></td>
                  <td>
                    <?php if ($row["status"] == "visiting") { ?>
                      <form method="post" onsubmit="return confirmExit();">
                        <input type="hidden" name="visitor_id" value="<?php echo $row["visitor_id"] ?>">
                        <button type="submit" name="exit" class="btn btn-primary">Signout Exit</button>
                      </form>
                    <?php } else {
                      echo "Visited";
                    } ?>
                  </td>
                </tr>
                <?php
                $i++;
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script src="../scripts.js"></script>
  <script>
    function confirmExit() {
      return confirm('Are you sure you want to record the exit for this visitor?');
    }
  </script>
</body>

</html>