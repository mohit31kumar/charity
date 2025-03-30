<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "charity_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donations
$sql = "SELECT d.id, d.organization_name, d.pickup_point, f.food_name, f.food_type, f.food_timing, f.quantity, f.food_picture 
        FROM donations d 
        LEFT JOIN food_items f ON d.id = f.donation_id";
$result = $conn->query($sql);
$donations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FeedConnect-Request Demo</title>
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
<style>
/* General Styles */
body {
    background-color: #ececec;
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: "DM Sans", sans-serif;
}

/* Main container */
.container {
    display: flex;
    width: 85%;
    max-width: 1200px;
    height: 75vh;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    border-radius: 20px;
    background-color: white;
    margin: 40px 0;
}

/* Left side - Image section */
.left-side {
    width: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-right: 5px solid #ff5722;
}

.left-side img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease-in-out;
    border-radius: 20px 0 0 20px;
}

.left-side img:hover {
    transform: scale(1.05);
}

/* Right side - Content */
.right-side {
    width: 50%;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background-color: #222;
    color: white;
    text-align: center;
}

h2 {
    font-size: 48px;
    margin-bottom: 10px;
    color: #ff5722;
}

p {
    font-size: 24px;
    margin: 10px 0;
    font-family: "Rubik", serif;
}

button {
    font-size: 18px;
    padding: 15px 30px;
    border: 2px solid #ff5722;
    background-color: #ff5722;
    color: white;
    cursor: pointer;
    border-radius: 10px;
    font-family: "Noto Sans JP", sans-serif;
    letter-spacing: 1px;
    transition: all 0.3s ease-in-out;
    align-self: center;
}

button:hover {
    background-color: transparent;
    color: #ff5722;
}
</style>
</head>
<body>
<div class="container">
    <!-- Left side with image -->
    <div class="left-side">
        <img src="https://cdn.pixabay.com/photo/2022/08/21/03/48/smile-7400381_1280.jpg" alt="Smiling Child">
    </div>
    <!-- Right side with content -->
    <div class="right-side">
        <h2>Donate Food</h2>
        <p>Ease your food <strong>donation process</strong></p>
        <a href="form.php"><button>Donate Now</button></a>
    </div>
</div>
</body>
</html>