<?php
session_start();
if (!isset($_SESSION['role'])) {
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
  <link rel="stylesheet" href="studentReport.css">
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
        <span class="dashboard">Watchman System</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <?php
        if (isset($_SESSION['username']) && $_SESSION['role'] === 'student') {
          $email = $_SESSION['username'];
          // Prepare the SQL query
          $sql = "SELECT id FROM student WHERE email = ?";
          // Prepare and bind parameters
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $email);
          // Execute the query
          $stmt->execute();
          // Bind the result variables
          $stmt->bind_result($id);
          // Fetch the result
          $stmt->fetch();
          // Close the statement
          $stmt->close();
          $_SESSION['studentId'] = $id;
        } else if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $_SESSION['studentId'] = $id;
        } else if (isset($_SESSION['studentId'])) {
          $id = $_SESSION['studentId'];
        }
        ?>
        <div class="heading">
          <h1>
            Student Report
          </h1>
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
          // Initialize $fromDate and $toDate
          $fromDate = null;
          $toDate = null;
          if (isset($_POST["btn"])) {
            $fromDate = $_POST['from'] ?? null;
            $toDate = $_POST['to'] ?? null;
          }
          ?>
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
      <!-- Main Content Ends Here -->
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <!-- Include jQuery before your custom JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
  $(document).ready(function () {
    // Get the user role from the session
    var userRole = "<?php echo $_SESSION['role']; ?>";

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
              if (response.trim() === 'success') {
                alert("Record updated successfully.");
                location.reload();
              } else {
                alert("Error updating record.");
              }
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
              alert("Error updating record.");
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
                if (response.trim() === 'success') {
                  alert("Record deleted successfully.");
                  location.reload();
                } else {
                  alert("Error deleting record.");
                }
              },
              error: function (xhr, status, error) {
                console.error(xhr);
                alert("Error deleting record.");
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
                if (response.trim() === 'success') {
                  alert("Delete status updated to 'processing'.");
                  location.reload();
                } else {
                  alert("Error updating delete status.");
                }
              },
              error: function (xhr, status, error) {
                console.error(xhr);
                alert("Error updating delete status.");
              }
            });
          }
        }
      });
    });
  </script>

  <script src="../scripts.js"></script>


</body>

</html>