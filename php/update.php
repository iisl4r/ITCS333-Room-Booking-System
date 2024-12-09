<?php
// Start the session to manage user data and errors
session_start();
ob_start(); // Enable output buffering to handle redirects smoothly

// Include database connection
require "db.php";

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if the request method is POST to process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate and update the user's first name
    if (preg_match('/^[a-zA-Z]+$/', $_POST['fname'])) { // Check for valid name format (alphabetical only)
        $fname = trim($_POST['fname']); // Remove unnecessary whitespace
        $sql = 'UPDATE users SET fname = :fname WHERE id = :user_id';
        $statement = $db->prepare($sql);
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    } else {
        $_SESSION['fname_error'] = true; // Set error if validation fails
    }

    // Validate and update the user's phone number
    if (preg_match('/^\+973?\d{8}$/', $_POST['phone_num'])) { // Check for valid phone number format
        $phone_num = trim($_POST['phone_num']); // Remove unnecessary whitespace
        $sql = 'UPDATE users SET phone_num = :phone_num WHERE id = :user_id';
        $statement = $db->prepare($sql);
        $statement->bindParam(':phone_num', $phone_num, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    } else {
        $_SESSION['phone_num_error'] = true; // Set error if validation fails
    }

    // Update the user's major 
    $major = trim($_POST['major']);
    $statement = $db->prepare('UPDATE users SET major = :major WHERE id = :user_id');
    $statement->bindParam(':major', $major, PDO::PARAM_STR);
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();

    // Validate and update the user's age
    if (preg_match('/^[1-9]*2/', $_POST['age'])) { // Check for valid age format (example validation)
        $age = intval($_POST['age']); // Convert to integer
        $sql = 'UPDATE users SET age = :age WHERE id = :user_id';
        $statement = $db->prepare($sql);
        $statement->bindParam(':age', $age, PDO::PARAM_INT);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    } else {
        $_SESSION['age_error'] = true; // Set error if validation fails
    }

    // Handle profile picture upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        // Define allowed file types and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB limit
        $fileType = $_FILES['profileImage']['type'];
        $fileSize = $_FILES['profileImage']['size'];

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['fileType_error'] = true;
        }

        // Validate file size
        if ($fileSize > $maxSize) {
            $_SESSION['fileSize_error'] = true;
        }

        // Define upload directory for profile pictures
        $uploadDir = 'uploads/';
        $profilePicsDir = $uploadDir . 'profile_pics/';

        // Create the directory if it doesn't exist
        if (!is_dir($profilePicsDir)) {
            if (!mkdir($profilePicsDir, 0755, true)) {
                $_SESSION['upload_error'] = true; // Set error if directory creation fails
            }
        }

        // Generate a unique file name for the uploaded image
        $fileExtension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('profile_' . time() . '_', true) . '.' . $fileExtension;
        $filePath = $profilePicsDir . $fileName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $filePath)) {
            $_SESSION['upload_error'] = true; // Set error if file upload fails
        }

        // Update the profile picture in the database
        $sql = 'UPDATE users SET profile_pic = :profile_pic WHERE id = :user_id';
        $statement = $db->prepare($sql);
        $statement->bindParam(':profile_pic', $filePath, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
} else {
    // Display an error message if the request method is not POST
    echo "Invalid request method.";
}

// Check if there are no errors and set a success message
if (
    empty($_SESSION['fname_error']) &&
    empty($_SESSION['phone_num_error']) &&
    empty($_SESSION['age_error']) &&
    empty($_SESSION['upload_error']) &&
    empty($_SESSION['fileSize_error']) &&
    empty($_SESSION['fileType_error'])
) {
    $_SESSION['update_status'] = "Profile updated successfully.";
}

// Redirect back to the edit profile page
header("Location: ../php/edit_profile.php");
ob_end_flush(); // Flush the output buffer and end buffering
