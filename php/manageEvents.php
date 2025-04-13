<?php
    session_start();

    $username = $_SESSION['username'];
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';  
    $account_type = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : 'user'; 

     
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
        header('Location: login.html');
        exit;
    }

    $username = $_SESSION['username'];
    $eventsFile = 'events.json';

    // Load events data
    $events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Events | Organizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">

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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto mt-12 p-8">

        <!-- Heading -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-yellow-400">Manage Events</h2>
            <button onclick="toggleModal()" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded hover:bg-yellow-500 transition">
                + Add New Event
            </button>
        </div>

        <!-- Events Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 rounded-lg shadow-md">
                <thead class="bg-gray-700 text-yellow-400">
                    <tr>
                        <th class="py-4 px-6 text-left">Title</th>
                        <th class="py-4 px-6 text-left">Date</th>
                        <th class="py-4 px-6 text-left">Location</th>
                        <th class="py-4 px-6 text-left">Status</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-600 transition">
                            <td class="py-4 px-6"><?php echo htmlspecialchars($event['title']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($event['date']); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($event['location']); ?></td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    <?php echo ($event['status'] === 'Upcoming') ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'; ?>">
                                    <?php echo htmlspecialchars($event['status']); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center flex justify-center space-x-4">
                                <a href="editEvent.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600 transition">Edit</a>
                                <a href="deleteEvent.php?id=<?php echo $event['id']; ?>" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal for Adding Event -->
    <div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
        <div class="bg-gray-800 rounded-lg p-8 w-[90%] max-w-lg shadow-lg">
            <h3 class="text-2xl font-bold mb-6 text-yellow-400">Create New Event</h3>

            <form action="createEvent.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Event Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Date</label>
                    <input type="date" name="date" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Location</label>
                    <input type="text" name="location" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea name="description" rows="3" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400"></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal()" class="bg-gray-600 px-4 py-2 rounded hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-2 rounded hover:bg-yellow-500 transition">Create Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for Modal -->
    <script>
        function toggleModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.toggle('hidden');
        }
    </script>

</body>
</html>
