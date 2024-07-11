<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
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
  <link rel="stylesheet" href="addVisitor.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php include "../sidebar/sidebar.php" ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Visitor Section</span>
      </div>
    </nav>
    <div class="home-content">
      <div class="main-content">
        <div class="form-container">
          <form action="add.php" class="form" method="post" enctype="multipart/form-data">
            <h2 class="form-heading">Visitor Details</h2>
            <div class="form-row">
              <label for="name">Name : </label>
              <input type="text" name="name">
            </div>
            <div class="form-row">
              <label for="mobile">Mobile No. : </label>
              <input type="text" name="mobile" id="mobile" required>
              <small id="mobileError" style="color: red;" class="form-error"></small>
            </div>
            <div class="form-row">
              <label for="reason">Reason : </label>
              <select name="reason" id="reason" onchange="showOtherReasonField()">
                <option value="Enquiry">Enquiry</option>
                <option value="Meeting">Meeting</option>
                <option value="Delivery">Delivery</option>
                <option value="Interview">Interview</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="form-row" id="otherReasonRow" style="display: none;">
              <label for="otherReason">Other Reason : </label>
              <input type="text" name="otherReason" id="otherReason">
            </div>
            <div class="form-row">
              <label for="whom">Visit Whom : </label>
              <select name="whom" id="whom">
                <option value="Director">Director General</option>
                <option value="Principal">Principal</option>
              </select>
            </div>
            <div class="form-row">
              <label for="Location">Location : </label>
              <input type="text" name="Location" id="Location" placeholder="Location">
            </div>
            <div class="form-row">
              <input type="submit" value="submit" class="btn" name="submit">
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

    function showOtherReasonField() {
      var reasonSelect = document.getElementById('reason');
      var otherReasonRow = document.getElementById('otherReasonRow');
      if (reasonSelect.value === 'Other') {
        otherReasonRow.style.display = 'block';
      } else {
        otherReasonRow.style.display = 'none';
      }
    }
  </script>
  <script src="../scripts.js"></script>
</body>

</html>