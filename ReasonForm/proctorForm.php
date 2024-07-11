<?php
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
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
  <link rel="stylesheet" href="reasonForm.css">
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
        <span class="dashboard">Student Info</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="form-container">
          <?php
          $sql = "SELECT * from student where id='{$id}'";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['id'];
            $photo = $row['photo_url'];
            ?>
            <form class="form" method="post">
              <h2 class="form-heading">Student Details</h2>
              <div class="form-row">
                <label for="name">Name:</label>
                <input type="text" name="name" value='<?php echo strtoupper($row["name"]); ?>' readonly>
              </div>
              <div class="form-row">
                <label for="dprt">Department:</label>
                <input type="text" name="dprt" value="<?php echo strtoupper($row['department']); ?>" readonly>
              </div>
              <div class="form-row">
                <label for="year">Year:</label>
                <input type="text" name="year" value="<?php echo strtoupper($row['year']); ?>" readonly>
              </div>
              <div class="form-row">
                <label for="num">Number:</label>
                <input type="text" name="num" value="<?php echo $row['conumber']; ?>" readonly>
              </div>
              <div class="form-row">
                <label for="issue">Issue:</label>
                <select name="issue" id="issue">
                  <option value="Late">Late Entry</option>
                  <option value="Early">Early Exit</option>
                  <option value="Tie">Tie</option>
                  <option value="Belt">Belt</option>
                  <option value="Shoes">Shoes</option>
                  <option value="Incomplete Dress">Incomplete Dress</option>
                  <option value="ID">ID</option>
                  <option value="Haircut">Need a Haircut</option>
                  <option value="Shave">Need a Shave</option>
                  <option value="Other">Others</option>
                </select>
              </div>
              <div class="form-row">
                <label for="author">Authorised By:</label>
                <select name="author" id="author" required>
                  <option value="Chief Proctor" selected>Chief Proctor</option>
                  <option value="HOD">HOD</option>
                  <option value="Director/Principal">Director/Principal</option>
                </select>
              </div>
              <div class="form-row">
                <label for="reason">Reason:</label>
                <textarea name="reason" placeholder="Fill Reason" required></textarea>
              </div>
              <div class="form-row">
                <input type="submit" class="btn" name="btn">
              </div>
            </form>
          </div>
          <?php
          }
          if (isset($_POST["btn"])) {
            $name = $_POST['name'];
            $dept = $_POST['dprt'];
            $year = $_POST['year'];
            $num = $_POST['num'];
            $reason = $_POST['reason'];
            $issue = $_POST['issue'];
            $author = $_POST['author'];

            //Inserting the data into database
            $sql = "INSERT INTO inqury_data(student_id,name,dprt,year,contact,reason,authorisedBy,status,photo_url) values('{$student_id}','{$name}','{$dept}','{$year}','{$num}','{$reason}','{$author}','{$issue}','{$photo}')";
            $result = mysqli_query($conn, $sql);
            header('Location: ../success/success.php');
            ob_end_flush();
          }
          ?>
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