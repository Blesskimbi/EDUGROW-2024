<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "edugrow";

// Creating connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection for errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize input data
  //check connection if any errors.here an testing conditions for all possible errors
//in this case were checking if the $conn object has a connect_error property.if it does,were displaying an error message and terminating the script
//die() function is used to terminate the script execution and display an error message
//the (.) is a concatenation operator which combines the erro message with the error message returned by thr database connection object($conn)
  $fname = $conn->real_escape_string(trim($_POST['fname']));
  $email = $conn->real_escape_string(trim($_POST['email']));
  $phone = $conn->real_escape_string(trim($_POST['phone'])); // Corrected this line
  $summary = $conn->real_escape_string(trim($_POST['summary']));
}

// Validate required fields

if (empty($fname) || empty($email) || empty($phone) || empty($summary)) {
  echo "All fields are required.";
  exit;
}

// Validate email
//the function filter_var() is used to filter and validate variables
//filter_var(variable, filter(type of filter to apply eg FILTER_VALIDATE_EMAIL),options)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "Invalid email format.";
  exit;
}

// Prepare the SQL query
$sql = "INSERT INTO tutor (fname, email, phone, summary) VALUES (?, ?, ?, ?)"; // Corrected this line

$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind parameters
        //"ssssssssss" indicates that 4 parameters are being bound
    $stmt->bind_param("ssss", $fname, $email, $phone, $summary);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>