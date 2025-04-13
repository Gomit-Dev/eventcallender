<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'organizer') {
    header('Location: login.html');
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($action) || $id === 0) {
    header('Location: participants.php');
    exit;
}

$participantsFile = 'participants.json';

if (!file_exists($participantsFile)) {
    die('Participants file not found!');
}

$participantsData = file_get_contents($participantsFile);
$participants = json_decode($participantsData, true);

if (!$participants) {
    die('Failed to read participants data!');
}

$found = false;

foreach ($participants as &$participant) {
    if ($participant['id'] === $id) {
        if ($action === 'approve') {
            $participant['status'] = 'Approved';
        } elseif ($action === 'reject') {
            $participant['status'] = 'Rejected';
        }
        $found = true;
        break;
    }
}

if ($found) {
    file_put_contents($participantsFile, json_encode($participants, JSON_PRETTY_PRINT));
}

header('Location: participants.php');
exit;
?>
