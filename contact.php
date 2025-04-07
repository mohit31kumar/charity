<!-- filepath: c:\xampp\htdocs\charity\contact.php -->
<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Database connection details
	$servername = "localhost";
	$username = "root"; // Update if necessary
	$password = "";
	$dbname = "charity_db";

	// Create a connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check the connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get form data
	$name = $conn->real_escape_string($_POST['name']);
	$email = $conn->real_escape_string($_POST['email']);
	$message = $conn->real_escape_string($_POST['message']);

	// Validate form data
	if (empty($name) || empty($email) || empty($message)) {
		echo "<script>alert('All fields are required.');</script>";
	} else {
		// Insert data into the feedback table
		$sql = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";

		if ($conn->query($sql) === TRUE) {
			echo "<script>alert('Thank you for your feedback!'); window.location.href = 'contact.php';</script>";
		} else {
			echo "<script>alert('Error: " . $conn->error . "');</script>";
		}
	}

	// Close the connection
	$conn->close();
}
?>
<!DOCTYPE html>
<html class="no-js">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Feeders Connect &mdash; Contact Us</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Superfish -->
	<link rel="stylesheet" href="css/superfish.css">
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
</head>

<body>
	<header id="fh5co-header-section" class="sticky-banner">
		<div class="container">
			<div class="nav-header">
				<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
				<!-- <img src="WhatsApp Image 2025-04-02 at 10.15.20_b03e4785.jpg" alt="" style="width: 20px; height: 20px;"> -->
				<h1 id="fh5co-logo"><a href="index.html">Feeders Connect</a></h1>
				<!-- START #fh5co-menu-wrap -->
				<nav id="fh5co-menu-wrap" role="navigation">
					<ul class="sf-menu" id="fh5co-primary-menu">
						<li class="active">
							<a href="index.html" style="font-size:21px">Home</a>
						</li>
						<li><a href="volunteer.html" style="font-size:21px">Volunteer</a></li>
						<li><a href="about.html" style="font-size:21px">About</a></li>
						<li><a href="contact.php" style="font-size:21px">Contact</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</header>


	<div class="fh5co-hero">
		<div class="fh5co-overlay"></div>
		<div class="fh5co-cover text-center" data-stellar-background-ratio="0.5" style="background-image: url(images/kids-2408614_1280.jpg);">
			<div class="desc animate-box">
				<h2><strong>Contact</strong> Us</h2>
			</div>
		</div>
	</div>

	<div id="fh5co-contact" class="animate-box">
		<div class="container">
			<form action="contact.php" method="POST">
				<div class="row">
					<!-- Our Address Section -->
					<div class="col-md-6">
						<h3 class="section-title">Contact Us</h3>
						<div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
							<p>If you have any questions or need assistance, feel free to reach out to us:</p>
							<ul class="contact-info">
								<li><i class="icon-phone2"></i> Phone: <a href="https://wa.me/919253206442" target="_blank">+91 9253206442</a></li>
								<li><i class="icon-mail"></i> Email: <a href="mailto:mohitkkumar405@gmail.com">mohitkkumar405@gmail.com</a></li>
								<li><i class="icon-globe2"></i> Website: <a href="/index.html" target="_blank">www.feederconnect.com</a></li>
								<li><i class="icon-map"></i> Address: Jaipur, Rajasthan, India</li>
							</ul>
						</div>
					</div>

					<!-- Feedback Form Section -->
					<div class="col-md-6">
						<h3 class="section-title">Feedback</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" name="name" class="form-control" placeholder="Name" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="email" name="email" class="form-control" placeholder="Email" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<textarea name="message" class="form-control" cols="30" rows="7" placeholder="Message" required></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" value="Submit Feedback" class="btn btn-primary">
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>

			<!-- Feedback Display Section -->
			<div class="row" style="margin-top: 30px;">
				<div class="col-md-12">
					<h3 class="section-title">What People Say</h3>
					<div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
						<?php
						// Database connection
						$conn = new mysqli("localhost", "root", "", "charity_db");

						// Check connection
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						// Fetch feedback from the database
						$sql = "SELECT name, message, created_at FROM feedback ORDER BY created_at DESC";
						$result = $conn->query($sql);

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								echo "<div style='margin-bottom: 15px;'>";
								echo "<strong>" . htmlspecialchars($row['name']) . "</strong> <small>(" . date("F j, Y, g:i a", strtotime($row['created_at'])) . ")</small>";
								echo "<p>" . htmlspecialchars($row['message']) . "</p>";
								echo "<hr>";
								echo "</div>";
							}
						} else {
							echo "<p>No feedback available yet.</p>";
						}

						$conn->close();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/sticky.js"></script>
	<!-- Stellar -->
	<script src="js/jquery.stellar.min.js"></script>
	<!-- Superfish -->
	<script src="js/hoverIntent.js"></script>
	<script src="js/superfish.js"></script>
	<!-- Google Map -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCefOgb1ZWqYtj7raVSmN4PL2WkTrc-KyA&sensor=false"></script>
	<script src="js/google_map.js"></script>
	<!-- Main JS -->
	<script src="js/main.js"></script>
</body>

</html>