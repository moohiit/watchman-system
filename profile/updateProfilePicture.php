<?php
session_start();
include '../database.php';

if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == 0) {
  // ImgBB API endpoint
  $imgbbApiUrl = "https://api.imgbb.com/1/upload";

  // Set your ImgBB API key
  $apiKey = "your_imgbb_api_key_here";

  // Read the file content
  $imageFile = $_FILES["profilePicture"]["tmp_name"];
  $binaryImageData = file_get_contents($imageFile);

  // Prepare the cURL request to ImgBB API
  $imgbbRequest = curl_init($imgbbApiUrl);
  $imgbbImageData = [
    'key' => $apiKey,
    'image' => base64_encode($binaryImageData),
  ];
  curl_setopt($imgbbRequest, CURLOPT_POST, 1);
  curl_setopt($imgbbRequest, CURLOPT_POSTFIELDS, $imgbbImageData);
  curl_setopt($imgbbRequest, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($imgbbRequest, CURLOPT_FAILONERROR, true);
  curl_setopt($imgbbRequest, CURLOPT_TIMEOUT, 30); // Increase timeout if needed

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
    $updateSql = "UPDATE users SET photo_url='$imageUrl' WHERE id=$userId";
    if ($conn->query($updateSql) === TRUE) {
      $_SESSION['profile_picture'] = "Profile picture updated successfully.";
    } else {
      $_SESSION['profile_picture'] = "Error updating profile picture in the database: " . $conn->error;
    }
  } else {
    $_SESSION['profile_picture'] = "Error uploading image to ImgBB. HTTP Status: $imgbbHttpStatus";
  }
}

// Redirect back to profile page
header("Location: profile.php");
exit();
?>