<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "charity_db";
// Create connection    



$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Handle Login & Signup Requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "signup") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Signup successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Signup failed! Try again."]);
        }
        $stmt->close();
        exit();
    }

    if ($action == "login") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                echo json_encode(["status" => "success", "message" => "Login successful!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid password!"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No user found with this email!"]);
        }
        $stmt->close();
        exit();
    }

    if ($action == "logout") {
        session_destroy();
        echo json_encode(["status" => "success", "message" => "Logged out successfully!"]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .container { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #28a745; color: #fff; border: none; cursor: pointer; }
        .alert { display: none; padding: 10px; margin-top: 10px; border-radius: 5px; }
        .success { background-color: #4caf50; color: white; }
        .error { background-color: #f44336; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <input type="email" id="loginEmail" placeholder="Email">
    <input type="password" id="loginPassword" placeholder="Password">
    <button onclick="handleLogin()" <a href="contact.html"></a> Login</button>
    <div id="loginAlert" class="alert"></div>
    <p>Don't have an account? <a href="#" onclick="toggleForms()">Sign up</a></p>
</div>

<div class="container" style="display:none;" id="signupContainer">
    <h2>Signup</h2>
    <input type="text" id="signupUsername" placeholder="Username">
    <input type="email" id="signupEmail" placeholder="Email">
    <input type="password" id="signupPassword" placeholder="Password">
    <button onclick="handleSignup()">Sign up</button>
    <div id="signupAlert" class="alert"></div>
    <p>Already have an account? <a href="#" onclick="toggleForms()">Login</a></p>
</div>

<script>
    function toggleForms() {
        document.querySelector(".container").style.display = 
            document.querySelector(".container").style.display === "none" ? "block" : "none";
        document.getElementById("signupContainer").style.display = 
            document.getElementById("signupContainer").style.display === "none" ? "block" : "none";
    }

    function handleSignup() {
        const username = document.getElementById("signupUsername").value;
        const email = document.getElementById("signupEmail").value;
        const password = document.getElementById("signupPassword").value;

        fetch("", {
            method: "POST",
            body: new URLSearchParams({ action: "signup", username, email, password }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
        .then(response => response.json())
        .then(data => {
            showAlert("signupAlert", data.message, data.status === "success");
            if (data.status === "success") setTimeout(() => location.reload(), 2000);
        });
    }

    function handleLogin() {
        const email = document.getElementById("loginEmail").value;
        const password = document.getElementById("loginPassword").value;

        fetch("", {
            method: "POST",
            body: new URLSearchParams({ action: "login", email, password }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
        .then(response => response.json())
        .then(data => {
            showAlert("loginAlert", data.message, data.status === "success");
            if (data.status === "success") setTimeout(() => window.location.href = "dashboard.php", 2000);
        });
    }

    function showAlert(id, message, success) {
        const alertBox = document.getElementById(id);
        alertBox.innerText = message;
        alertBox.className = "alert " + (success ? "success" : "error");
        alertBox.style.display = "block";
        setTimeout(() => alertBox.style.display = "none", 3000);
    }
</script>

</body>
</html>
