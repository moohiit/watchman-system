<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';

$userId = $_SESSION['userId'];
$sql = "SELECT id, fullname, username AS email, mobile, password, role, department, status FROM users WHERE id = $userId";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $password = $_POST['password'];

  $updateSql = "UPDATE users SET fullname='$fullname', username='$email', mobile='$mobile', password='$password' WHERE id=$userId";
  if ($conn->query($updateSql) === TRUE) {
    $message = "Profile updated successfully.";
  } else {
    $message = "Error updating profile: " . $conn->error;
  }

  $sql = "SELECT id, fullname, username AS email, mobile, password, role, department, status FROM users WHERE id = $userId";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();
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
  <link rel="stylesheet" href="profile.css">
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
        <span class="dashboard">Watchman System</span>
      </div>
      <div class="avt dropdown">
        <button class="dropdown-toggle" id="profile-dropdown-toggle">
          <img src="<?php echo isset($user['photo_url']) ? $user['photo_url'] : '../profile.png'; ?>"
            alt="Profile Avatar" class="profile-avatar">
        </button>
        <ul class="dropdown-menu" id="profile-dropdown">
          <li><a href="../profile/profile.php?id=<?php echo $_SESSION['userId']; ?>">Profile</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
    <div class="home-content">
      <div class="main-content">
        <h2>
          <center>User Profile</center>
        </h2>
        <?php if (isset($message) || isset($_SESSION['profile_picture'])): ?>
          <div class="alert alert-info">
            <center>
              <?php echo $message; ?>
              <?php echo isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : ''; ?>
              <?php unset($_SESSION['profile_picture']); ?>
              <?php unset($message); ?>
            </center>
          </div>
        <?php endif; ?>
        <div class="form-container">
          <div class="form-wrapper">
            <div class="profile-picture">
              <img src="<?php echo isset($user['photo_url']) ? $user['photo_url'] : '../profile.png'; ?>"
                alt="Profile Picture" id="profilePicture">
              <a href="#" id="updateProfilePictureLink">Update Profile Picture</a>
            </div>
            <form id="profileForm" method="POST" action="updateProfile.php">
              <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" class="form-control" id="fullname" name="fullname"
                  value="<?php echo $user['fullname']; ?>" readonly>
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>"
                  readonly>
              </div>
              <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user['mobile']; ?>"
                  readonly>
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password"
                  value="<?php echo $user['password']; ?>" readonly>
              </div>
              <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" class="form-control" id="role" value="<?php echo $user['role']; ?>" readonly>
              </div>
              <?php if ($user['department']): ?>
                <div class="form-group">
                  <label for="department">Department:</label>
                  <input type="text" class="form-control" id="department" value="<?php echo $user['department']; ?>"
                    readonly>
                </div>
              <?php endif; ?>
              <div class="form-group">
                <label for="status">Status:</label>
                <input type="text" class="form-control" id="status" value="<?php echo $user['status']; ?>" readonly>
              </div>
              <div class="button-group">
                <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
                <button type="submit" class="btn btn-success" id="updateBtn" style="display: none;">Update</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn" style="display: none;">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Profile Picture Modal -->
    <div id="updateProfilePictureModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <form id="updateProfilePictureForm" method="POST" enctype="multipart/form-data"
          action="updateProfilePicture.php">
            <label for="profilePictureInput">Select a new profile picture:</label>
            <input type="file" class="form-control-file" id="profilePictureInput" name="profilePicture">
          <div class="button-group">
            <button type="submit" class="btn btn-success" name="submit">Upload</button>
          </div>
        </form>
      </div>
    </div>



    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var editBtn = document.getElementById('editBtn');
      var updateBtn = document.getElementById('updateBtn');
      var cancelBtn = document.getElementById('cancelBtn');
      var formFields = ['fullname', 'email', 'mobile', 'password'];

      editBtn.addEventListener('click', function () {
        formFields.forEach(function (field) {
          document.getElementById(field).removeAttribute('readonly');
        });
        editBtn.style.display = 'none';
        updateBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'inline-block';
      });

      cancelBtn.addEventListener('click', function () {
        formFields.forEach(function (field) {
          document.getElementById(field).setAttribute('readonly', 'readonly');
        });
        editBtn.style.display = 'inline-block';
        updateBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        location.reload();
      });

      document.getElementById('updateProfilePictureLink').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('updateProfilePictureModal').style.display = 'block';
      });

      document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('updateProfilePictureModal').style.display = 'none';
      });

      window.onclick = function (event) {
        if (event.target == document.getElementById('updateProfilePictureModal')) {
          document.getElementById('updateProfilePictureModal').style.display = 'none';
        }
      };
    });
  </script>
  <script src="../scripts.js"></script>
</body>

</html>