
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
    <title>Analytics | Organizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <main class="max-w-6xl mx-auto mt-12 p-8">
        <h2 class="text-3xl font-bold mb-8 text-yellow-400">Event Analytics</h2>

        <div class="grid gap-8 md:grid-cols-2">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Participant Registrations</h3>
                <canvas id="registrationsChart"></canvas>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Event Attendance</h3>
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        const regCtx = document.getElementById('registrationsChart').getContext('2d');
        const registrationsChart = new Chart(regCtx, {
            type: 'bar',
            data: {
                labels: ['Event 1', 'Event 2', 'Event 3', 'Event 4'],
                datasets: [{
                    label: 'Registrations',
                    data: [50, 75, 100, 60],
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            }
        });

        const attCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attCtx, {
            type: 'line',
            data: {
                labels: ['Event 1', 'Event 2', 'Event 3', 'Event 4'],
                datasets: [{
                    label: 'Attendance',
                    data: [40, 65, 90, 50],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>

</body>
</html>
