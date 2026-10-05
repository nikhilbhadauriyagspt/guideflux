<?php
/**
 * Real-time Hotel & Room Reservation API Endpoint
 * Handles live bookings from hotel-details.php, saves into database `bookings`,
 * and returns JSON response with confirmation details.
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
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$guestName = trim($_POST['guest_name'] ?? '');
$guestPhone = trim($_POST['guest_phone'] ?? '');
$guestEmail = trim($_POST['guest_email'] ?? '');
if (empty($guestEmail) && isset($_SESSION['user_email'])) {
    $guestEmail = $_SESSION['user_email'];
}
if (empty($guestEmail)) {
    $guestEmail = 'guest@guideflux.com';
}

$hotelName = trim($_POST['hotel_name'] ?? 'Hotel');
$roomName = trim($_POST['room_name'] ?? 'Deluxe Room');
$checkIn = trim($_POST['check_in'] ?? date('Y-m-d'));
$checkOut = trim($_POST['check_out'] ?? date('Y-m-d', strtotime('+2 days')));
$roomsCount = (int)($_POST['rooms_count'] ?? 1);
$guestsCount = (int)($_POST['guests_count'] ?? 2);
$totalAmount = (float)($_POST['total_amount'] ?? 0);

if (empty($guestName) || empty($guestPhone)) {
    echo json_encode(['success' => false, 'message' => 'Please provide your Full Name and Phone Number.']);
    exit();
}

try {
    $bookingCode = 'HTL-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    $packageTitle = $hotelName . ' - ' . $roomName . ' (' . $roomsCount . ' Room' . ($roomsCount > 1 ? 's' : '') . ', ' . $guestsCount . ' Guests)';

    $stmt = $pdo->prepare("INSERT INTO `bookings` (
        `booking_code`, `customer_name`, `customer_email`, `customer_phone`, 
        `package_title`, `travel_date`, `amount`, `status`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')");

    $stmt->execute([
        $bookingCode,
        $guestName,
        $guestEmail,
        $guestPhone,
        $packageTitle,
        $checkIn,
        $totalAmount
    ]);

    echo json_encode([
        'success' => true,
        'booking_code' => $bookingCode,
        'hotel_name' => $hotelName,
        'room_name' => $roomName,
        'guest_name' => $guestName,
        'amount' => $totalAmount,
        'message' => 'Your room reservation is confirmed!'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Booking error: ' . $e->getMessage()]);
}
