<?php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$db = 'ex6';
$user = 'root'; // Update this if your username is different
$pass = 'new_password'; // Use your updated password here

try {
    // Establishing a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, output error and exit
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$errors = [];
$response = [];

// Processing form data when POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Getting input values
    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $age = intval($_POST['age']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Validating input values
    if (empty($firstName)) {
        $errors['firstName'] = 'First name is required.';
    }

    if (empty($lastName)) {
        $errors['lastName'] = 'Last name is required.';
    }

    if ($age < 1 || $age > 120) {
        $errors['age'] = 'Please provide a valid age between 1 and 120.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please provide a valid email address.';
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = 'Please provide a valid 10-digit phone number.';
    }

    if (empty($errors)) {
        // Insert data into the database if there are no errors
        try {
            $stmt = $conn->prepare("INSERT INTO form_submissions (first_name, middle_name, last_name, age, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $middleName, $lastName, $age, $email, $phone]);

            // Successful response
            $response['success'] = true;
            $response['submittedData'] = [
                'firstName' => $firstName,
                'middleName' => $middleName,
                'lastName' => $lastName,
                'age' => $age,
                'email' => $email,
                'phone' => $phone
            ];
        } catch (PDOException $e) {
            // If insertion fails, output error message
            $response['success'] = false;
            $response['error'] = 'Database insertion failed: ' . $e->getMessage();
        }
    } else {
        // If validation errors exist
        $response['success'] = false;
        $response['errors'] = $errors;
    }

    // Returning JSON response
    echo json_encode($response);
}
