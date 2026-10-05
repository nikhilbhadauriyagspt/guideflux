<?php
/**
 * Real-time Tour Package Booking API Endpoint
 * Handles package reservation submissions, generates booking codes,
 * records to `bookings` table, and returns JSON confirmation.
 */
header('Content-Type: application/json; charset=UTF-8');
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/settings.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$pdo = getDBConnection();
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection error.']);
    exit();
}

$leadName = trim($_POST['lead_name'] ?? '');
$leadPhone = trim($_POST['lead_phone'] ?? '');
$leadEmail = trim($_POST['lead_email'] ?? '');
if (empty($leadEmail) && isset($_SESSION['user_email'])) {
    $leadEmail = $_SESSION['user_email'];
}
if (empty($leadEmail)) {
    $leadEmail = 'traveler@guideflux.com';
}

$packageTitle = trim($_POST['package_title'] ?? 'Holiday Tour');
$travelDate = trim($_POST['travel_date'] ?? date('Y-m-d', strtotime('+7 days')));
$adultsCount = (int)($_POST['adults_count'] ?? 2);
$tokenAmount = (float)($_POST['token_amount'] ?? 2500);
$totalAmount = (float)($_POST['total_amount'] ?? ($tokenAmount * $adultsCount));

if (empty($leadName) || empty($leadPhone)) {
    echo json_encode(['success' => false, 'message' => 'Please provide your Full Name and Phone Number.']);
    exit();
}

try {
    $bookingCode = 'PKG-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    $fullPackageTitle = $packageTitle . ' (' . $adultsCount . ' Traveler' . ($adultsCount > 1 ? 's' : '') . ')';

    $stmt = $pdo->prepare("INSERT INTO `bookings` (
        `booking_code`, `customer_name`, `customer_email`, `customer_phone`, 
        `package_title`, `travel_date`, `amount`, `status`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')");

    $stmt->execute([
        $bookingCode,
        $leadName,
        $leadEmail,
        $leadPhone,
        $fullPackageTitle,
        $travelDate,
        $totalAmount
    ]);

    echo json_encode([
        'success' => true,
        'booking_code' => $bookingCode,
        'package_title' => $packageTitle,
        'lead_name' => $leadName,
        'travel_date' => $travelDate,
        'adults_count' => $adultsCount,
        'token_amount' => $tokenAmount,
        'total_amount' => $totalAmount,
        'message' => 'Tour package booked successfully!'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Booking failed: ' . $e->getMessage()]);
}
