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
  <link rel="stylesheet" href="studentProfile.css">
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
        <span class="dashboard">Profile</span>
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
          $sql = "SELECT * from student where id='{$id}'";
        }else if (isset($_GET['id'])) {
          $id = $_GET['id'];
          $_SESSION['studentId'] = $id;
          $sql = "SELECT * from student where id='{$id}'";
        } else if (isset($_SESSION['studentId'])) {
          $id = $_SESSION['studentId'];
          $sql = "SELECT * from student where id='{$id}'";
        }
        ?>
        <div class="heading">
          <h1>
            Student Profile
          </h1>
        </div>
        <div class="form-container">
          <?php
          
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['id'];
            $photo = $row['photo_url'];
            $department = $row['department'];
            ?>
            <form class="form">
              <h2 class="form-heading">Student Details</h2>
              <div>
                <img class="table-image" src="<?php echo $photo; ?>" alt="Photo">
              </div>
              <div>
                <div class="form-row">
                  <label for="name">Name:</label>
                  <p>
                    <?php echo strtoupper($row["name"]); ?>
                  </p>
                </div>
                <div class="form-row">
                  <label for="dprt">Department:</label>
                  <p>
                    <?php echo strtoupper($row['department']); ?>
                  </p>
                </div>
                <div class="form-row">
                  <label for="year">Year:</label>
                  <p>
                    <?php echo strtoupper($row['year']); ?>
                  </p>
                </div>
                <div class="form-row">
                  <label for="num">Number:</label>
                  <p>
                    <?php echo $row['conumber']; ?>
                  </p>
                </div>
              </div>
            </form>
          <?php } ?>
        </div>
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