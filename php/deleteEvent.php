<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header('Location: login.html');
    exit;
}

$eventsFile = 'events.json';
$eventId = $_GET['id'] ?? null;

if (!$eventId) {
    header('Location: manageEvents.php');
    exit;
}

// Get existing events
$events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];

// Filter out the event to delete
$events = array_filter($events, function($event) use ($eventId) {
    return $event['id'] != $eventId;
});

// Save updated events
file_put_contents($eventsFile, json_encode(array_values($events), JSON_PRETTY_PRINT));

// Redirect back to manageEvents.php
header('Location: manageEvents.php');
exit;
?>
