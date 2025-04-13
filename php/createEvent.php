<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header('Location: login.html');
    exit;
}

$eventsFile = 'events.json';

// Get existing events
$events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];

// Get form data
$title = $_POST['title'];
$date = $_POST['date'];
$location = $_POST['location'];
$description = $_POST['description'];
$status = 'Upcoming';

// Generate new ID
$newId = count($events) > 0 ? max(array_column($events, 'id')) + 1 : 1;

// New event array
$newEvent = [
    'id' => $newId,
    'title' => $title,
    'date' => $date,
    'location' => $location,
    'description' => $description,
    'status' => $status
];

// Add to events
$events[] = $newEvent;

// Save back to JSON
file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));

// Redirect back to manageEvents.php
header('Location: manageEvents.php');
exit;
?>
