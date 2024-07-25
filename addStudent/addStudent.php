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
  <link rel="stylesheet" href="addStudent.css">
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
        <div class="heading">
          <h1>Add Students Data</h1>
        </div>
        <div class="card-container">
          <!-- Card Start here -->
            <a class="card" href="../addStudent/addsingle.php">
              <p class="big">Add</p>
              <p>Single Student</p>
            </a>
            <a class="card" href="../addStudent/addmultiple.php">
              <p class="big">Add</p>
              <p>Multiple Students</p>
            </a>        
          <!-- Card End here -->
        </div>
      <!-- Main Content Ends Here -->
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/compressorjs@1.0.5"></script>
  <script>
    async function resizeImage() {
      var input = document.getElementById('image');
      var file = input.files[0];

      if (file) {
        const compressedImage = await new Compressor(file, {
          quality: 0.5, // Adjust the quality (0 to 1)
          maxWidth: 800, // Maximum width
          maxHeight: 600, // Maximum height
          success(result) {
            // Set the resized image data to the hidden input
            const reader = new FileReader();
            reader.onload = () => {
              document.getElementById('resizedImageData').value = reader.result;

              // Optionally, display the resized image (for testing purposes)
              const resizedImage = new Image();
              resizedImage.src = reader.result;
              console.log(result);
              document.body.appendChild(resizedImage);
            };
            reader.readAsDataURL(result);
          },
          error(err) {
            console.error(err.message);
          },
        });
      }
    }

    //mobile Validation
    document.addEventListener('DOMContentLoaded', function () {
      var mobileInput = document.getElementById('mobile');
      var mobileError = document.getElementById('mobileError');

      mobileInput.addEventListener('input', function () {
        validateMobileNumber();
      });

      function validateMobileNumber() {
        var mobileRegex = /^[0-9]{10}$/;
        var mobileValue = mobileInput.value;

        if (!mobileRegex.test(mobileValue)) {
          mobileError.textContent = 'Invalid mobile number. Please enter a 10-digit number.';
          mobileInput.setCustomValidity('Invalid mobile number');
        } else {
          mobileError.textContent = '';
          mobileInput.setCustomValidity('');
        }
      }
    });
  </script>


  <script src="../scripts.js"></script>
</body>

</html>