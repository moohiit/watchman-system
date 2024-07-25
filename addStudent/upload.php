<?php
// Include the database connection file
include '../database.php';
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}

if (isset($_POST["submit"])) {
  // Check if the resized image data is received from the client-side JavaScript
  if (isset($_POST["resizedImageData"])) {
    // FreeImage.host API endpoint
    $apiUrl = "https://freeimage.host/api/1/upload";

    // Set your FreeImage.host API key
    $apiKey = "6d207e02198a847aa98d0a2a901485a5";

    // Get the received resized image data
    $resizedImageData = $_POST["resizedImageData"];

    // Convert the base64 image data to binary
    $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $resizedImageData));

    // Create a unique name for the image to avoid overwriting
    $uniqueName = uniqid("image_") . ".jpeg"; // Assuming you want JPEG format

    // Prepare the cURL request to FreeImage.host API
    $apiRequest = curl_init($apiUrl);
    $apiImageData = [
      'key' => $apiKey,
      'source' => base64_encode($binaryImageData),
      'format' => 'json'
    ];
    curl_setopt($apiRequest, CURLOPT_POST, 1);
    curl_setopt($apiRequest, CURLOPT_POSTFIELDS, $apiImageData);
    curl_setopt($apiRequest, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($apiRequest, CURLOPT_FAILONERROR, true);

    // Execute cURL request
    $apiResult = curl_exec($apiRequest);
    $apiHttpStatus = curl_getinfo($apiRequest, CURLINFO_HTTP_CODE);
    curl_close($apiRequest);

    // Decode the JSON response
    $apiResponse = json_decode($apiResult, true);

    // Check if the upload was successful
    if ($apiHttpStatus == 200 && isset($apiResponse['image']['url'])) {
      $imageUrl = $apiResponse['image']['url'];

      // Assuming you have form fields for name, department, year, and mobile
      $name = $_POST['name'];
      $email = $_POST['email'];
      $department = $_POST['dprt'];
      $year = $_POST['year'];
      $batchYear = $_POST['batch_year'];
      $mobile = $_POST['mobile'];
      $role = 'student';
      $password = $_POST['mobile'];

      // Get the current year
      // $currentYear = date("Y");

      // Fetch the department code from the department table
      // $sqlDeptCode = "SELECT department_code FROM department WHERE department='$department'";
      // $resultDeptCode = mysqli_query($conn, $sqlDeptCode);
      // if ($rowDeptCode = mysqli_fetch_assoc($resultDeptCode)) {
      //   $departmentCode = $rowDeptCode['department_code'];
      // } else {
      //   $_SESSION['status'] = "Error: Department code not found. Update the department Code.";
      //   header("Location: addStudent.php");
      //   exit();
      // }

      // Generate the college_id
      // $sqlCount = "SELECT COUNT(*) as count FROM student WHERE department='$department' AND year='$year'";
      // $resultCount = mysqli_query($conn, $sqlCount);
      // $rowCount = mysqli_fetch_assoc($resultCount);
      // $count = $rowCount['count'] + 1;
      // $collegeId = $departmentCode . $batchYear . str_pad($count, 3, '0', STR_PAD_LEFT);

      // SQL query to insert data into the "student" table
      $sqlStudent = "INSERT INTO student (name, email, department, year, batch_year, conumber, photo_url) VALUES ('$name','$email', '$department', '$year', '$batchYear', '$mobile', '$imageUrl')";

      // Check if student already exists
      $check_query = "SELECT * FROM student WHERE email='$email'";
      $result = mysqli_query($conn, $check_query);
      if (mysqli_num_rows($result) > 0) {
        $_SESSION["status"] = "Error: Email already exists! Try with another email.";
        header("Location:addStudent.php "); // Redirect back to signup page
        exit();
      }

      // Create SQL query to insert data into the users table
      // $sqlUsers = "INSERT INTO users (fullname, username, mobile, role, department, password) VALUES ('$name', '$email', '$mobile', '$role', '$department', '$password')";

      // Execute the query
      // $queryUsers = $conn->query($sqlUsers);
      $queryStudent = $conn->query($sqlStudent);
      if ($queryStudent === TRUE) { //&& $queryUsers === True
        $_SESSION['success'] = "Image uploaded successfully and data saved in the database.";
        // Redirect to success page or handle as needed
        header("Location: success.php");
        exit();
      } else {
        $_SESSION['status'] = "Error saving data in the database: " . $conn->error . " (Query: $sqlStudent)";
        // Redirect to addStudent page with an error message
        header("Location: addStudent.php");
        exit();
      }
    } else {
      $_SESSION['status'] = "Error uploading image to FreeImage.host. HTTP Status: $apiHttpStatus";
      // Redirect to addStudent page with an error message
      header("Location: addStudent.php");
      exit();
    }
  } else {
    // This block is executed when the form is submitted without using JavaScript
    $_SESSION['status'] = "Please select an image to upload.";
    // Redirect to addStudent page with an error message
    header("Location: addStudent.php");
    exit();
  }
}
?>