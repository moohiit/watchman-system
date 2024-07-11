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
  <link rel="stylesheet" href="mail.css">
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
        <span class="dashboard">Send Mail</span>
      </div>
    </nav>
    <!-- Navbar ends Here -->
    <div class="home-content">
      <!-- Main Content Goes Here   -->
      <div class="main-content">
          <div class="form-container">
            <h3>Send Today's Report</h3>
            <h6>To</h6>
            <h4>Head of Department</h4>
            <form method="post" id="mailForm" action="deptMail.php" class="form">
              <div class="form-row">
                <input type="submit" value="Send Mail" name="send_mail">
              </div>
            </form>
            <div id="loader" style="display: none; ">
              <img src="loader.gif" style="width: 100px; height: 100px;" alt="Loader">
              <p>Sending Mail...</p>
            </div>
            <div id="statusMessages">
              <?php
              if (isset($_SESSION['mail_status'])) {
                echo '<div class="alert alert-success"><p>' . $_SESSION['mail_status'] . '</p></div>';
                unset($_SESSION['mail_status']);
              }
              if (isset($_SESSION['mail_error'])) {
                echo '<div class="alert alert-error"><p>' . $_SESSION['mail_error'] . '</p></div>';
                unset($_SESSION['mail_error']);
              }
              ?>
            </div>

          </div>
      </div>
      <!-- Main Content Ends Here -->
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script>
    document.getElementById('mailForm').addEventListener('submit', function () {
      // Show loader when the form is submitted
      document.getElementById('loader').style.display = 'block';
      document.getElementById('mailForm').style.display = 'none';

    });

    // Function to hide the loader when mail status is obtained
    function hideLoader() {
      document.getElementById('loader').style.display = 'none';
    }

    // Check for mail status and hide loader accordingly
    <?php
    if (isset($_SESSION['mail_status']) || isset($_SESSION['mail_error'])) {
      echo 'hideLoader();';
    }
    ?>
  </script>
  <script src="../scripts.js"></script>
</body>

</html>