<?php
    // Start the session to access session variables
    session_start();

    // Include the database connection file
    require 'db.php';
    require 'footer.php';
    require 'header.php';

    // Check if the 'user' cookie exists
if (!isset($_COOKIE['user'])) {
    // If the cookie is missing or expired, redirect to the authentication page
    header("Location: ../auth.html");
    exit();
}
// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php"); // Redirect to the authentication page if no user ID in session
    exit();
}

// Prepare SQL query to fetch user details based on the session's user ID
$sql = "SELECT * FROM users WHERE id = :user_id";
$statement = $db->prepare($sql);

// Execute the query with the user ID from the session
$statement->execute(['user_id' => $_SESSION['user_id']]);
$user = $statement->fetch(PDO::FETCH_ASSOC); // Fetch the user data as an associative array

// Check if user data is retrieved
if ($user) {
    // Assign user details to variables for display
    $email = $user['email'];
    $fname = $user['fname'];
    $major = $user['major'];
    $age = $user['age'];
    $phone_num = $user['phone_num'];
    $photo = $user['profile_pic'];
} else {
    // If user not found, display an error message and stop the script
    die("User not found. Please ensure you are logged in with a valid account.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../user.css">
</head>
<body>
    <h2>Edit Profile</h2>
    <div class="userinput">
        <!-- Form for editing the user profile -->
        <form action="update.php" method="post" enctype="multipart/form-data">
            <div class="profileContainer">
                <?php
                // Display the user's current profile picture or a default picture if none exists
                if (!empty($photo)) {
                    echo '<img src="' . htmlspecialchars($photo) . '" alt="Profile Picture" id="profile-image">';
                } else {
                    echo '<img src="../profile_pic.png" alt="Default Profile Picture" id="profile-image">';
                }
                ?>
            </div>

            <!-- Input for changing the profile picture -->
            <label for="profileImage" id="editPic">Edit picture</label>
            <input type="file" name="profileImage" id="profileImage" class="imageInput">

            <?php
            // Display error messages related to file uploads
            if (!empty($_SESSION['fileSize_error'])) {
                echo "<p>File size must be less than 2 MB.</p>";
                unset($_SESSION['fileSize_error']); // Clear error after displaying
            }
            if (!empty($_SESSION['fileType_error'])) {
                echo "<p>Only JPG, PNG, and GIF files are allowed.</p>";
                unset($_SESSION['fileType_error']);
            }
            if (!empty($_SESSION['upload_error'])) {
                echo "<p>Failed to upload the picture.</p>";
                unset($_SESSION['upload_error']);
            }
            ?>

            <!-- Input for editing user details -->
            <label for="fname">Name:</label>
            <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>">
            <?php
            if (!empty($_SESSION['fname_error'])) {
                echo "<p>Invalid name format. Only alphabet characters and underscores are allowed.</p>";
                unset($_SESSION['fname_error']); // Clear error after displaying
            }
            ?>

            <label for="email">Email:</label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly> <!-- Email is read-only -->

            <label for="phoneNum">Phone Number:</label>
            <input type="tel" id="phoneNum" name="phone_num" value="<?php echo htmlspecialchars($phone_num); ?>">
            <?php
            if (!empty($_SESSION['phone_num_error'])) {
                echo "<p>Invalid phone number format.</p>";
                unset($_SESSION['phone_num_error']); // Clear error after displaying
            }
            ?>

            <label for="major">Major:</label>
            <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($major); ?>">

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">
            <?php
            if (!empty($_SESSION['age_error'])) {
                echo "<p>Invalid age.</p>";
                unset($_SESSION['age_error']); // Clear error after displaying
            }
            ?>

            <!-- Submit button to save changes -->
            <input type="submit" value="Save Changes">

            <?php
            // Display a success message after updating the profile
            if (!empty($_SESSION['update_status'])) {
                echo "<p>" . $_SESSION['update_status'] . "</p>";
                unset($_SESSION['update_status']); // Clear status after displaying
            }
            ?>
        </form>
    </div>
    <script>
        document.getElementById('profileImage').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader(); // FileReader to read the file
                reader.onload = function(e) {
                    // Update the src of the profile-image with the file content
                    document.getElementById('profile-image').src = e.target.result;
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        });
    </script>
</body>
</html>
