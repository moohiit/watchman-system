<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
$role = $_SESSION['role'];
include '../database.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="seemore.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php
  include "../sidebar/sidebar.php"
    ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Watchman System</span>
      </div>
    </nav>
    <div class="home-content">
      <div class="main-content">
        <?php
        if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $fromDate = $_GET["fromDate"] ?? null;
          $toDate = $_GET["toDate"] ?? null;
        }
        ?>
        <div class="heading">
          <h1>Student Report</h1>
        </div>
        <div class="form-container">
          <?php
          $sql = "SELECT * from student where id='{$id}'";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['id'];
            $photo = $row['photo_url'];
            ?>
            <form class="form">
              <h2 class="form-heading">Student Details</h2>
              <div>
                <img class="table-image" src="<?php echo $photo; ?>" alt="Photo">
              </div>
              <div>
                <div class="form-row">
                  <label for="name">Name:</label>
                  <p><?php echo strtoupper($row["name"]); ?></p>
                </div>
                <div class="form-row">
                  <label for="name">College ID:</label>
                  <p><?php echo strtoupper($row["college_id"]); ?></p>
                </div>
                <div class="form-row">
                  <label for="dprt">Department:</label>
                  <p><?php echo strtoupper($row['department']); ?></p>
                </div>
                <div class="form-row">
                  <label for="year">Year:</label>
                  <p><?php echo strtoupper($row['year']); ?></p>
                </div>
                <div class="form-row">
                  <label for="num">Number:</label>
                  <p><?php echo $row['conumber']; ?></p>
                </div>
              </div>
            </form>
          <?php } ?>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Time</th>
                <th>Delete Status</th>
                <?php
                if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hod') {
                  ?>
                  <th>Edit</th>
                  <th>Delete</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($fromDate && $toDate) {
                $sql1 = "SELECT * FROM inqury_data WHERE `date` BETWEEN ? AND ? AND student_id=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("sss", $fromDate, $toDate, $id);
              } else if ($fromDate == null && $toDate == null) {
                $sql1 = "SELECT * FROM inqury_data WHERE student_id=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("s", $id);
              }

              $stmt1->execute();
              $result = $stmt1->get_result();
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                  <td><?php echo $i ?></td>
                  <td>
                    <?php echo ($row["status"] == "Late") ? "Late Entry" : ($row["status"] == "Early" ? "Early Exit" : $row["status"]); ?>
                  </td>
                  <td><?php echo $row["reason"] ?></td>
                  <td><?php echo $row["date"] ?></td>
                  <td><?php echo $row["currentime"] ?></td>
                  <td><?php echo $row["delete_status"] ?></td>
                  <?php
                  if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hod') {
                    ?>
                    <td>
                      <center><button class="edit-btn" data-id="<?php echo $row['student_id']; ?>"
                          data-currentime=" <?php echo $row['currentime']; ?>">Edit</button></center>
                    </td>
                    <td>
                      <center><button class="delete-btn" data-id="<?php echo $row['student_id']; ?>"
                          data-currentime="<?php echo $row['currentime']; ?>"
                          data-delete-status="<?php echo $row['delete_status']; ?>">Delete</button></center>
                    </td>
                  <?php } ?>
                </tr>
                <?php
                $i++;
              }
              $stmt1->close();
              $conn->close();
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      var userRole = "<?php echo $role; ?>";
      $(".edit-btn").click(function () {
        var student_id = $(this).data("id");
        var currentime = $(this).data("currentime");
        var reason = prompt("Enter new reason:");
        if (reason != null && reason.trim() != "") {
          $.ajax({
            url: 'editRecord.php',
            type: 'POST',
            data: { student_id: student_id, currentime: currentime, reason: reason },
            success: function (response) {
              // Refresh the page after successful edit
              window.location.reload(true);
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        }
      });


      $(".delete-btn").click(function () {
        var student_id = $(this).data("id");
        var currentime = $(this).data("currentime");
        var deleteStatus = $(this).data("delete-status");

        if (deleteStatus === "approved" || userRole === "admin") {
          var confirmation = confirm("Are you sure you want to delete this record?");
          if (confirmation) {
            $.ajax({
              url: "deleteRecord.php",
              type: "POST",
              data: {
                student_id: student_id,
                currentime: currentime
              },
              success: function (response) {
                location.reload();
              },
              error: function (xhr, status, error) {
                console.error(xhr);
              }
            });
          }
        } else {
          var alertConfirmation = confirm("You don't have access yet. Do you want to update the delete status to 'processing'?");
          if (alertConfirmation) {
            $.ajax({
              url: "updateDeleteStatus.php",
              type: "POST",
              data: {
                student_id: student_id,
                currentime: currentime,
                deleteStatus: 'processing'
              },
              success: function (response) {
                location.reload();
              },
              error: function (xhr, status, error) {
                console.error(xhr);
              }
            });
          }
        }
      });
    });
  </script>
</body>

</html>