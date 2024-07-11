<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $student_id = $_POST['student_id'];
  $currentime = $_POST['currentime'];
  $deleteStatus = $_POST['deleteStatus'];

  $sql = "UPDATE inqury_data SET delete_status=? WHERE student_id=? AND currentime=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $deleteStatus, $student_id, $currentime);

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }

  $stmt->close();
  $conn->close();
}
?>