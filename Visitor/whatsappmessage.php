<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}

include '../database.php';

if (isset($_POST["submit"])) {
  $name = $_POST['name'];
  $mobile = $_POST['mobile'];
  $reason = $_POST['reason'];
  $otherReason = $_POST['otherReason'];
  $whom = $_POST['whom'];
  $location = $_POST['Location'];
  $phone = $_POST["whom_mobile"];

  if ($reason === 'Other') {
    $reason = $otherReason;
  }

  // Debugging output
  // echo "Whom Mobile: " . $phone;
  // exit();

  // Inserting the data into the database
  $sql = "INSERT INTO visitor(name, mobile, reason, visit_whom, location) VALUES ('{$name}', '{$mobile}', '{$reason}', '{$whom}', '{$location}')";
  $result = mysqli_query($conn, $sql);

  require_once ('./vendor/autoload.php'); // if you use Composer
  //require_once('ultramsg.class.php'); // if you download ultramsg.class.php
  $token = "9bxqsmzoeua477h8"; // Ultramsg.com token
  $instance_id = "instance89948"; // Ultramsg.com instance id
  $client = new UltraMsg\WhatsAppApi($token, $instance_id);

  // Updated WhatsApp message format
  $message = "Hello,\n\nYou have a visitor who would like to meet you.\n\n"
    . "Visitor Details:\n"
    . "Name: {$name}\n"
    . "Mobile: {$mobile}\n"
    . "Location: {$location}\n"
    . "Reason: {$reason}\n\n"
    . "Please be prepared for their visit.\n\n"
    . "Thank you.";

  $api = $client->sendChatMessage($phone, $message);
  print_r($api);

  if ($result) {
    header("Location: success.php");
    exit(); // Ensure that no further code is executed after the header
  } else {
    echo "Error: " . mysqli_error($conn);
  }

  ob_end_flush();
}
?>