<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
include '../database.php';

if (isset($_POST["submit"])) {
  $name = $_POST['name'];
  $mobile = $_POST['mobile'];
  $reason = $_POST['reason'];
  $otherReason = $_POST['otherReason'];
  $whom = $_POST['whom'];
  $location = $_POST['location'];

  if ($reason === 'Other') {
    $reason = $otherReason;
  }

  // Inserting the data into the database
  $sql = "INSERT INTO visitor(name, mobile, reason, visit_whom, location) VALUES ('{$name}', '{$mobile}', '{$reason}', '{$whom}', '{$location}')";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("Location: success.php");
    exit(); // Ensure that no further code is executed after the header
  } else {
    echo "Error: " . mysqli_error($conn);
  }

  ob_end_flush();
}
