<?php
// Include the database connection file
include '../database.php';

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the student_id, currentime, and new reason from the POST data
  $student_id = $_POST['student_id'];
  $currentime = $_POST['currentime'];
  $new_reason = $_POST['reason'];

  // Prepare and execute the UPDATE query to update the reason
  $sql = "UPDATE inqury_data SET reason = ? WHERE student_id = ? AND currentime = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $new_reason, $student_id, $currentime);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt->affected_rows > 0) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>