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

// Fetch departments for the dropdown
$departments = [];
$departmentQuery = "SELECT * FROM department";
$departmentResult = $conn->query($departmentQuery);
while ($row = $departmentResult->fetch_assoc()) {
  $departments[] = $row;
}

// Fetch distinct batch years for the dropdown
$batchYears = [];
$batchYearQuery = "SELECT DISTINCT batch_year FROM student";
$batchYearResult = $conn->query($batchYearQuery);
while ($row = $batchYearResult->fetch_assoc()) {
  $batchYears[] = $row['batch_year'];
}

// Handle batch upgrade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upgrade_batch'])) {
  $department = $_POST['department'];
  $year = $_POST['year'];
  $batch_year = $_POST['batch_year'] ?? null;

  $courseQuery = "SELECT duration FROM courses WHERE department='$department'";
  $courseResult = $conn->query($courseQuery);
  if ($batch_year != null) {
    if ($courseResult->num_rows > 0) {
      $course = $courseResult->fetch_assoc();
      $courseDuration = $course['duration'];

      $yearToNumber = [
        'First Year' => 1,
        'Second Year' => 2,
        'Third Year' => 3,
        'Fourth Year' => 4
      ];

      if ($yearToNumber[$year] >= $courseDuration) {
        $message = "Error: Cannot upgrade students beyond the course duration.";
      } else {
        $nextYear = array_search($yearToNumber[$year] + 1, $yearToNumber);
        $upgradeQuery = "UPDATE student SET year='$nextYear' WHERE department='$department' AND year='$year' AND batch_year='$batch_year'";
        if ($conn->query($upgradeQuery) === TRUE) {
          $message = "Batch upgraded successfully.";
        } else {
          $message = "Error upgrading batch: " . $conn->error;
        }
      }
    } else {
      $message = "Error: Department not found.";
    }
  } else {
    $message = "Batch Year is required for this Upgrade.";
  }
}

// Handle batch delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_batch'])) {
  $department = $_POST['department'];
  $year = $_POST['year'];
  $batch_year = $_POST['batch_year'] ?? null; // Assume this value is provided in the form
  if ($batch_year != null) {
    $deleteBatchQuery = "DELETE FROM student WHERE department='$department' AND year='$year' AND batch_year='$batch_year'";
    if ($conn->query($deleteBatchQuery) === TRUE) {
      $message = "Batch deleted successfully.";
    } else {
      $message = "Error deleting batch: " . $conn->error;
    }
  } else {
    $message = "Batch Year is required for Deleting a Batch.";
  }
}

// Handle individual student delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
  $studentId = $_POST['student_id'];
  $deleteStudentQuery = "DELETE FROM student WHERE id='$studentId'";
  if ($conn->query($deleteStudentQuery) === TRUE) {
    $message = "Student deleted successfully.";
  } else {
    $message = "Error deleting student: " . $conn->error;
  }
}

// Handle show students
$students = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_students'])) {
  $department = $_POST['department'];
  $year = $_POST['year'];
  $batch_year = $_POST['batch_year'] ?? null;

  $studentsQuery = "SELECT * FROM student WHERE department='$department' AND year='$year'";
  if ($batch_year) {
    $studentsQuery .= " AND batch_year='$batch_year'";
  }
  $studentsResult = $conn->query($studentsQuery);
  while ($row = $studentsResult->fetch_assoc()) {
    $students[] = $row;
  }
}

// Function to get course duration based on department
function getCourseDuration($department)
{
  global $conn;
  $query = "SELECT duration FROM courses WHERE department='$department'";
  $result = $conn->query($query);
  if ($row = $result->fetch_assoc()) {
    return $row['duration'];
  }
  return 4; // Default to 4 years if not specified
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
  <link rel="stylesheet" href="manageStudents.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <?php include "../navbar/navbar.php" ?>
    <div class="home-content">
      <div class="main-content">
        <div class="heading">
          <h1>Manage Students</h1>
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
              <select name="department" class="form-control" required>
                <option value="">--Select Department--</option>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?php echo $dept['department']; ?>"><?php echo $dept['department']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-row">
              <label for="year">Year:</label>
              <select name="year" class="form-control" required>
                <option value="First Year">First Year</option>
                <option value="Second Year">Second Year</option>
                <option value="Third Year">Third Year</option>
                <option value="Fourth Year">Fourth Year</option>
              </select>
            </div>
            <div class="form-row">
              <label for="batch_year">Batch Year:</label>
              <select name="batch_year" class="form-control">
                <option value="">--Select Batch Year--</option>
                <?php foreach ($batchYears as $year): ?>
                  <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-row">
              <input type="submit" class="btn btn-primary" name="upgrade_batch" value="Upgrade Batch"
                onclick="return confirm('Are you sure you want to upgrade this batch?');">
              <input type="submit" class="btn btn-danger" name="delete_batch" value="Delete Batch"
                onclick="return confirm('Are you sure you want to delete this batch?');">
              <input type="submit" class="btn btn-info" name="show_students" value="Show Students">
            </div>
          </form>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Department</th>
                <th>Year</th>
                <th>Photo</th>
                <th>College ID</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($students as $student): ?>
                <tr>
                  <td><?php echo $student["id"]; ?></td>
                  <td><?php echo $student["name"]; ?></td>
                  <td><?php echo $student["email"]; ?></td>
                  <td><?php echo $student["conumber"]; ?></td>
                  <td><?php echo $student["department"]; ?></td>
                  <td><?php echo $student["year"]; ?></td>
                  <td><img src="<?php echo $student["photo_url"]; ?>" alt="Student Photo" width="50" height="50"></td>
                  <td><?php echo $student["college_id"]; ?></td>
                  <td>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="student_id" value="<?php echo $student["id"]; ?>">
                      <button type="submit" name="delete_student" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
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
</body>

</html>
