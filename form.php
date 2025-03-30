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
            // Generate a unique name for the image
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

    // Redirect to the home page
    header("Location: index.html");
    exit(); // Ensure no further code is executed after the redirect
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Form</title>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
  <style>
 /* General Styling */
 body {
      background-color: #f4f4f9;
      font-family: 'Rubik', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      max-width: 800px;
      width: 100%;
    }

    h2 {
      font-size: 28px;
      color: #ff5722;
      margin-bottom: 20px;
      text-align: center;
    }

    label {
      font-size: 16px;
      font-weight: bold;
      color: #555555;
      display: block;
      margin-bottom: 5px;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #dddddd;
      border-radius: 5px;
      margin-bottom: 15px;
      outline: none;
      transition: border-color 0.3s;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: #ff5722;
    }

    button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #ff5722;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #e64a19;
    }

    .food-details-container {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    .food-list {
      margin-top: 20px;
      padding: 15px;
      border: 1px solid #dddddd;
      border-radius: 8px;
      background-color: #ffffff;
    }

    .food-list h4 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 1px solid #dddddd;
      padding: 10px;
      text-align: center;
    }

    table th {
      background-color: #ff5722;
      color: white;
    }

    .remove-btn {
      background-color: #dc3545;
      color: white;
      padding: 5px 10px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .remove-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Donation Form</h2>
    <form id="donation-form" action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="foodItems" name="foodItems">
      <label for="organization">Organization Name</label>
      <input type="text" id="organization" name="organization" placeholder="Enter organization name" required>

      <label for="pickup-point">Pickup Point Address</label>
      <textarea id="pickup-point" name="pickup_point" placeholder="Enter pickup point address" rows="3" required></textarea>

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
        <input type="file" id="food-picture" name="food_picture" accept="image/*">

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

  <script src="donation_form.js"></script>
</body>

</html>