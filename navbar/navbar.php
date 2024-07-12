<?php
include '../database.php';
$userId = $_SESSION['userId'];
$sql = "SELECT id, fullname, username AS email, mobile, password, role, department, status, photo_url FROM users WHERE id = $userId";
$photo_result = $conn->query($sql);
$user = $photo_result->fetch_assoc();
$photo_url = isset($user['photo_url']) && !empty($user['photo_url']) ? $user['photo_url'] : '../profile.png';
?>

<nav>
  <div class="sidebar-button">
    <i class='bx bx-menu sidebarBtn'></i>
    <span class="dashboard">Watchman System</span>
  </div>
  <div class="avt dropdown">
    <button class="dropdown-toggle" id="profile-dropdown-toggle">
      <img src="<?php echo $photo_url; ?>" alt="Profile Avatar" class="profile-avatar">
    </button>
    <ul class="dropdown-menu" id="profile-dropdown">
      <li><a href="../profile/profile.php?id=<?php echo $_SESSION['userId']; ?>">Profile</a></li>
      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </div>
</nav>