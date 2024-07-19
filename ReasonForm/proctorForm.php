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
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <!-- Navbar start Here -->
    <?php include "../navbar/navbar.php" ?>
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
                <select name="reason" id="reason" onchange="checkReason(this.value)">
                  <?php
                  $reasonSql = "SELECT * FROM reasons";
                  $reasonResult = mysqli_query($conn, $reasonSql);
                  while ($reasonRow = mysqli_fetch_assoc($reasonResult)) {
                    echo "<option value='" . $reasonRow['reason'] . "'>" . $reasonRow['reason'] . "</option>";
                  }
                  ?>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="form-row">
                <input type="text" name="other_reason" id="other_reason" placeholder="Fill Reason" style="display:none;">
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
            if ($reason === "Other") {
              $reason = $_POST['other_reason'];
              // Insert the new reason into the reasons table
              $insertReasonSql = "INSERT INTO reasons (reason) VALUES ('$reason')";
              mysqli_query($conn, $insertReasonSql);
            }
            $issue = $_POST['issue'];
            $author = $_POST['author'];
            date_default_timezone_set('Asia/Kolkata');
            $currentTime = date('H:i:s');
            // Inserting the data into database
            $sql = "INSERT INTO inqury_data(student_id, name, dprt, year, contact, reason, authorisedBy, status,currentime, photo_url) VALUES ('$student_id', '$name', '$dept', '$year', '$num', '$reason', '$author', '$issue', '$currentTime', '$photo')";
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
  <script>
    function checkReason(value) {
      var otherReason = document.getElementById("other_reason");
      if (value === "Other") {
        otherReason.style.display = "block";
      } else {
        otherReason.style.display = "none";
      }
    }
  </script>
  <script src="../scripts.js"></script>
</body>

</html>
