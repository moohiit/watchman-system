<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the email is provided
  if (isset($_POST["email"])) {
    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Send OTP to the user's email
    $email = $_POST["email"];
    $subject = "Password Reset OTP";
    $message = "Your OTP for password reset is: $otp";

    // Set up headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
    $headers .= "From: admin@ourwebprojects.site" . "\r\n";

    // Send email using mail() function
    if (mail($email, $subject, $message, $headers)) {
      // Store OTP in session
      $_SESSION["otp"] = $otp;
      $_SESSION["email"] = $email;

      // Redirect to verify OTP page
      header("Location: verify_otp.php");
      exit;
    } else {
      echo "Failed to send OTP. Please try again later.";
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Forget Password</title>
  <link rel="stylesheet" href="forgotPassword.css">
</head>

<body>
  <form method="post" class="card">
    <h2>Forgot Password</h2>
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send OTP</button>
  </form>
  <footer>
    <p>&copy; Gate Entry System <br> Developed by Mohit Patel and Raman Goyal</p>
    <p>Contact us
    <ul>
      <li><a href="https://github.com/moohiit"><i class="fab fa-github"></i> GitHub</a></li>
      <li><a href="https://www.linkedin.com/in/mohit-patel-51338a245"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
      <li><a href="mailto:pmohit645@gmail.com"><i class="far fa-envelope"></i> Email</a></li>
    </ul>
    </p>
  </footer>
</body>

</html>
