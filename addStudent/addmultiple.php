<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';
require './vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

$successMessage = '';
if (isset($_POST["submit"])) {
  $file = $_FILES['file']['tmp_name'];
  $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
  $spreadsheet = $reader->load($file);
  $worksheet = $spreadsheet->getActiveSheet();
  $rows = $worksheet->toArray();

  // Skip the header row
  unset($rows[0]);

  foreach ($rows as $row) {
    $name = $row[0];
    $email = $row[1];
    $department = $row[2];
    $mobile = $row[3];
    $year = $row[4];
    $batch_year = $row[5];

    // Insert data into database
    $sql = "INSERT INTO student (name, email, department, conumber, year, batch_year)
                                VALUES ('$name', '$email', '$department', '$mobile', '$year', '$batch_year')";

    if ($conn->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }

  $successMessage = "Data successfully imported!";
  $conn->close();
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
  <link rel="stylesheet" href="addmultiple.css">
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
    <?php include "../navbar/navbar.php" ?>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <div class="container">
          <h2>Upload Students Data</h2>
          <?php
          if ($successMessage) {
            echo "<div class='alert alert-success' role='alert'>$successMessage</div>";
          }
          ?>
          <form class="form" action="" method="post" enctype="multipart/form-data">
            <div class="form-row">
              <input type="file" name="file" accept=".csv, .xlsx" required>
            </div>
            <div class="form-row">
              <input class="btn" type="submit" name="submit" value="Upload">
            </div>
          </form>
          <div class="note">
            Please include these headers in the Excel or CSV file: <br>
            <strong>Name, Email, Department, Mobile, Year, Batch Year</strong>
          </div>
          <div class="download-link">
            <a href="./blank_csv_with_headers.csv" download="blank_csv_with_headers.csv"> Download a blank template with
              defined headers:
              Blank CSV Template</a>
          </div>
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