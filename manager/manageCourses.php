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

// Fetch departments for the dropdown
$departments = [];
$departmentQuery = "SELECT * FROM department";
$departmentResult = $conn->query($departmentQuery);
while ($row = $departmentResult->fetch_assoc()) {
  $departments[] = $row;
}

// Handle add course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
  $department = $_POST['department'];
  $duration = $_POST['duration'];

  $addCourseQuery = "INSERT INTO courses (department, duration) VALUES ('$department', '$duration')";
  if ($conn->query($addCourseQuery) === TRUE) {
    $message = "Course added successfully.";
  } else {
    $message = "Error adding course: " . $conn->error;
  }
}

// Handle update course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
  $courseId = $_POST['course_id'];
  $department = $_POST['department'];
  $duration = $_POST['duration'];

  $updateCourseQuery = "UPDATE courses SET department='$department', duration='$duration' WHERE id='$courseId'";
  if ($conn->query($updateCourseQuery) === TRUE) {
    $message = "Course updated successfully.";
  } else {
    $message = "Error updating course: " . $conn->error;
  }
}

// Handle delete course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
  $courseId = $_POST['course_id'];
  $deleteCourseQuery = "DELETE FROM courses WHERE id='$courseId'";
  if ($conn->query($deleteCourseQuery) === TRUE) {
    $message = "Course deleted successfully.";
  } else {
    $message = "Error deleting course: " . $conn->error;
  }
}

// Fetch all courses for display
$courses = [];
$coursesQuery = "SELECT * FROM courses";
$coursesResult = $conn->query($coursesQuery);
while ($row = $coursesResult->fetch_assoc()) {
  $courses[] = $row;
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
  <link rel="stylesheet" href="manageCourses.css">
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
          <h1>Manage Courses</h1>
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
              <label for="duration">Duration:</label>
              <input type="number" name="duration" class="form-control" required>
            </div>
            <div class="form-row">
              <input type="submit" class="btn btn-primary" name="add_course" value="Add Course">
            </div>
          </form>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Duration</th>
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($courses as $course): ?>
                <tr>
                  <td><?php echo $course["id"]; ?></td>
                  <td><?php echo $course["department"]; ?></td>
                  <td><?php echo $course["duration"]; ?></td>
                  <td>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="course_id" value="<?php echo $course["id"]; ?>">
                      <button type="submit" name="delete_course" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this course?');">Delete</button>
                    </form>
                  </td>
                  <td>
                    <button type="button" class="btn btn-info"
                      onclick="showUpdateForm(<?php echo $course['id']; ?>, '<?php echo $course['department']; ?>', <?php echo $course['duration']; ?>)">Update</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div id="updateFormContainer" style="display:none;">
          <h3>Update Course</h3>
          <form class="form" method="POST">
            <input type="hidden" name="course_id" id="updateCourseId">
            <div class="form-row">
              <label for="department">Department:</label>
              <select name="department" id="updateDepartment" class="form-control" required>
                <option value="">--Select Department--</option>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?php echo $dept['department']; ?>"><?php echo $dept['department']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-row">
              <label for="duration">Duration:</label>
              <input type="number" name="duration" id="updateDuration" class="form-control" required>
            </div>
            <div class="form-row">
              <input type="submit" class="btn btn-primary" name="update_course" value="Update Course">
            </div>
          </form>
        </div>
      </div>
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script>
    function showUpdateForm(courseId, department, duration) {
      document.getElementById('updateFormContainer').style.display = 'block';
      document.getElementById('updateCourseId').value = courseId;
      document.getElementById('updateDepartment').value = department;
      document.getElementById('updateDuration').value = duration;
    }
  </script>
  <script src="../scripts.js"></script>
</body>

</html>