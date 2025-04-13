<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$action = $_POST['action'] ?? '';

$eventsFile = 'events.json';
$events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];

if ($action === 'add_event' && $role === 'organizer') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $events[] = [
        'title' => $title,
        'date' => $date,
        'description' => $description,
        'rsvps' => []
    ];

    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;

} elseif ($action === 'rsvp' && $role === 'user') {
    $event_id = (int)$_POST['event_id'];

    if (!isset($events[$event_id]['rsvps'])) {
        $events[$event_id]['rsvps'] = [];
    }

    if (!in_array($username, $events[$event_id]['rsvps'])) {
        $events[$event_id]['rsvps'][] = $username;
    }

    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;

} else {
    die('Invalid action!');
}
?>
