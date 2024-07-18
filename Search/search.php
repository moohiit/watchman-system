<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}

// Define the number of results per page
$results_per_page = 48;

// Determine which page number visitor is currently on
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

// Determine the SQL LIMIT starting number for the results on the displaying page
$this_page_first_result = ($page - 1) * $results_per_page;
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
  <link rel="stylesheet" href="search.css">
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
          <h1>Search Student</h1>
        </div>
        <div class="form-container">
          <form method="post" class="form">
            <div class="form-row">
              <label for="department">Department:</label>
              <select name="department">
                <option value="">Select Department</option>
                <?php
                include "../database.php";
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
              <label for="name" class="label">Search:</label>
              <input type="text" id="search" name="search" class="input" placeholder="Name or ID" autocomplete="off">
            </div>
            <div class="form-row">
              <input type="submit" value="Search" name="btn">
            </div>
          </form>
        </div>
        <?php
        include '../database.php';
        if (isset($_POST["btn"]) || isset($_GET['page'])) {
          $search = $_POST['search'] ?? '';
          $department = $_POST['department'] ?? '';

          // Construct the base SQL query
          $sql = "SELECT * FROM student WHERE ";

          // Add conditions based on search input
          if (!empty($search)) {
            $sql .= "(name LIKE '%$search%' OR id = '$search')";
          } else {
            $sql .= "1"; // If search is empty, select all records
          }

          // If department is selected, add department condition to the query
          if (!empty($department)) {
            $sql .= " AND department = '$department'";
          }

          // Get the total number of results
          $result = mysqli_query($conn, $sql);
          $number_of_results = mysqli_num_rows($result);

          // Calculate the total number of pages
          $number_of_pages = ceil($number_of_results / $results_per_page);

          // Add the LIMIT clause to the SQL query
          $sql .= " LIMIT " . $this_page_first_result . "," . $results_per_page;

          $result = mysqli_query($conn, $sql);
          ?>
          <div class="card-container">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              // Display search results
              $id = $row["id"];
              $name = $row["name"];
              $email = $row["email"];
              $department = $row["department"];
              $conumber = $row["conumber"];
              $photo_url = $row["photo_url"];
              $userRole = $_SESSION['role'];
              if ($userRole === 'admin' || $userRole === 'hod') {
                $link = "./showStudent.php?id=$id";
              } else {
                $link = "../ReasonForm/proctorForm.php?id=$id";
              }
              ?>
              <!-- Card Start here -->
              <a class="card" href="<?php echo $link; ?>">
                <div class="image">
                  <img src="<?php echo $photo_url; ?>" alt="Image Not Updated Yet" width="100px" height="100px">
                </div>
                <div class="data">
                  <p class="id">
                    <?php echo $row["college_id"]; ?>
                  </p>
                  <p class="name">
                    <?php echo $row["name"]; ?>
                  </p>
                  <p class="conumber">
                    <?php echo $row["conumber"]; ?>
                  </p>
                  <p class="department">
                    <?php echo $row["department"]; ?>
                  </p>
                </div>
              </a>
              <!-- Card End here -->
              <?php
            }
            ?>
          </div>
          <!-- Pagination Links -->
          <div class="pagination">
            <?php
            // Display pagination controls
            if ($page > 1) {
              echo '<a href="search.php?page=' . ($page - 1) . '">&laquo; Previous</a>';
            }

            for ($i = 1; $i <= $number_of_pages; $i++) {
              if ($i == $page) {
                echo '<a class="active" href="search.php?page=' . $i . '">' . $i . '</a>';
              } else {
                echo '<a href="search.php?page=' . $i . '">' . $i . '</a>';
              }
            }

            if ($page < $number_of_pages) {
              echo '<a href="search.php?page=' . ($page + 1) . '">Next &raquo;</a>';
            }
            ?>
          </div>
          <?php
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