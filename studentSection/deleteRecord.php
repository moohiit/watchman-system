<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student_id = $_POST['student_id'];
  $currentime = $_POST['currentime'];

  $sql = "DELETE FROM inqury_data WHERE student_id=? AND currentime=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $student_id, $currentime);

  if ($stmt->execute()) {
    echo 'success';
  } else {
    echo 'error';
  }

  $stmt->close();
  $conn->close();
}
?>