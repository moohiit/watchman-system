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
  date_default_timezone_set('Asia/Kolkata');
  $currentTime = date('H:i:s');
  if ($reason === 'Other') {
    $reason = $otherReason;
  }

  require_once ('./vendor/autoload.php'); // if you use Composer
  //require_once('ultramsg.class.php'); // if you download ultramsg.class.php
  $token = "17btnqgyxw2r7k8z"; // Ultramsg.com token
  $instance_id = "instance90216"; // Ultramsg.com instance id
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

  // Check the response from UltraMsg API
  if ($api) {
    // Inserting the data into the database only if the WhatsApp message is sent successfully
    $sql = "INSERT INTO visitor(name, mobile, reason,entry_time, visit_whom, location) VALUES ('{$name}', '{$mobile}', '{$reason}', '{$currentTime}', '{$whom}', '{$location}')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $_SESSION['whatsapp_status'] = "Success: Visitor information saved and WhatsApp message sent successfully.";
      header("Location: success.php");
      exit(); // Ensure that no further code is executed after the header
    } else {
      $_SESSION['whatsapp_status'] = "Error: " . mysqli_error($conn);
      header("Location: addVisitor.php");
      exit();
    }
  } else {
    $_SESSION['whatsapp_status'] = "Error: Failed to send WhatsApp message.";
    header("Location: addVisitor.php");
    exit();
  }
}
ob_end_flush();
?>