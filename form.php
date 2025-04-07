<!-- filepath: c:\xampp\htdocs\charity\form.php -->
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $organization = $_POST['organization'];
  $pickup_point = $_POST['pickup_point'];
  $contact_email = $_POST['contact_email'];
  $foodItems = json_decode($_POST['foodItems'], true);

  if (empty($organization) || empty($pickup_point) || empty($contact_email) || empty($foodItems)) {
    die("All fields are required, and at least one food item must be added.");
  }

  // Prepare the SQL statement
  $stmt = $conn->prepare("INSERT INTO donation_details (organization_name, pickup_point, contact_email, food_name, food_type, food_timing, quantity, food_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

  foreach ($foodItems as $food) {
    $food_name = $food['name'];
    $food_type = $food['type'];
    $food_timing = $food['timing'];
    $quantity = $food['quantity'];
    $food_picture = ""; // Initialize the image path

    // Handle file upload
    if (isset($_FILES["food_picture"]["name"]) && !empty($_FILES["food_picture"]["name"])) {
      $target_dir = "uploads/";
      $file_extension = pathinfo($_FILES["food_picture"]["name"], PATHINFO_EXTENSION);
      $unique_file_name = uniqid("food_", true) . "." . $file_extension;
      $food_picture = $target_dir . $unique_file_name;

      // Move the uploaded file to the target directory
      if (!move_uploaded_file($_FILES["food_picture"]["tmp_name"], $food_picture)) {
        die("Error uploading file: " . $_FILES["food_picture"]["error"]);
      }
    }

    // Bind parameters and execute the query
    $stmt->bind_param("ssssssis", $organization, $pickup_point, $contact_email, $food_name, $food_type, $food_timing, $quantity, $food_picture);
    $stmt->execute();
  }

  echo "<script>alert('Donation successfully saved!');</script>";

  $stmt->close();
  $conn->close();
}
?>
<!-- filepath: c:\xampp\htdocs\charity\form.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Form</title>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/donation_form.css">
</head>

<body>
  <div class="container">
    <h2>Donation Form</h2>
    <form id="donation-form" action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="foodItems" name="foodItems">
      
      <label for="organization">Organization Name</label>
      <input type="text" id="organization" name="organization" placeholder="Enter organization name" required>

      <label for="pickup-point">Pickup Point Address</label>
      <div style="position: relative;">
        <input type="text" id="pickup-point" name="pickup_point" placeholder="Enter pickup point address" required>
        <button type="button" id="choose-from-map" style="margin-top: 10px;">Choose from Map</button>
      </div>

      <label for="contact-email">Contact Email</label>
      <input type="email" id="contact-email" name="contact_email" placeholder="Enter contact email" required>

      <div class="food-details-container" id="food-details-container">
        <h3>Food Details</h3>
        <label for="food-name">Food Name</label>
        <input type="text" id="food-name" placeholder="Enter food name">

        <label for="food-type">Cooked or Packaged</label>
        <select id="food-type">
          <option value="">Select</option>
          <option value="cooked">Cooked</option>
          <option value="packaged">Packaged</option>
        </select>

        <label for="food-timing">Cooked Time (if cooked) / Expiry Date (if packaged)</label>
        <input type="datetime-local" id="food-timing">

        <label for="food-quantity">Quantity (Approximate for how many people)</label>
        <input type="number" id="food-quantity" placeholder="Enter quantity">

        <label for="food-picture">Picture of Food</label>
        <input type="file" id="food-picture" accept="image/*">

        <button type="button" id="add-food">Add Food Item</button>
      </div>

      <div class="food-list" id="food-list">
        <h4>Added Food Items:</h4>
        <table id="food-table">
          <thead>
            <tr>
              <th>Food Name</th>
              <th>Type</th>
              <th>Timing</th>
              <th>Quantity</th>
              <th>Picture</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Food items will be dynamically added here -->
          </tbody>
        </table>
      </div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <!-- Include donation_form.js with defer -->
  <script src="donation_form.js" defer></script>
  <script src="js/modernizr-2.6.2.min.js" defer></script>
</body>

</html>a