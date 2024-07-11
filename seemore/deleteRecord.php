<?php
// Include the database connection file
include '../database.php';

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the student_id and currentime from the POST data
  $student_id = $_POST['student_id'];
  $currentime = $_POST['currentime'];

  // Prepare and execute the DELETE query to delete the record
  $sql = "DELETE FROM inqury_data WHERE student_id = ? AND currentime = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $student_id, $currentime);
  $stmt->execute();

  // Check if the query was successful
  if ($stmt->affected_rows > 0) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>