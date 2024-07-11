<div class="sidebar">
  <div class="logo-details">
    <span class="logo_name" style="margin-left:1px"><img src="../logo.png" alt="KCMT" /></span>
    <h5>Watchman System</h5>
  </div>
  <ul class="nav-links">
    <?php
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
      ?>
      <li class="nav-link">
        <a href="../dashboard/dashboard.php">
          <i class="bx bx-home-alt icon"></i>
          <span class="text nav-text">Dashboard</span>
        </a>
      </li>
    <?php } ?>
    <li class="nav-link">
      <a href="../Search/search.php">
        <i class="bx bx-search icon"></i>
        <span class="text nav-text">Search Student</span>
      </a>
    </li>
    <?php
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
      ?>
      <li class="nav-link">
        <a href="../addStudent/addStudent.php">
          <i class="bx bx-user-plus icon"></i>
          <span class="text nav-text">Add Student</span>
        </a>
      </li>
    <?php } ?>
    <?php
    if ($_SESSION['role'] == 'hod' || $_SESSION['role'] == 'classIncharge') {
      ?>
      <li class="nav-link">
        <a href="../Analytics/analyticshod.php">
          <i class="bx bx-bar-chart icon"></i>
          <span class="text nav-text">Analytics</span>
        </a>
      </li>
    <?php } ?>
    <?php
    if ($_SESSION['role'] == 'admin') {
      ?>
      <li class="nav-link">
        <a href="../Analytics/analytics.php">
          <i class="bx bx-bar-chart icon"></i>
          <span class="text nav-text">Analytics</span>
        </a>
      </li>
      <li class="nav-link">
        <a href="../seemore/approve_status.php">
          <i class="bx bx-check-circle icon"></i>
          <span class="text nav-text">Approve</span>
        </a>
      </li>
    <?php } ?>
      <li class="nav-link">
        <a href="../Visitor/Visitor.php">
          <i class='bx bx-group icon'></i>
          <span class="text nav-text">Visitor Section</span>
        </a>
      </li>
      <?php
      if ($_SESSION['role'] == 'admin') {
        ?>
      <li class="nav-link">
        <a href="../mail/mail.php">
          <i class="bx bx-mail-send icon"></i>
          <span class="text nav-text">Send Report</span>
        </a>
      </li>
    <?php } ?>
    <li class="log_out nav-link">
      <a href="../logout.php">
        <i class='bx bx-log-out bx-fade-left-hover'></i>
        <span class="links_name">Log out</span>
      </a>
    </li>
  </ul>
</div>