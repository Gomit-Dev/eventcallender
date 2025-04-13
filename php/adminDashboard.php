<?php
    session_start();

    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
        header('Location: login.html');
        exit;
    }

    // Grab session variables safely
    $username = $_SESSION['username'];
    $email = $_SESSION['email'] ?? 'user@example.com';
    $account_type = $_SESSION['account_type'] ?? 'organizer';

    $upcomingEventsCount = 0;
    $totalParticipants = 0;
    $pendingApprovalsCount = 0;

    // Load events.json
    $eventsData = file_get_contents('events.json');
    $events = json_decode($eventsData, true);

    $today = date('Y-m-d');

    // Process events to get counts
    if (!empty($events)) {
        foreach ($events as $event) {
            $eventDate = $event['date'];
            $status = strtolower($event['status']);

            // Count Upcoming Events
            if ($status === 'upcoming' && $eventDate >= $today) {
                $upcomingEventsCount++;
            }

            // Count Pending Approvals (if you have "pending" status events)
            if ($status === 'pending') {
                $pendingApprovalsCount++;
            }

            // Sum total participants
            $totalParticipants += $event['participants'] ?? 0;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Organizer Dashboard | UniGather</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Scrollbar -->
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #facc15;  
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-gray-900 to-gray-800 min-h-screen text-white font-sans">

    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 flex justify-between items-center shadow-md sticky top-0 z-50">
        <div class="flex items-center space-x-2">
            <img src="img/logo.png" alt="UniGather Logo" class="h-10 w-10 rounded-full" />
            <h1 class="text-3xl font-bold text-yellow-400">UniGather</h1>
        </div>

        <div class="flex items-center space-x-6">
            <div class="hidden sm:block text-sm text-right">
                <p class="font-semibold">Welcome, <span class="text-yellow-400"><?php echo htmlspecialchars($username); ?></span></p>
                <p class="text-xs text-gray-400"><?php echo ucfirst(htmlspecialchars($account_type)); ?> | <?php echo htmlspecialchars($email); ?></p>
            </div>
            <a href="logout.php" class="bg-red-600 px-5 py-2 rounded-full font-semibold hover:bg-red-700 transition">Logout</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="p-8 text-center bg-gray-900 shadow-lg">
        <h2 class="text-4xl font-extrabold mb-4 text-yellow-400 animate-pulse">Organizer Dashboard</h2>
        <p class="text-lg text-gray-300">Manage your events, participants, and analytics in one place.</p>
    </header>

    <!-- Main Content -->
    <main class="p-8 space-y-8">

        <!-- Quick Stats -->
        <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Upcoming Events -->
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300">
                <h3 class="text-xl font-semibold text-yellow-400 mb-2">Upcoming Events</h3>
                <p class="text-4xl font-bold"><?php echo $upcomingEventsCount; ?></p>
                <p class="text-gray-400">Scheduled for this month</p>
            </div>

            <!-- Registered Participants -->
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300">
                <h3 class="text-xl font-semibold text-yellow-400 mb-2">Registered Participants</h3>
                <p class="text-4xl font-bold"><?php echo $totalParticipants; ?></p>
                <p class="text-gray-400">Across all events</p>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:scale-105 transition duration-300">
                <h3 class="text-xl font-semibold text-yellow-400 mb-2">Pending Approvals</h3>
                <p class="text-4xl font-bold"><?php echo $pendingApprovalsCount; ?></p>
                <p class="text-gray-400">Awaiting confirmation</p>
            </div>
        </section>

        <!-- Quick Actions -->
        <section>
            <h3 class="text-2xl font-bold mb-4">Quick Actions</h3>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                
                <!-- Manage Events -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold group-hover:text-yellow-400">Manage Events</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 6H7m4-16h2m-1 16v1m0-1v-1m0-10v-1m0 1h1m-1 0h-1" />
                        </svg>
                    </div>
                    <p class="text-gray-400 mb-4">Create, edit, or delete events seamlessly with our intuitive interface.</p>
                    <a href="manageEvents.php" class="text-yellow-400 hover:underline">Go to Event Manager →</a>
                </div>

                <!-- Manage Participants -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold group-hover:text-yellow-400">Participants</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V8h-5M3 20h5V4H3v16zm6 0h5V12h-5v8z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 mb-4">Track and manage participant registrations for your events.</p>
                    <a href="participants.php" class="text-yellow-400 hover:underline">View Participants →</a>
                </div>

                <!-- Analytics -->
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold group-hover:text-yellow-400">Analytics</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3h2v8h-2zm0 10h2v-8h-2v8zM3 21h18M3 3v18M3 7h18" />
                        </svg>
                    </div>
                    <p class="text-gray-400 mb-4">Review stats and participant insights to boost engagement.</p>
                    <a href="analytics.php" class="text-yellow-400 hover:underline">Explore Analytics →</a>
                </div>

            </div>
        </section>

        <!-- Organizer Profile -->
        <section class="mt-12">
            <h3 class="text-2xl font-bold mb-4">Your Organizer Profile</h3>
            <div class="bg-gray-800 p-6 rounded-2xl shadow-lg md:w-1/2 mx-auto">
                <p><strong>Username:</strong> <span class="text-yellow-400"><?php echo htmlspecialchars($username); ?></span></p>
                <p><strong>Email:</strong> <span class="text-yellow-400"><?php echo htmlspecialchars($email); ?></span></p>
                <p><strong>Role:</strong> <span class="text-yellow-400"><?php echo ucfirst(htmlspecialchars($account_type)); ?></span></p>
                <a href="editProfile.php" class="inline-block mt-4 bg-yellow-400 text-black px-4 py-2 rounded-full font-semibold hover:bg-yellow-500 transition">Edit Profile</a>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 p-6 mt-12 text-center text-gray-500 text-sm">
        <div class="flex justify-center space-x-8 mb-4">
            <a href="about.html" class="hover:text-yellow-400">About</a>
            <a href="contact.html" class="hover:text-yellow-400">Contact</a>
            <a href="privacy.html" class="hover:text-yellow-400">Privacy Policy</a>
            <a href="terms.html" class="hover:text-yellow-400">Terms of Use</a>
        </div>
        <p>&copy; <?php echo date("Y"); ?> UniGather. All rights reserved.</p>
    </footer>

</body>
</html>
