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

// Fetch donation details from the `donation_details` table
$sql = "SELECT id, organization_name, pickup_point, contact_email, food_name, food_type, food_timing, quantity, food_picture, created_at FROM donation_details";
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
    <title>Donations List</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header Styling */
        h2 {
            text-align: center;
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease;
        }

        th {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        td {
            background-color: #f9f9f9;
            font-size: 16px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }
        }
    </style>
    <script>
        // Function to open the image in a popup window
        function viewImage(imageUrl) {
            if (imageUrl) {
                const popup = window.open("", "_blank", "width=600,height=400");
                popup.document.write(`
                    <html>
                        <head>
                            <title>Food Image</title>
                        </head>
                        <body style="margin:0;padding:0;display:flex;justify-content:center;align-items:center;height:100vh;">
                            <img src="${imageUrl}" style="max-width:100%;max-height:100%;border:1px solid #ddd;border-radius:8px;">
                        </body>
                    </html>
                `);
                popup.document.close();
            } else {
                alert("No image available.");
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Donations List</h2>
        <table>
            <tr>
                <th>Organization</th>
                <th>Pickup Point</th>
                <th>Contact Email</th>
                <th>Food Name</th>
                <th>Type</th>
                <th>Timing</th>
                <th>Quantity</th>
                <th>Picture</th>
                <th>Created At</th>
            </tr>
            <?php if (!empty($donations)) { ?>
                <?php foreach ($donations as $donation) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['organization_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['pickup_point']); ?></td>
                        <td><?php echo htmlspecialchars($donation['contact_email']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_type']); ?></td>
                        <td><?php echo htmlspecialchars($donation['food_timing']); ?></td>
                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                        <td>
                            <?php if (!empty($donation['food_picture'])) { ?>
                                <button onclick="viewImage('<?php echo htmlspecialchars($donation['food_picture']); ?>')">View Image</button>
                            <?php } else { ?>
                                No Image
                            <?php } ?>
                        </td>
                        <td><?php echo htmlspecialchars($donation['created_at']); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9">No donations found.</td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>



<!-- Steps to Get Your Google Maps API Key
Go to the Google Cloud Console.
Create a new project or select an existing one.
Enable the Maps JavaScript API and Places API for your project.
Generate an API key and replace YOUR_API_KEY in the script above with your actual API key.
 -->
</html>