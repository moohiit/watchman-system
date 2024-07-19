<?php
// Include the database connection file
include '../database.php';
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}

if (isset($_POST["submit"])) {
  // Check if a file is uploaded
  if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == 0) {
    // FreeImage.host API endpoint
    $apiUrl = "https://freeimage.host/api/1/upload";

    // Set your FreeImage.host API key
    $apiKey = "6d207e02198a847aa98d0a2a901485a5";

    // Read the file content
    $imageFile = $_FILES["profilePicture"]["tmp_name"];
    $binaryImageData = file_get_contents($imageFile);

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

      // Update the user's photo_url in the database
      $userId = $_SESSION['userId'];
      $sql = "UPDATE users SET photo_url='$imageUrl' WHERE id='$userId'";
      if ($conn->query($sql) === TRUE) {
        $_SESSION['profile_picture'] = "Profile picture updated successfully.";
      } else {
        $_SESSION['profile_picture'] = "Error updating profile picture in the database: " . $conn->error;
      }
    } else {
      $_SESSION['profile_picture'] = "Error uploading image to FreeImage.host. HTTP Status: $apiHttpStatus";
    }
  } else {
    $_SESSION['profile_picture'] = "Please select an image to upload.";
  }

  // Redirect back to the profile page
  header("Location: profile.php");
  exit();
}
?>
