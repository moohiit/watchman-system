<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: ../index.php");
  exit();
}
include '../database.php';

// Fetch user details
$userId = $_SESSION['userId'];
$role = $_SESSION['role'];

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $receiverId = $_POST['receiver_id'];
  $message = $_POST['message'];

  $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ($userId, $receiverId, '$message')";
  if ($conn->query($sql) === TRUE) {
    $message = "Message sent successfully.";
  } else {
    $message = "Error sending message: " . $conn->error;
  }
}

// Fetch users for admin to send messages
if ($role === 'admin') {
  $usersSql = "SELECT id, fullname FROM users WHERE role = 'hod'";
  $usersResult = $conn->query($usersSql);
}

// Fetch admin IDs for HODs
if ($role === 'hod') {
  $adminsSql = "SELECT id FROM users WHERE role = 'admin'";
  $adminsResult = $conn->query($adminsSql);

  $adminIds = [];
  while ($row = $adminsResult->fetch_assoc()) {
    $adminIds[] = $row['id'];
  }

  $adminIdsStr = implode(',', $adminIds);

  $messagesSql = "SELECT m.*, u.fullname AS sender_name 
                   FROM messages m 
                   JOIN users u ON m.sender_id = u.id 
                   WHERE m.receiver_id = $userId 
                   AND m.sender_id IN ($adminIdsStr) 
                   ORDER BY m.created_at DESC";
} elseif ($role === 'admin') {
  $messagesSql = "SELECT m.*, u.fullname AS sender_name 
                   FROM messages m 
                   JOIN users u ON m.sender_id = u.id 
                   WHERE m.receiver_id = $userId 
                   ORDER BY m.created_at DESC";
}

$messagesResult = $conn->query($messagesSql);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="message.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System - Messages</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <?php include "../sidebar/sidebar.php"; ?>
  <section class="home-section">
    <?php include "../navbar/navbar.php"; ?>
    <div class="home-content">
      <div class="main-content">
        <h2>
          <center>Messages</center>
        </h2>
        <?php if (isset($message)): ?>
          <div class="alert alert-info">
            <center><?php echo $message;
            unset($message); ?></center>
          </div>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
          <form method="POST" id="sendMessageForm">
            <div class="form-group">
              <label for="receiver_id">Send to:</label>
              <select class="form-control" id="receiver_id" name="receiver_id" required>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                  <option value="<?php echo $user['id']; ?>"><?php echo $user['fullname']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="message">Message:</label>
              <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
          </form>
        <?php endif; ?>

        <h3>Message History</h3>
        <div class="messages">
          <?php while ($msg = $messagesResult->fetch_assoc()): ?>
            <div class="message">
              <strong><?php echo $msg['sender_name']; ?>:</strong>
              <p><?php echo $msg['message']; ?></p>
              <small><?php echo $msg['created_at']; ?></small>
              <?php if ($role === 'hod' && in_array($msg['sender_id'], $adminIds)): ?>
                <button class="btn btn-secondary btn-sm replyBtn"
                  data-sender-id="<?php echo $msg['sender_id']; ?>">Reply</button>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>

        <?php if ($role === 'hod'): ?>
          <form method="POST" id="replyForm" style="display: none;">
            <input type="hidden" id="reply_receiver_id" name="receiver_id">
            <div class="form-group">
              <label for="reply_message">Reply:</label>
              <textarea class="form-control" id="reply_message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <footer>
      <p>&copy; Watchman System <br> Developed by Mohit Patel and Raman Goyal</p>
    </footer>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var replyBtns = document.querySelectorAll('.replyBtn');
      var replyForm = document.getElementById('replyForm');
      var replyReceiverIdInput = document.getElementById('reply_receiver_id');

      replyBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var senderId = this.getAttribute('data-sender-id');
          replyReceiverIdInput.value = senderId;
          replyForm.style.display = 'block';
        });
      });
    });
  </script>
  <script src="../scripts.js"></script>
</body>

</html>