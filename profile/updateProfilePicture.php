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
    // ImgBB API endpoint
    $imgbbApiUrl = "https://api.imgbb.com/1/upload";

    // Set your ImgBB API key
    $apiKey = "1ada3b3c96d91d229518a4bb06c14452";

    // Read the file content
    $imageFile = $_FILES["profilePicture"]["tmp_name"];
    $binaryImageData = file_get_contents($imageFile);

    // Create a unique name for the image to avoid overwriting
    $uniqueName = uniqid("image_") . ".jpeg"; // Assuming you want JPEG format

    // Prepare the cURL request to ImgBB API
    $imgbbRequest = curl_init($imgbbApiUrl);
    $imgbbImageData = [
      'key' => $apiKey,
      'image' => base64_encode($binaryImageData),
      'name' => $uniqueName,
    ];
    curl_setopt($imgbbRequest, CURLOPT_POST, 1);
    curl_setopt($imgbbRequest, CURLOPT_POSTFIELDS, $imgbbImageData);
    curl_setopt($imgbbRequest, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($imgbbRequest, CURLOPT_FAILONERROR, true);

    // Execute cURL request
    $imgbbResult = curl_exec($imgbbRequest);
    $imgbbHttpStatus = curl_getinfo($imgbbRequest, CURLINFO_HTTP_CODE);
    curl_close($imgbbRequest);

    // Decode the JSON response
    $imgbbResponse = json_decode($imgbbResult, true);

    // Check if the upload was successful
    if ($imgbbHttpStatus == 200 && isset($imgbbResponse['data']['url'])) {
      $imageUrl = $imgbbResponse['data']['url'];

      // Update the user's photo_url in the database
      $userId = $_SESSION['userId'];
      $sql = "UPDATE users SET photo_url='$imageUrl' WHERE id='$userId'";
      if ($conn->query($sql) === TRUE) {
        $_SESSION['profile_picture'] = "Profile picture updated successfully.";
      } else {
        $_SESSION['profile_picture'] = "Error updating profile picture in the database: " . $conn->error;
      }
    } else {
      $_SESSION['profile_picture'] = "Error uploading image to ImgBB. HTTP Status: $imgbbHttpStatus";
    }
  } else {
    $_SESSION['profile_picture'] = "Please select an image to upload.";
  }

  // Redirect back to the profile page
  header("Location: profile.php");
  exit();
}
?>