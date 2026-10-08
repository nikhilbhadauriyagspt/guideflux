<?php
/**
 * Real-time Ocean Cruise Reservation & Advance Token Booking API Endpoint
 * GuideFlux / Orion Advent Travel Portal
 */
header('Content-Type: application/json; charset=UTF-8');
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// 1. Mandatory User Login Check
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'login_required' => true,
        'message' => 'Please sign in to your GuideFlux account to complete your cruise reservation.'
    ]);
    exit();
}

$pdo = getDBConnection();
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database connection error.']);
    exit();
}

$userId = (int)$_SESSION['user_id'];
$leadName = trim($_POST['lead_name'] ?? ($_SESSION['user_name'] ?? ''));
$leadEmail = trim($_POST['lead_email'] ?? ($_SESSION['user_email'] ?? ''));
$leadPhone = trim($_POST['lead_phone'] ?? ($_SESSION['user_phone'] ?? ''));

$cruiseId = trim($_POST['cruise_id'] ?? '');
$cruiseTitle = trim($_POST['cruise_title'] ?? 'Ocean Cruise Sailing');
$shipName = trim($_POST['ship_name'] ?? 'The Empress');
$cabinName = trim($_POST['cabin_name'] ?? 'Interior Stateroom');
$sailingDate = trim($_POST['sailing_date'] ?? date('Y-m-d', strtotime('+10 days')));
$adultsCount = max(1, (int)($_POST['adults_count'] ?? 2));
$kidsCount = max(0, (int)($_POST['kids_count'] ?? 0));

$perPersonPrice = (float)($_POST['per_person_price'] ?? 19999);
$tokenPerPerson = (float)($_POST['token_advance'] ?? 3000);

// Base calculation
$baseFare = ($adultsCount * $perPersonPrice) + ($kidsCount * ($perPersonPrice * 0.5));
$portTaxes = ($adultsCount + $kidsCount) * 1800;
$totalAmount = $baseFare + $portTaxes;
$advanceAmount = min($totalAmount, max(1000, $adultsCount * $tokenPerPerson));
$balanceAmount = max(0, round($totalAmount - $advanceAmount, 2));

if (empty($leadName) || empty($leadPhone)) {
    echo json_encode(['success' => false, 'message' => 'Please provide your full traveler name and phone number.']);
    exit();
}

// Payment details simulation
$paymentGateway = trim($_POST['payment_gateway'] ?? 'razorpay');
$paymentId = trim($_POST['razorpay_payment_id'] ?? ('PAY_CRU_' . strtoupper(substr(md5(uniqid()), 0, 10))));

try {
    $bookingCode = 'CRU-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    $fullBookingTitle = $cruiseTitle . ' • ' . $shipName . ' (' . $cabinName . ')';

    $detailsJson = json_encode([
        'cruise_id' => $cruiseId,
        'cruise_title' => $cruiseTitle,
        'ship_name' => $shipName,
        'cabin_name' => $cabinName,
        'adults' => $adultsCount,
        'kids' => $kidsCount,
        'per_person_price' => $perPersonPrice,
        'port_taxes' => $portTaxes,
        'advance_amount' => $advanceAmount,
        'balance_amount' => $balanceAmount,
        'gateway' => $paymentGateway,
        'booked_at' => date('Y-m-d H:i:s')
    ]);

    $stmt = $pdo->prepare("INSERT INTO `bookings` (
        `booking_code`, `user_id`, `booking_type`, `customer_name`, `customer_email`, `customer_phone`, 
        `package_title`, `travel_date`, `amount`, `advance_amount`, `balance_amount`,
        `payment_gateway`, `payment_id`, `booking_details`, `status`
    ) VALUES (?, ?, 'cruise', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')");

    $stmt->execute([
        $bookingCode,
        $userId,
        $leadName,
        $leadEmail,
        $leadPhone,
        $fullBookingTitle,
        $sailingDate,
        $totalAmount,
        $advanceAmount,
        $balanceAmount,
        $paymentGateway,
        $paymentId,
        $detailsJson
    ]);

    // Send Confirmation Email
    $bookingData = [
        'booking_code' => $bookingCode,
        'customer_name' => $leadName,
        'customer_email' => $leadEmail,
        'customer_phone' => $leadPhone,
        'package_title' => $fullBookingTitle,
        'travel_date' => $sailingDate,
        'amount' => $totalAmount,
        'advance_amount' => $advanceAmount,
        'balance_amount' => $balanceAmount,
        'payment_id' => $paymentId,
        'booking_type' => 'cruise'
    ];

    $emailHtml = generateBookingConfirmationEmailHtml($bookingData);
    @sendGuideFluxMail($leadEmail, $leadName, "Cruise Stateroom Confirmed: " . $fullBookingTitle . " (" . $bookingCode . ")", $emailHtml);

    // Trigger Admin Notification
    if (file_exists(__DIR__ . '/../includes/notifications.php')) {
        require_once __DIR__ . '/../includes/notifications.php';
        createAdminNotification(
            'booking',
            'New Ocean Cruise Reservation',
            $leadName . ' reserved ' . $cabinName . ' on "' . $cruiseTitle . '" - Token ₹' . number_format($advanceAmount) . ' received.',
            'bookings.php?code=' . urlencode($bookingCode),
            ['booking_code' => $bookingCode, 'amount' => $totalAmount, 'advance' => $advanceAmount, 'gateway' => $paymentGateway]
        );
    }

    echo json_encode([
        'success' => true,
        'booking_code' => $bookingCode,
        'total_amount' => $totalAmount,
        'advance_amount' => $advanceAmount,
        'balance_amount' => $balanceAmount,
        'message' => 'Your cruise cabin has been successfully locked with token advance!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create cruise reservation: ' . $e->getMessage()
    ]);
}
