<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in first before continuing';
    header('location:login.php?error=user_access_deny');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT username, home_address, blood_type, image_path FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $user_details = $result->fetch_assoc();
} else {
    $_SESSION['error_message'] = 'Error fetching user details from the database';
    header('location:login.php?error=database_error');
    exit();
}

// Handle profile update
if (isset($_POST['submit'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_home_address = mysqli_real_escape_string($conn, $_POST['home_address']);
    $new_blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);

    $update_query = "UPDATE users SET username = ?, home_address = ?, blood_type = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssssi', $new_username, $new_home_address, $new_blood_type, $new_image_path, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'User details updated successfully';
    } else {
        $_SESSION['error_message'] = 'Error updating user details';
    }

    header('location:user-profile.php');
    exit();
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if ($current_password !== $stored_password) {
        $_SESSION['error_message'] = "Current password is incorrect.";
        header('Location: user-profile.php');
        exit();
    }

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = "New passwords do not match.";
        header('Location: user-profile.php');
        exit();
    }

    // Update the password in the database without hashing
    $update_query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $new_password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Password changed successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to change password. Please try again.";
    }

    header('Location: user-profile.php');
    exit();
}