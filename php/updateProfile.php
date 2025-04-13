<?php
session_start();

// Ensure the user is logged in and an organizer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header('Location: login.html');
    exit;
}

// Sanitize POST data
$newUsername = isset($_POST['username']) ? trim($_POST['username']) : '';
$newEmail = isset($_POST['email']) ? trim($_POST['email']) : '';
$newPassword = isset($_POST['password']) ? trim($_POST['password']) : '';

$usersFile = 'users.json';

// Validate form inputs
if (empty($newUsername) || empty($newEmail)) {
    $_SESSION['error'] = "Username and Email are required!";
    header('Location: editProfile.php');
    exit;
}

// Load existing users
if (!file_exists($usersFile)) {
    $_SESSION['error'] = "Users database not found.";
    header('Location: editProfile.php');
    exit;
}

$usersData = json_decode(file_get_contents($usersFile), true);

if (!$usersData) {
    $_SESSION['error'] = "Unable to read users database.";
    header('Location: editProfile.php');
    exit;
}

$currentUsername = $_SESSION['username'];
$userFound = false;

// Update user info
foreach ($usersData as &$user) {
    if ($user['username'] === $currentUsername) {
        $user['username'] = $newUsername;
        $user['email'] = $newEmail;

        // Only update password if a new one was provided
        if (!empty($newPassword)) {
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userFound = true;
        break;
    }
}

if (!$userFound) {
    $_SESSION['error'] = "User not found!";
    header('Location: editProfile.php');
    exit;
}

// Save the updated users.json
if (file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT))) {
    // Update session values
    $_SESSION['username'] = $newUsername;
    $_SESSION['email'] = $newEmail;

    $_SESSION['success'] = "Profile updated successfully!";
    header('Location: editProfile.php');
    exit;
} else {
    $_SESSION['error'] = "Failed to save profile changes.";
    header('Location: editProfile.php');
    exit;
}
?>
