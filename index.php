<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Watchman System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    header {
      background: #0C356A;
      color: #fff;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }

    header .logo {
      font-size: 30px;
      border-radius: 5px;
    }

    header nav{
      margin-right: 15px;
    }
    header nav a {
      color: #fff;
      margin: 0 15px;
      text-decoration: none;
    }

    .hero {
      background: #f4f4f4;
      text-align: center;
      padding: 100px 20px;
      margin-top: 80px;
      /* Add margin to offset the header height */
    }

    .hero h1{
      font-size: 50px;
    }
    .headings{
      font-size: 30px;
    }

    .section {
      padding: 100px 20px 50px;
      text-align: center;
    }

    .features {
      background: #e0f7fa;
    }

    .how-it-works {
      background: #f4f4f4;
    }

    .benefits {
      background: #e0f7fa;
    }

    .testimonials {
      background: #f4f4f4;
    }

    .contact {
      background: #e0f7fa;
    }

    footer {
      background: #333;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }

    footer a {
      color: #fff;
      margin: 0 10px;
      text-decoration: none;
    }

    .feature,
    .benefit {
      display: inline-block;
      width: 30%;
      padding: 20px;
    }

    .feature i,
    .benefit i {
      font-size: 50px;
      color: #333;
    }

    #get-started{
      background: #0C356A;
      color: #fff;
      padding: 10px;
      border-radius: 5px;

    }
  </style>
</head>

<body>

  <header>
    <img class="logo" src="logo.png" alt="College Logo" style="height: 50px;">
    <nav>
      <a href="#hero">Home</a>
      <a href="./loginPage.php">Login</a>
      <a href="#about">About</a>
      <a href="#features">Features</a>
      <a href="#contact">Contact Us</a>
    </nav>
  </header>

  <section class="hero" id="hero">
    <h1>Welcome to the Watchman System</h1>
    <p>Efficient and Secure Entry and Exit Management</p>
    <button id="get-started" onclick="location.href='./loginPage.php'">Get Started</button>
  </section>

  <section class="section features" id="features">
    <h1 class="headings">Features</h1>
    <div class="feature">
      <i class="fas fa-id-card"></i>
      <h3>Easy Student Check-In/Out</h3>
      <p>Quickly scan student IDs for smooth entry and exit.</p>
    </div>
    <div class="feature">
      <i class="fas fa-video"></i>
      <h3>Real-Time Monitoring</h3>
      <p>Track student movements in real-time for enhanced security.</p>
    </div>
    <div class="feature">
      <i class="fas fa-file-alt"></i>
      <h3>Automated Reports</h3>
      <p>Generate detailed reports on student entries and exits.</p>
    </div>
  </section>

  <section class="section how-it-works" id="about">
    <h1 class="headings">How It Works</h1>
    <p>Step 1: Student shows their ID at the gate.</p>
    <p>Step 2: Watchman scans the ID.</p>
    <p>Step 3: System logs the entry/exit and updates the database.</p>
  </section>

  <section class="section benefits">
    <h1 class="headings">Benefits</h1>
    <div class="benefit">
      <i class="fas fa-shield-alt"></i>
      <h3>Security</h3>
      <p>Ensures campus safety.</p>
    </div>
    <div class="benefit">
      <i class="fas fa-clock"></i>
      <h3>Efficiency</h3>
      <p>Speeds up the entry/exit process.</p>
    </div>
    <div class="benefit">
      <i class="fas fa-tasks"></i>
      <h3>Accountability</h3>
      <p>Keeps accurate records of student movements.</p>
    </div>
  </section>

  <section class="section testimonials">
    <h1 class="headings">Testimonials</h1>
    <p>"This system has made gate management so much easier!" - College Administrator</p>
    <p>"I feel safer knowing our movements are monitored." - Student</p>
  </section>

  <section class="section contact" id="contact">
    <h1 class="headings">Contact Us</h1>
    <form>
      <input type="text" name="name" placeholder="Name"><br><br>
      <input type="email" name="email" placeholder="Email"><br><br>
      <textarea name="message" placeholder="Message"></textarea><br><br>
      <button type="submit">Send</button>
    </form>
    <p>Email: support@college.edu | Phone: (123) 456-7890</p>
  </section>

  <footer>
    <p>
      <a href="#">Privacy Policy</a> |
      <a href="#">Terms of Service</a>
    </p>
    <p>© 2024 [Your College Name]. All rights reserved.</p>
    <div>
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

</body>

</html>