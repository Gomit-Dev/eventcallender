
<?php
    session_start();

     
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'attendee') {
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 1s ease-out forwards;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-green-500 to-teal-950 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class=" shadow-lg bg-white">
    <div class="container mx-auto px-6 py-1 flex justify-between items-center">
        
      <a href="#" class="text-2xl font-bold text-green-700">
        <img src="img/logo.png" alt="Logo" class="inline-block h-16 mr-2"> <p class=" hidden sm:inline ">UniGather</p>
      </a>
      <div class="flex-grow text-center space-x-4 text-lg font-semibold">
        <a href="#" class="text-gray-700 hover:text-green-500">Home</a>
        <a href="#" class="text-gray-700 hover:text-green-500">Events</a>
        <a href="#" class="text-gray-700 hover:text-green-500">Profile</a>
      </div>
      <div class="hidden sm:flex items-center space-x-4">
        <span class="text-gray-700"><?php echo htmlspecialchars($email); ?></span>
        <a href="login.html" class="text-red-500 hover:text-red-700">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-grow container mx-auto px-6 py-12 animate-fade-in">
    <h1 class="text-white mb-8">
        <p class="font-bold text-4xl">Welcome</p>
        <br> 
        <span class="block sm:inline text-md"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </h1>

    <!-- Events List -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      
      <!-- Event Card Example -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <img src="img/event1.jpg" alt="Event" class="w-full h-48 object-cover">
        <div class="p-6">
          <h2 class="text-2xl font-semibold text-green-700 mb-2">Event Title</h2>
          <p class="text-gray-700 mb-4">Brief description of the event goes here. Join us for an amazing experience!</p>
          <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-full">RSVP</button>
        </div>
      </div>

      <!-- Add more event cards... -->

    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white p-4 text-center text-gray-600 mt-8">
        <div class="flex justify-center space-x-12 mb-8">
            <a href="about.html" class="hover:underline">About Us</a>
            <a href="contact.html" class="hover:underline">Contact Us</a>
            <a href="privacy.html" class="hover:underline">Privacy Policy</a>
            <a href="terms.html" class="hover:underline">Terms of Service</a>
        </div>
        <p>&copy; <?php echo date("Y"); ?> UniGather. All rights reserved.</p>
    </footer>

</body>
</html>
