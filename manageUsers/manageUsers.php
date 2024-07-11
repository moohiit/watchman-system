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
  <link rel="stylesheet" href="manageUsers.css">
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
        <span class="dashboard">User Management</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="heading">
          <h1>Manage Users</h1>
        </div>
        <div class="table">
          <form class="form" method="post">
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
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="form-row">
              <input type="submit" class="btn" name="btn">
            </div>
          </form>
          <table>
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Department</th>
                <th>Status</th>
                <th>Change Status</th>
                <th>Action</th>
              </tr>

            </thead>
            <tbody>
              <?php
              //Initialize Department
              $department = null;
              if (isset($_POST["btn"])) {
                $department = $_POST['dprt'] ?? null;
              }
              // Use prepared statements to prevent SQL injection
              if ($department) {
                $sql = "SELECT fullname, username, mobile, role, department, status FROM users WHERE department = '$department' AND role != 'admin' AND role !='student'";
              } else {
                $sql = "SELECT fullname, username, mobile, role, department, status FROM users WHERE role != 'admin' AND role !='student'";
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
                    <?php echo $row["fullname"]; ?>
                  </td>
                  <td>
                    <?php echo $row["username"] ?>
                  </td>
                  <td>
                    <?php echo $row["mobile"] ?>
                  </td>
                  <td>
                    <?php echo $row["role"] ?>
                  </td>
                  <td>
                    <?php echo $row["department"] ?>
                  </td>
                  <td>
                    <?php echo $row["status"] ?>
                  </td>
                  <td>
                    <select onchange="updateStatus(this.value, '<?php echo $row['username']; ?>')">
                      <option value="active" <?php if ($row['status'] == 'active')
                        echo 'selected'; ?>>Active</option>
                      <option value="inactive" <?php if ($row['status'] == 'inactive')
                        echo 'selected'; ?>>Inactive</option>
                    </select>
                  </td>
                  <td>
                    <center><button id="table-button"
                        onclick="confirmDelete('<?php echo $row['username']; ?>')">Delete</button></center>
                  </td>
                </tr>
                <?php
                $i++;
              } ?>
            </tbody>
            </>
        </div>
      </div>
      <!-- Main Content Ends Here -->
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script>
    function updateStatus(status, username) {
      // Send an AJAX request to update the status
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          // Reload the page after updating the status
          window.location.reload();
        }
      };
      xhttp.open("POST", "updateStatus.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("status=" + status + "&username=" + username);
    }
    function confirmDelete(username) {
      if (confirm("Are you sure you want to delete this user?")) {
        // Send an AJAX request to delete the user
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            // Reload the page after deleting the user
            window.location.reload();
          }
        };
        xhttp.open("POST", "deleteUser.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("username=" + username);
      }
    }

  </script>
  <script src="../scripts.js"></script>
</body>
</html>