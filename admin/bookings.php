<?php
/**
 * Admin Bookings Management Controller - GuideFlux
 * View, filter, and manage tour package and flight bookings
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/settings.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$pdo = getDBConnection();
$alertMessage = '';
$alertType = 'success';

// Handle booking status update
if (isset($_POST['update_status']) && isset($_POST['booking_id']) && isset($_POST['status'])) {
    if ($pdo) {
        try {
            $bookingId = (int)$_POST['booking_id'];
            $newStatus = trim($_POST['status']);
            $allowed = ['confirmed', 'pending', 'completed', 'cancelled'];
            if (in_array($newStatus, $allowed)) {
                $stmt = $pdo->prepare("UPDATE `bookings` SET `status` = ? WHERE `id` = ?");
                $stmt->execute([$newStatus, $bookingId]);
                $alertMessage = "Booking status updated to " . ucfirst($newStatus);
            }
        } catch (Exception $e) {
            $alertMessage = "Failed to update status: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// Fetch bookings
$bookings = [];
$totalBookings = 0;
$confirmedCount = 0;
$pendingCount = 0;
$totalSales = 0.0;

if ($pdo) {
    try {
        $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM `bookings`")->fetchColumn();
        $confirmedCount = (int)$pdo->query("SELECT COUNT(*) FROM `bookings` WHERE `status` = 'confirmed'")->fetchColumn();
        $pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM `bookings` WHERE `status` = 'pending'")->fetchColumn();
        $totalSales = (float)$pdo->query("SELECT COALESCE(SUM(amount), 0) FROM `bookings` WHERE `status` != 'cancelled'")->fetchColumn();

        $filterStatus = isset($_GET['status']) ? trim($_GET['status']) : '';
        $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

        $sql = "SELECT * FROM `bookings` WHERE 1=1";
        $params = [];

        if (!empty($filterStatus)) {
            $sql .= " AND `status` = ?";
            $params[] = $filterStatus;
        }
        if (!empty($searchQuery)) {
            $sql .= " AND (`booking_code` LIKE ? OR `customer_name` LIKE ? OR `customer_email` LIKE ? OR `package_title` LIKE ?)";
            $params[] = "%$searchQuery%";
            $params[] = "%$searchQuery%";
            $params[] = "%$searchQuery%";
            $params[] = "%$searchQuery%";
        }

        $sql .= " ORDER BY `id` DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();
    } catch (Exception $e) {
        $bookings = [];
    }
}

$pageTitle = "Bookings & Reservations";
include 'components/head.php';
?>

<div class="min-h-screen flex bg-[#f8fafc] antialiased selection:bg-teal-600 selection:text-white">
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 xl:pl-72 flex flex-col min-w-0 transition-all">
        <!-- Header -->
        <?php include 'components/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900 tracking-tight">Bookings &amp; Reservations</h1>
                    <p class="text-xs sm:text-sm text-slate-500">Track and manage customer tour bookings, travel dates, and payment statuses.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="packages.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <i class="fa-solid fa-plus"></i>
                        <span>Explore Packages</span>
                    </a>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-4 rounded-xl text-xs font-semibold flex items-center gap-2.5 <?php echo $alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'; ?>">
                    <i class="fa-solid <?php echo $alertType === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-rose-600'; ?> text-sm"></i>
                    <span><?php echo htmlspecialchars($alertMessage); ?></span>
                </div>
            <?php endif; ?>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Bookings</div>
                        <div class="text-2xl font-extrabold font-space text-slate-900 mt-1"><?php echo number_format($totalBookings); ?></div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Confirmed</div>
                        <div class="text-2xl font-extrabold font-space text-emerald-600 mt-1"><?php echo number_format($confirmedCount); ?></div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Review</div>
                        <div class="text-2xl font-extrabold font-space text-amber-600 mt-1"><?php echo number_format($pendingCount); ?></div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</div>
                        <div class="text-2xl font-extrabold font-space text-slate-900 mt-1">₹<?php echo number_format($totalSales); ?></div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <a href="bookings.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?php echo empty($filterStatus) ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">All (<?php echo $totalBookings; ?>)</a>
                    <a href="bookings.php?status=confirmed" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?php echo $filterStatus === 'confirmed' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">Confirmed</a>
                    <a href="bookings.php?status=pending" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?php echo $filterStatus === 'pending' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">Pending</a>
                    <a href="bookings.php?status=completed" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all <?php echo $filterStatus === 'completed' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">Completed</a>
                </div>

                <form method="GET" action="bookings.php" class="flex items-center gap-2 w-full sm:w-72">
                    <?php if (!empty($filterStatus)): ?>
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($filterStatus); ?>">
                    <?php endif; ?>
                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="q" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search code, name, tour..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-teal-500 focus:bg-white transition-all">
                    </div>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-4">Booking Code</th>
                                <th class="py-3.5 px-4">Customer</th>
                                <th class="py-3.5 px-4">Tour / Package</th>
                                <th class="py-3.5 px-4">Travel Date</th>
                                <th class="py-3.5 px-4">Amount</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $b): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3.5 px-4">
                                            <span class="font-mono font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-200"><?php echo htmlspecialchars($b['booking_code']); ?></span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-slate-900"><?php echo htmlspecialchars($b['customer_name']); ?></div>
                                            <div class="text-[11px] text-slate-400"><?php echo htmlspecialchars($b['customer_email']); ?> • <?php echo htmlspecialchars($b['customer_phone']); ?></div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-medium text-slate-800 max-w-xs truncate"><?php echo htmlspecialchars($b['package_title']); ?></div>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 font-medium">
                                            <i class="fa-regular fa-calendar text-slate-400 mr-1"></i>
                                            <?php echo date('M d, Y', strtotime($b['travel_date'])); ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-bold text-slate-900 font-space">
                                            ₹<?php echo number_format($b['amount']); ?>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <?php
                                            $st = strtolower($b['status']);
                                            $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                            if ($st === 'confirmed') $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            elseif ($st === 'pending') $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                            elseif ($st === 'completed') $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                            elseif ($st === 'cancelled') $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                            ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border <?php echo $badgeClass; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $st === 'confirmed' ? 'bg-emerald-500' : ($st === 'pending' ? 'bg-amber-500' : ($st === 'completed' ? 'bg-blue-500' : 'bg-rose-500')); ?>"></span>
                                                <?php echo ucfirst($st); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <form method="POST" action="bookings.php" class="inline-flex items-center gap-1">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                                <input type="hidden" name="update_status" value="1">
                                                <select name="status" onchange="this.form.submit()" class="text-[11px] py-1 px-2 rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-slate-300 focus:outline-hidden cursor-pointer">
                                                    <option value="confirmed" <?php echo $st === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="pending" <?php echo $st === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="completed" <?php echo $st === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo $st === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        <i class="fa-solid fa-calendar-xmark text-3xl mb-2 block"></i>
                                        No bookings found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include 'components/footer.php'; ?>
