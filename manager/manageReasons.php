<?php
session_start();
if (!isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a new reason
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reason'])) {
  $reason = $_POST['reason'];
  $insertSql = "INSERT INTO reasons (reason) VALUES ('$reason')";
  if ($conn->query($insertSql) === TRUE) {
    $message = "Reason added successfully.";
  } else {
    $message = "Error adding reason: " . $conn->error;
  }
}

// Handle form submission for editing a reason
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_reason'])) {
  $reasonId = $_POST['reason_id'];
  $reason = $_POST['reason'];
  $updateSql = "UPDATE reasons SET reason='$reason' WHERE reason_id='$reasonId'";
  if ($conn->query($updateSql) === TRUE) {
    $message = "Reason updated successfully.";
  } else {
    $message = "Error updating reason: " . $conn->error;
  }
}

// Handle form submission for deleting a reason
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reason'])) {
  $reasonId = $_POST['reason_id'];
  $deleteSql = "DELETE FROM reasons WHERE reason_id='$reasonId'";
  if ($conn->query($deleteSql) === TRUE) {
    $message = "Reason deleted successfully.";
  } else {
    $message = "Error deleting reason: " . $conn->error;
  }
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
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <!-- Navbar start Here -->
    <?php include "../navbar/navbar.php" ?>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="heading">
          <h1>Manage Reasons</h1>
        </div>
        <?php if (isset($message)): ?>
          <div class="alert alert-info">
            <center><?php echo $message; ?></center>
            <?php unset($message); ?>
          </div>
        <?php endif; ?>
        <div class="form-container">
          <form class="form" method="POST">
            <div class="form-row">
              <label for="reason">Reason:</label>
              <input type="text" class="form-control" name="reason" required>
            </div>
            <div class="form-row">
              <input type="submit" class="btn btn-primary" name="add_reason" value="Add New Reason">
            </div>
          </form>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Reason</th>
                <th colspan="2"><center>Actions</center></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM reasons";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                  <td><?php echo $row["reason_id"]; ?></td>
                  <td><?php echo $row["reason"]; ?></td>
                  <td>
                    <button class="btn btn-warning"
                      onclick="editReason('<?php echo $row['reason_id']; ?>', '<?php echo $row['reason']; ?>')">Edit</button>
                  </td>
                  <td>
                    <button class="btn btn-danger" onclick="deleteReason('<?php echo $row['reason_id']; ?>')">Delete</button>
                  </td>
                </tr>
                <?php
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
  <script>
    function editReason(id, reason) {
      if (confirm("Are you sure you want to edit this reason?")) {
        var newReason = prompt("Edit Reason:", reason);
        if (newReason !== null) {
          var form = document.createElement("form");
          form.method = "POST";
          form.style.display = "none";

          var reasonIdInput = document.createElement("input");
          reasonIdInput.name = "reason_id";
          reasonIdInput.value = id;
          form.appendChild(reasonIdInput);

          var reasonInput = document.createElement("input");
          reasonInput.name = "reason";
          reasonInput.value = newReason;
          form.appendChild(reasonInput);

          var editReasonInput = document.createElement("input");
          editReasonInput.name = "edit_reason";
          editReasonInput.value = "1";
          form.appendChild(editReasonInput);

          document.body.appendChild(form);
          form.submit();
        }
      }
    }

    function deleteReason(id) {
      if (confirm("Are you sure you want to delete this reason?")) {
        var form = document.createElement("form");
        form.method = "POST";
        form.style.display = "none";

        var reasonIdInput = document.createElement("input");
        reasonIdInput.name = "reason_id";
        reasonIdInput.value = id;
        form.appendChild(reasonIdInput);

        var deleteReasonInput = document.createElement("input");
        deleteReasonInput.name = "delete_reason";
        deleteReasonInput.value = "1";
        form.appendChild(deleteReasonInput);

        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
  <script src="../scripts.js"></script>
</body>

</html>