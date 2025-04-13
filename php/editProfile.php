<?php
    session_start();

     
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
        header('Location: login.html');
        exit;
    }
    $username = $_SESSION['username'];
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';  
    $account_type = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : 'user';  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Profile | Organizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-gray-900 to-gray-800 text-white min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 flex justify-between items-center shadow-md sticky top-0 z-50">
        <div class="flex items-center space-x-2">
            <img src="img/logo.png" alt="UniGather Logo" class="h-10 w-10 rounded-full" />
            <h1 class="text-3xl font-bold text-yellow-400">UniGather</h1>
        </div>
        <div class="flex items-center space-x-6">
            <div class="hidden sm:block text-sm">
                <p class="font-semibold text-white">Welcome, <span class="text-yellow-400"><?php echo $username; ?></span></p>
                <p class="text-xs text-gray-400"><?php echo ucfirst($account_type); ?> | <?php echo $email; ?></p>
            </div>
            <a href="logout.php" class="bg-red-600 px-5 py-2 rounded-full font-semibold hover:bg-red-700 transition">Logout</a>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="mb-4 p-4 bg-green-600 text-white rounded"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="mb-4 p-4 bg-red-600 text-white rounded"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <main class="max-w-xl mx-auto mt-12 bg-gray-800 rounded-lg shadow-xl p-8">
        <h2 class="text-3xl font-bold mb-6 text-yellow-400">Edit Your Profile</h2>

        <form action="updateProfile.php" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" required maxlength="11" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Change Password</label>
                <input type="password" name="password" placeholder="Enter new password" class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" />
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-black font-bold py-3 rounded hover:bg-yellow-500 transition">Save Changes</button>
        </form>
    </main>

</body>
</html>
