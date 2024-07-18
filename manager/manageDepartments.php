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

// Handle form submission for adding a new department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_department'])) {
  $deptEmail = $_POST['deptEmail'];
  $department = $_POST['department'];
  $departmentCode = strtoupper($_POST['department_code']); // Convert to uppercase
  $hodName = $_POST['hodName'];
  $hodMobile = $_POST['hodMobile'];

  // Check if the department email or department name or department code already exists
  $checkSql = "SELECT * FROM department WHERE deptEmail = '$deptEmail' OR department = '$department' OR department_code = '$departmentCode'";
  $checkResult = $conn->query($checkSql);

  if ($checkResult->num_rows > 0) {
    $message = "Error: Department email, name, or code already exists.";
  } else {
    $insertSql = "INSERT INTO department (deptEmail, department, department_code, hod_name, hod_mobile) VALUES ('$deptEmail', '$department', '$departmentCode', '$hodName', '$hodMobile')";
    if ($conn->query($insertSql) === TRUE) {
      $message = "Department added successfully.";
    } else {
      $message = "Error adding department: " . $conn->error;
    }
  }
}

// Handle form submission for editing a department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_department'])) {
  $deptID = $_POST['deptID'];
  $deptEmail = $_POST['deptEmail'];
  $department = $_POST['department'];
  $departmentCode = strtoupper($_POST['department_code']); // Convert to uppercase
  $hodName = $_POST['hodName'];
  $hodMobile = $_POST['hodMobile'];

  // Check if the new department code already exists (excluding the current department)
  $checkSql = "SELECT * FROM department WHERE (deptEmail = '$deptEmail' OR department = '$department' OR department_code = '$departmentCode') AND deptID != '$deptID'";
  $checkResult = $conn->query($checkSql);

  if ($checkResult->num_rows > 0) {
    $message = "Error: Department email, name, or code already exists.";
  } else {
    $updateSql = "UPDATE department SET deptEmail='$deptEmail', department='$department', department_code='$departmentCode', hod_name='$hodName', hod_mobile='$hodMobile' WHERE deptID='$deptID'";
    if ($conn->query($updateSql) === TRUE) {
      $message = "Department updated successfully.";
    } else {
      $message = "Error updating department: " . $conn->error;
    }
  }
}

// Handle form submission for deleting a department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_department'])) {
  $deptID = $_POST['deptID'];
  $deleteSql = "DELETE FROM department WHERE deptID='$deptID'";
  if ($conn->query($deleteSql) === TRUE) {
    $message = "Department deleted successfully.";
  } else {
    $message = "Error deleting department: " . $conn->error;
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
  <link rel="stylesheet" href="manageDepartments.css">
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
          <h1>Manage Departments</h1>
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
              <label for="department">Department:</label>
              <input type="text" class="form-control" name="department" required>
            </div>
            <div class="form-row">
              <label for="department_code">Department Code:</label>
              <input type="text" class="form-control" name="department_code" maxlength="3" required>
            </div>
            <div class="form-row">
              <label for="hodName">HOD Name:</label>
              <input type="text" class="form-control" name="hodName" required>
            </div>
            <div class="form-row">
              <label for="deptEmail">Department Email:</label>
              <input type="email" class="form-control" name="deptEmail" required>
            </div>
            <div class="form-row">
              <label for="hodMobile">HOD Mobile:</label>
              <input type="text" class="form-control" name="hodMobile" required>
            </div>
            <div class="form-row">
              <input type="submit" class="btn btn-primary" name="add_department" value="Add Department">
            </div>
          </form>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Department Code</th>
                <th>HOD Name</th>
                <th>HOD Mobile</th>
                <th>Department Email</th>
                <th colspan="2">
                  <center>Actions</center>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM department";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                  <td><?php echo $row["deptID"]; ?></td>
                  <td><?php echo $row["department"]; ?></td>
                  <td><?php echo $row["department_code"]; ?></td>
                  <td><?php echo $row["hod_name"]; ?></td>
                  <td><?php echo $row["hod_mobile"]; ?></td>
                  <td><?php echo $row["deptEmail"]; ?></td>
                  <td>
                    <button class="btn btn-warning"
                      onclick="editDepartment('<?php echo $row['deptID']; ?>', '<?php echo $row['deptEmail']; ?>', '<?php echo $row['department']; ?>', '<?php echo $row['department_code']; ?>', '<?php echo $row['hod_name']; ?>', '<?php echo $row['hod_mobile']; ?>')">Edit</button>
                  </td>
                  <td>
                    <button class="btn btn-danger"
                      onclick="deleteDepartment('<?php echo $row['deptID']; ?>')">Delete</button>
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
    function editDepartment(id, email, department, departmentCode, hodName, hodMobile) {
      if (confirm("Are you sure you want to edit this department?")) {
        var newEmail = prompt("Edit Department Email:", email);
        var newDepartment = prompt("Edit Department:", department);
        var newDepartmentCode = prompt("Edit Department Code:", departmentCode);
        var newHodName = prompt("Edit HOD Name:", hodName);
        var newHodMobile = prompt("Edit HOD Mobile:", hodMobile);
        if (newEmail !== null && newDepartment !== null && newDepartmentCode !== null && newHodName !== null && newHodMobile !== null) {
          var form = document.createElement("form");
          form.method = "POST";
          form.style.display = "none";

          var deptIDInput = document.createElement("input");
          deptIDInput.name = "deptID";
          deptIDInput.value = id;
          form.appendChild(deptIDInput);

          var deptEmailInput = document.createElement("input");
          deptEmailInput.name = "deptEmail";
          deptEmailInput.value = newEmail;
          form.appendChild(deptEmailInput);

          var departmentInput = document.createElement("input");
          departmentInput.name = "department";
          departmentInput.value = newDepartment;
          form.appendChild(departmentInput);

          var departmentCodeInput = document.createElement("input");
          departmentCodeInput.name = "department_code";
          departmentCodeInput.value = newDepartmentCode;
          form.appendChild(departmentCodeInput);

          var hodNameInput = document.createElement("input");
          hodNameInput.name = "hodName";
          hodNameInput.value = newHodName;
          form.appendChild(hodNameInput);

          var hodMobileInput = document.createElement("input");
          hodMobileInput.name = "hodMobile";
          hodMobileInput.value = newHodMobile;
          form.appendChild(hodMobileInput);

          var editDepartmentInput = document.createElement("input");
          editDepartmentInput.name = "edit_department";
          editDepartmentInput.value = "1";
          form.appendChild(editDepartmentInput);

          document.body.appendChild(form);
          form.submit();
        }
      }
    }

    function deleteDepartment(id) {
      if (confirm("Are you sure you want to delete this department?")) {
        var form = document.createElement("form");
        form.method = "POST";
        form.style.display = "none";

        var deptIDInput = document.createElement("input");
        deptIDInput.name = "deptID";
        deptIDInput.value = id;
        form.appendChild(deptIDInput);

        var deleteDepartmentInput = document.createElement("input");
        deleteDepartmentInput.name = "delete_department";
        deleteDepartmentInput.value = "1";
        form.appendChild(deleteDepartmentInput);

        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
  <script src="../scripts.js"></script>
</body>

</html>