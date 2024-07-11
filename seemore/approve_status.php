<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
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
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="approve_status.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approve Delete Status</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Approve Delete Status</span>
      </div>
    </nav>
    <div class="home-content">
      <div class="main-content">
        <div class="heading">
          <h1>Approve Deletion Requests</h1>
        </div>
        <div class="table">
          <?php
          $sql = "SELECT * FROM inqury_data WHERE delete_status='processing'";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) > 0) {
            ?>
            <table>
              <thead>
                <tr>
                  <th>Sr.No</th>
                  <th>Student ID</th>
                  <th>Status</th>
                  <th>Reason</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Delete Status</th>
                  <th>Approve</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row["student_id"] ?></td>
                    <td>
                      <?php echo ($row["status"] == "Late") ? "Late Entry" : ($row["status"] == "Early" ? "Early Exit" : $row["status"]); ?>
                    </td>
                    <td><?php echo $row["reason"] ?></td>
                    <td><?php echo $row["date"] ?></td>
                    <td><?php echo $row["currentime"] ?></td>
                    <td><?php echo $row["delete_status"] ?></td>
                    <td>
                      <center><button class="approve-btn" data-id="<?php echo $row['student_id']; ?>"
                          data-currentime="<?php echo $row['currentime']; ?>">Approve</button></center>
                    </td>
                  </tr>
                  <?php
                  $i++;
                }
                ?>
              </tbody>
            </table>
            <?php
          } else {
            echo '<p style="color:darkblue;">No requests to Approve</p>';
          }
          $conn->close();
          ?>
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
      $(".approve-btn").click(function () {
        var student_id = $(this).data("id");
        var currentime = $(this).data("currentime");

        var confirmation = confirm("Are you sure you want to approve this delete request?");
        if (confirmation) {
          $.ajax({
            url: "approveDeleteStatus.php",
            type: "POST",
            data: {
              student_id: student_id,
              currentime: currentime,
              deleteStatus: 'approved'
            },
            success: function (response) {
              if (response.trim() === 'success') {
                alert("Delete status approved.");
                location.reload();
              } else {
                alert("Error approving delete status.");
              }
            },
            error: function (xhr, status, error) {
              console.error(xhr);
            }
          });
        }
      });
    });
  </script>
</body>

</html>
