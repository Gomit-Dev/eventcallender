<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header('Location: login.html');
    exit;
}

$username = $_SESSION['username'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com'; 
$account_type = 'organizer'; 

// Load participants from JSON file
$participantsFile = 'participants.json';

if (!file_exists($participantsFile)) {
    $participants = []; 
} else {
    $jsonData = file_get_contents($participantsFile);
    $participants = json_decode($jsonData, true);

    if (!is_array($participants)) {
        $participants = []; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Participants | Organizer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- container -->
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

<!-- Navbar -->
<nav class="bg-gray-800 p-4 flex justify-between items-center shadow-md sticky top-0 z-50">
    <div class="flex items-center space-x-2">
        <img src="img/logo.png" alt="UniGather Logo" class="h-10 w-10 rounded-full" />
        <h1 class="text-3xl font-bold text-yellow-400">UniGather</h1>
    </div>
    <div class="flex items-center space-x-6">
        <div class="hidden sm:block text-sm text-right">
            <p class="font-semibold">Welcome, <span class="text-yellow-400"><?php echo htmlspecialchars($username); ?></span></p>
            <p class="text-xs text-gray-400"><?php echo ucfirst($account_type); ?> | <?php echo htmlspecialchars($email); ?></p>
        </div>
        <a href="logout.php" class="bg-red-600 px-5 py-2 rounded-full font-semibold hover:bg-red-700 transition">Logout</a>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto mt-12 p-8 flex-grow">
    <h2 class="text-4xl font-bold mb-8 text-yellow-400">Manage Participants</h2>

    <?php if (empty($participants)): ?>
        <div class="text-center text-gray-400 text-lg">No participants found for your events!</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full bg-gray-800 rounded-lg overflow-hidden shadow-md table-auto">
                <thead class="bg-gray-700 text-yellow-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $participant): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-600 transition">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($participant['name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($participant['email']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php
                                        if ($participant['status'] === 'Approved') {
                                            echo 'bg-green-500 text-white';
                                        } elseif ($participant['status'] === 'Rejected') {
                                            echo 'bg-red-500 text-white';
                                        } else {
                                            echo 'bg-yellow-500 text-black';
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($participant['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center space-x-4">
                                <?php if ($participant['status'] === 'Pending'): ?>
                                    <a href="updateParticipant.php?action=approve&id=<?php echo $participant['id']; ?>" 
                                       class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 transition">
                                        Approve
                                    </a>
                                    <a href="updateParticipant.php?action=reject&id=<?php echo $participant['id']; ?>" 
                                       class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition">
                                        Reject
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<!-- Footer -->
<footer class="bg-gray-800 p-4 text-center text-gray-400 mt-auto">
    &copy; <?php echo date("Y"); ?> UniGather. All rights reserved.
</footer>

</body>
</html>
