<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection details
include '../database.php';

// Set your email details
$sender_email = "admin@ourwebprojects.site";

// Check if the form is submitted
if (isset($_POST['send_mail'])) {
    // Fetch data from the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Fetch department-wise email addresses
    $deptQuery = $pdo->query("SELECT dprt FROM inqury_data WHERE date=CURRENT_DATE");
    $departments = $deptQuery->fetchAll(PDO::FETCH_ASSOC);

    foreach ($departments as $department) {
        // Fetch data for each department
        $dept = $department['dprt'];
        $sql = $pdo->prepare("SELECT username FROM users WHERE department = :dept AND role='hod';");
        $sql->bindParam(':dept', $dept);
        $sql->execute();
        $row = $sql->fetchAll(PDO::FETCH_ASSOC);
        $deptEmail = $row[0]["username"];

        $stmt = $pdo->prepare("SELECT * FROM inqury_data WHERE dprt = :dept AND date = CURRENT_DATE");
        $stmt->bindParam(':dept', $dept);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Build HTML content dynamically for each department
        $html_content = '<html><head><title>Student Report</title></head><body>';
        $html_content .= '<h2>Today\'s Students Report for ' . $dept . '</h2>';
        $html_content .= '<table border="1">';
        $html_content .= '<tr><th>Name</th><th>Department</th><th>Contact</th><th>Reason</th><th>Status</th><th>Authorized By</th><th>Time</th><th>Date</th><th>Photo</th></tr>';

        foreach ($data as $row) {
            $html_content .= '<tr>';
            $html_content .= '<td>' . $row['name'] . '</td>';
            $html_content .= '<td>' . $row['dprt'] . '</td>';
            $html_content .= '<td>' . $row['contact'] . '</td>';
            $html_content .= '<td>' . $row['reason'] . '</td>';
            $html_content .= '<td>' . $row['status'] . '</td>';
            $html_content .= '<td>' . $row['authorisedBy'] . '</td>';
            $html_content .= '<td>' . $row['currentime'] . '</td>';
            $html_content .= '<td>' . $row['date'] . '</td>';
            $html_content .= '<td><img width="100" height="100" src="' . $row["photo_url"] . '" alt="Photo"></td>';
            $html_content .= '</tr>';
        }

        $html_content .= '</table></body></html>';

        // Set up headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: admin@ourwebprojects.site" . "\r\n";


        // Send email using mail() function
        if (mail($deptEmail, 'Student Report', $html_content, $headers)) {
            $_SESSION["mail_status"] = "Email has been sent successfully.";
        } else {
            $_SESSION["mail_error"] = "Failed to send email to " . $deptEmail . ".";
        }
    }

    header("Location: ./mail.php");
    exit();
}
?>
