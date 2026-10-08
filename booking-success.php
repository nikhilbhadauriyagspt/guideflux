<?php
/**
 * Booking Confirmation & Travel Voucher Screen
 * GuideFlux Travel Portal
 */
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/settings.php';

$bookingCode = trim($_GET['code'] ?? '');
$bookingType = trim($_GET['type'] ?? 'package');

$booking = null;
$pdo = getDBConnection();

if ($pdo && !empty($bookingCode)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `bookings` WHERE `booking_code` = ? LIMIT 1");
        $stmt->execute([$bookingCode]);
        $booking = $stmt->fetch();
    } catch (Exception $e) {
        $booking = null;
    }
}

// Fallback dummy for direct preview if code not found
if (!$booking) {
    $booking = [
        'booking_code' => !empty($bookingCode) ? $bookingCode : 'GFX-' . strtoupper(substr(md5(time()), 0, 8)),
        'customer_name' => $_SESSION['user_name'] ?? 'Traveler',
        'customer_email' => $_SESSION['user_email'] ?? 'traveler@guideflux.com',
        'customer_phone' => $_SESSION['user_phone'] ?? '+91 98765 43210',
        'package_title' => 'Luxury Kashmir Tour Circuit with Houseboat Stay',
        'travel_date' => date('Y-m-d', strtotime('+7 days')),
        'amount' => 38500.00,
        'advance_amount' => 11550.00,
        'balance_amount' => 26950.00,
        'payment_gateway' => 'razorpay',
        'payment_id' => 'PAY_' . strtoupper(substr(md5(time()), 0, 10)),
        'status' => 'confirmed',
        'booking_type' => $bookingType
    ];
}

$isFlight = ($booking['booking_type'] ?? '') === 'flight_inquiry';
$pageTitle = ($isFlight ? 'Flight Request Received' : 'Booking Confirmed!') . ' - ' . $booking['booking_code'];
$siteWhatsapp = getSetting('site_whatsapp', '919876543210');

require_once 'components/header.php';
require_once 'components/navbar.php';
?>

<main class="w-full bg-[#f8fafc] py-10 sm:py-14 px-4 sm:px-8 xl:px-12 min-h-screen">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Top Success Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 text-center space-y-4 shadow-sm">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full <?php echo $isFlight ? 'bg-sky-50 text-sky-600 border border-sky-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200'; ?> flex items-center justify-center mx-auto text-2xl sm:text-3xl animate-bounce">
                <i class="fa-solid <?php echo $isFlight ? 'fa-plane-circle-check' : 'fa-check'; ?>"></i>
            </div>

            <div class="space-y-1.5 max-w-md mx-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?php echo $isFlight ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'; ?>">
                    <span class="w-2 h-2 rounded-full <?php echo $isFlight ? 'bg-sky-500' : 'bg-emerald-500'; ?> animate-pulse"></span>
                    <span><?php echo $isFlight ? 'Flight Inquiry Submitted' : 'Booking Successfully Confirmed'; ?></span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-space">
                    <?php echo $isFlight ? 'We Have Received Your Flight Request!' : 'Pack Your Bags, You\'re Going Places!'; ?>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    <?php echo $isFlight 
                        ? 'Our flight reservation concierge is checking live seat inventory and will reach out to you within 15 minutes.' 
                        : 'Your travel voucher and payment receipt have been dispatched to <strong>' . htmlspecialchars($booking['customer_email']) . '</strong>.'; ?>
                </p>
            </div>

            <!-- Booking Code Barcode Capsule -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-left max-w-lg mx-auto">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Booking Reference Code</span>
                    <span class="text-base font-extrabold font-mono text-brand-700 tracking-wider"><?php echo htmlspecialchars($booking['booking_code']); ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-3.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-print text-slate-500"></i>
                        <span>Print Voucher</span>
                    </button>
                    <a href="my-trips.php" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-suitcase text-[11px]"></i>
                        <span>My Trips</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Reservation Voucher Ticket -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="bg-slate-900 text-white p-5 sm:p-6 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-teal-400 text-lg">
                        <i class="fa-solid <?php echo $isFlight ? 'fa-plane-departure' : (($booking['booking_type'] ?? '') === 'hotel' ? 'fa-hotel' : 'fa-map-location-dot'); ?>"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-teal-400 uppercase tracking-wider">Itinerary Details</div>
                        <h2 class="text-sm sm:text-base font-bold text-white"><?php echo htmlspecialchars($booking['package_title']); ?></h2>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 block uppercase">Payment Status</span>
                    <span class="text-xs font-bold <?php echo $isFlight ? 'text-amber-400' : 'text-emerald-400'; ?>">
                        <?php echo $isFlight ? 'Query Under Review' : 'Advance Token Verified'; ?>
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Traveler Matrix -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pb-6 border-b border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 text-[11px] block">Lead Traveler</span>
                        <strong class="text-slate-900 font-bold"><?php echo htmlspecialchars($booking['customer_name']); ?></strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[11px] block">Travel / Check-in Date</span>
                        <strong class="text-brand-700 font-bold"><?php echo date('D, M d, Y', strtotime($booking['travel_date'])); ?></strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[11px] block">Contact Number</span>
                        <strong class="text-slate-900 font-bold"><?php echo htmlspecialchars($booking['customer_phone']); ?></strong>
                    </div>
                </div>

                <!-- Price & Advance Breakdown -->
                <div class="bg-teal-50/60 rounded-2xl border border-teal-100 p-5 space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-teal-900 border-b border-teal-100/80 pb-2">
                        <span>Payment Summary &amp; Balance</span>
                        <span class="font-mono text-[11px]">Txn ID: <?php echo htmlspecialchars($booking['payment_id'] ?? 'PAY_VERIFIED'); ?></span>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-700">
                        <div class="flex justify-between">
                            <span>Total Trip Cost:</span>
                            <strong class="text-slate-900">₹<?php echo number_format((float)$booking['amount']); ?></strong>
                        </div>
                        <?php if (!$isFlight): ?>
                            <div class="flex justify-between text-emerald-700">
                                <span>Advance Token Paid Online:</span>
                                <strong>₹<?php echo number_format((float)$booking['advance_amount']); ?></strong>
                            </div>
                            <div class="flex justify-between text-amber-700 pt-2 border-t border-teal-100 font-bold text-sm">
                                <span>Balance Payable on Arrival:</span>
                                <span>₹<?php echo number_format((float)$booking['balance_amount']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="flex justify-between text-sky-700 pt-2 border-t border-teal-100 font-bold text-sm">
                                <span>Estimated Quoted Fare:</span>
                                <span>₹<?php echo number_format((float)$booking['amount']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Next Steps Info Box -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-brand-600"></i>
                        <span>Important Travel Guidelines</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-1 text-slate-500">
                        <li>Please carry a government photo ID (Aadhar/Passport/Voter ID) for all travelers.</li>
                        <li>Chauffeur and hotel check-in voucher will be re-sent via WhatsApp 24 hours prior to travel.</li>
                        <li>For itinerary customization or hotel room upgrades, reach out to our concierge below.</li>
                    </ul>
                </div>

                <!-- Actions Footer -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <a href="https://wa.me/<?php echo htmlspecialchars($siteWhatsapp); ?>?text=<?php echo urlencode("Hi " . $siteName . ", I booked " . $booking['package_title'] . " (Booking ID: " . $booking['booking_code'] . "). Please share my travel coordinator details."); ?>" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Chat on WhatsApp (24x7)</span>
                    </a>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="search.php" class="flex-1 sm:flex-initial text-center px-4 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                            Explore More Tours
                        </a>
                        <a href="my-trips.php" class="flex-1 sm:flex-initial text-center px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition">
                            View All My Trips &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once 'components/footer.php'; ?>
