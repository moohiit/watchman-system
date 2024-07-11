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
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Add Student</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
        <?php
        // Check if the user is an admin
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hod') {
          ?>
          <div class=access-denied>
            <?php
            echo "<div>You do not have permission to access this page.</div>";
            // You may also redirect to a limited access page or the login page.
            ?>
            <div>
              <a style="text-decoration: none;" href="../Search/search.php">Go to Homepage</a>
            </div>
          </div>
          <?php
        } else {
          ?>
          <div class="form-container">
            <form action="upload.php" class="form" method="post" enctype="multipart/form-data">
              <h2 class="form-heading">Student Details</h2>
              <center>
                <h3 style="color: red;font-size: x-small;">
                  <?php if (isset($_SESSION['status'])) {
                    echo $_SESSION['status'];
                    unset($_SESSION['status']);
                  }
                  ?>
                </h3>
              </center>
              <div class="form-row">
                <label for="name">Name</label>
                <input type="text" name="name">
              </div>
              <div class="form-row">
                <label for="email">Email</label>
                <input type="text" name="email">
              </div>
              <div class="form-row">
                <label for="dprt">Department</label>
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
                  } ?>
                </select>
              </div>
              <div class="form-row">
                <label for="year">Year</label>
                <select name="year" required>
                  <option value="First Year">First Year</option>
                  <option value="Second Year">Second Year</option>
                  <option value="Third Year">Third Year</option>
                </select>
              </div>
              <div class="form-row">
                <label for="mobile">Mobile No.</label>
                <input type="text" name="mobile" id="mobile" required>
                <small id="mobileError" style="color: red;" class="form-error"></small>
              </div>
              <div class="form-row">
                <label for="">Photo:</label>
                <input type="file" name="image" id="image" accept="image/*" required onchange="resizeImage()">
                <!-- Hidden input for resized image data -->
                <input type="hidden" name="resizedImageData" id="resizedImageData">
              </div>
              <div class="form-row">
                <input type="submit" value="submit" class="btn" name="submit">
              </div>
            </form>
          </div>
        <?php } ?>
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