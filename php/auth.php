<?php
session_start();

$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$action = $_POST['action'] ?? '';

function saveUsers($users, $file) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function showErrorPage($message, $redirectPage) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Error</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="flex items-center justify-center min-h-screen bg-black bg-[url(\'img/caution-bg.png\')] bg-center bg-no-repeat bg-cover relative">
        
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <div class="relative z-10 text-center text-white p-8 bg-black bg-opacity-80 rounded-xl shadow-2xl max-w-md w-[90%] animate-pulse">
            <img src="img/caution-icon.png" alt="Caution" class="mx-auto mb-4 w-24 h-24">
            <h1 class="text-2xl font-bold mb-4">Oops!</h1>
            <p class="mb-6 text-lg">' . htmlspecialchars($message) . '</p>
            <a href="' . htmlspecialchars($redirectPage) . '" class="inline-block bg-yellow-400 text-black font-semibold px-6 py-3 rounded-full hover:bg-yellow-500 transition">Try Again</a>
        </div>

    </body>
    </html>
    ';
    exit;
}

if ($action === 'register') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['account_type']; 
    $email = $_POST['email'];

    // Validate username
    if (strlen($username) > 11) {
        showErrorPage('Username must be 11 characters or less!', 'register.html');
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        showErrorPage('Username can only contain letters, numbers, and underscores.', 'register.html');
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showErrorPage('Invalid email address!', 'register.html');
    }

    // Check if username already exists
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            showErrorPage('Username already exists!', 'register.html');
        }
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $users[] = [
        'username' => $username,
        'password' => $hashedPassword,
        'role'     => $role,
        'email'    => $email
    ];

    saveUsers($users, $usersFile);
    header('Location: login.html');
    exit;

} elseif ($action === 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Role-based redirection
            if ($_SESSION['role'] === 'organizer') {
                header('Location: adminDashboard.php');
                exit;
            } elseif ($_SESSION['role'] === 'attendee') {
                header('Location: userDashboard.php');
                exit;
            } else {
                showErrorPage('Unknown role assigned!', 'login.html');
            }
        }
    }

    showErrorPage('Invalid credentials!', 'login.html');
}
?>
