<?php
    session_start();

    $username = $_SESSION['username'];
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';  
    $account_type = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : 'user';  

    
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
        header('Location: login.html');
        exit;
        
    }

    $eventsFile = 'events.json';

    // Get event ID from URL
    $eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Load events
    $events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];

    // Find the event to edit
    $eventToEdit = null;
    foreach ($events as $event) {
        if ($event['id'] == $eventId) {
            $eventToEdit = $event;
            break;
        }
    }

    if (!$eventToEdit) {
        echo "Event not found.";
        exit;
    }

    // Handle form submission (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        // Update event
        foreach ($events as &$event) {
            if ($event['id'] == $eventId) {
                $event['title'] = $title;
                $event['date'] = $date;
                $event['location'] = $location;
                $event['description'] = $description;
                $event['status'] = $status;
                break;
            }
        }

        // Save back to JSON file
        file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));

        // Redirect back to manageEvents.php
        header('Location: manageEvents.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Event | Organizer</title>
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

    <!-- Edit Event Form -->
    <main class="max-w-3xl mx-auto mt-12 p-8 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-yellow-400 mb-8">Edit Event</h2>

        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-300">Event Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($eventToEdit['title']); ?>" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Date</label>
                <input type="date" name="date" value="<?php echo htmlspecialchars($eventToEdit['date']); ?>" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Location</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($eventToEdit['location']); ?>" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Description</label>
                <textarea name="description" rows="3" required class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400"><?php echo htmlspecialchars($eventToEdit['description']); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Status</label>
                <select name="status" class="w-full px-4 py-2 mt-1 rounded bg-gray-700 text-white focus:ring-yellow-400 focus:border-yellow-400">
                    <option value="Upcoming" <?php echo ($eventToEdit['status'] === 'Upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="Completed" <?php echo ($eventToEdit['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo ($eventToEdit['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="manageEvents.php" class="bg-gray-600 px-4 py-2 rounded hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="bg-yellow-400 text-black font-bold px-6 py-2 rounded hover:bg-yellow-500 transition">Update Event</button>
            </div>
        </form>
    </main>

</body>
</html>
