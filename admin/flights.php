<?php
/**
 * Admin Flights Management & Live Route Engine Controller - GuideFlux
 * Configure flight routes, airline commissions, GDS providers, and live testing
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

// Handle quick markup updates directly from flights manager
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_flight_rules'])) {
    $markupType = $_POST['flight_markup_type'] ?? 'fixed';
    $markupDom = $_POST['flight_commission_domestic'] ?? '350';
    $markupIntl = $_POST['flight_commission_international'] ?? '1200';
    $convFee = $_POST['flight_convenience_fee'] ?? '250';
    $amadeusEnv = $_POST['amadeus_environment'] ?? 'test';

    updateSettings([
        'flight_markup_type' => $markupType,
        'flight_commission_domestic' => $markupDom,
        'flight_commission_international' => $markupIntl,
        'flight_convenience_fee' => $convFee,
        'amadeus_environment' => $amadeusEnv
    ]);

    $alertMessage = "Flight pricing rules and aggregator settings saved successfully!";
}

$settings = getGlobalSettings(true);

// Popular Flight Routes Data
$popularRoutes = [
    ['from_code' => 'DEL', 'from_city' => 'New Delhi', 'to_code' => 'BOM', 'to_city' => 'Mumbai', 'airline' => 'IndiGo / Air India', 'base_fare' => 4200, 'duration' => '2h 15m', 'type' => 'domestic', 'status' => 'active'],
    ['from_code' => 'BOM', 'from_city' => 'Mumbai', 'to_code' => 'GOI', 'to_city' => 'Goa', 'airline' => 'IndiGo / Akasa Air', 'base_fare' => 2800, 'duration' => '1h 20m', 'type' => 'domestic', 'status' => 'active'],
    ['from_code' => 'DEL', 'from_city' => 'New Delhi', 'to_code' => 'BLR', 'to_city' => 'Bangalore', 'airline' => 'Vistara / IndiGo', 'base_fare' => 5100, 'duration' => '2h 45m', 'type' => 'domestic', 'status' => 'active'],
    ['from_code' => 'DEL', 'from_city' => 'New Delhi', 'to_code' => 'SXR', 'to_city' => 'Srinagar', 'airline' => 'SpiceJet / Air India', 'base_fare' => 4600, 'duration' => '1h 35m', 'type' => 'domestic', 'status' => 'active'],
    ['from_code' => 'DEL', 'from_city' => 'New Delhi', 'to_code' => 'DXB', 'to_city' => 'Dubai', 'airline' => 'Emirates / IndiGo', 'base_fare' => 14500, 'duration' => '3h 50m', 'type' => 'international', 'status' => 'active'],
    ['from_code' => 'BOM', 'from_city' => 'Mumbai', 'to_code' => 'DPS', 'to_city' => 'Bali', 'airline' => 'Singapore Airlines / VietJet', 'base_fare' => 21000, 'duration' => '8h 15m', 'type' => 'international', 'status' => 'active'],
    ['from_code' => 'DEL', 'from_city' => 'New Delhi', 'to_code' => 'BKK', 'to_city' => 'Bangkok', 'airline' => 'Thai Airways / Air India', 'base_fare' => 13200, 'duration' => '4h 10m', 'type' => 'international', 'status' => 'active'],
    ['from_code' => 'BOM', 'from_city' => 'Mumbai', 'to_code' => 'SIN', 'to_city' => 'Singapore', 'airline' => 'Singapore Airlines / Air India', 'base_fare' => 16500, 'duration' => '5h 25m', 'type' => 'international', 'status' => 'active'],
];

$pageTitle = "Flight Engine & Aggregators";
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
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900 tracking-tight">Flights &amp; Airline Routes</h1>
                    <p class="text-xs sm:text-sm text-slate-500">Manage real-time GDS aggregators, live pricing rules, domestic/international profit markup, and route availability.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="../flights.php" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <i class="fa-solid fa-plane-departure text-teal-400"></i>
                        <span>Test Live Flight UI</span>
                    </a>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-4 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span><?php echo htmlspecialchars($alertMessage); ?></span>
                </div>
            <?php endif; ?>

            <!-- Commission & GDS Engine Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Profit Markup &amp; GDS Commission Controls</h2>
                            <p class="text-xs text-slate-400">These commission fees are automatically calculated over net airline fares in real-time.</p>
                        </div>
                    </div>
                    <a href="settings.php#tab-flights" class="text-xs font-bold text-teal-600 hover:underline">Full API Settings →</a>
                </div>

                <form method="POST" action="flights.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Markup Mode</label>
                        <select name="flight_markup_type" class="w-full text-xs py-2 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-hidden focus:border-teal-500 font-semibold">
                            <option value="fixed" <?php echo ($settings['flight_markup_type'] ?? 'fixed') === 'fixed' ? 'selected' : ''; ?>>Fixed Amount (₹ Flat per ticket)</option>
                            <option value="percentage" <?php echo ($settings['flight_markup_type'] ?? '') === 'percentage' ? 'selected' : ''; ?>>Percentage (% of Base Fare)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Domestic Commission</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">₹</span>
                            <input type="number" name="flight_commission_domestic" value="<?php echo htmlspecialchars($settings['flight_commission_domestic'] ?? '350'); ?>" class="w-full text-xs pl-7 pr-3 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-hidden focus:border-teal-500 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">International Commission</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">₹</span>
                            <input type="number" name="flight_commission_international" value="<?php echo htmlspecialchars($settings['flight_commission_international'] ?? '1200'); ?>" class="w-full text-xs pl-7 pr-3 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-hidden focus:border-teal-500 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Convenience Fee</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">₹</span>
                            <input type="number" name="flight_convenience_fee" value="<?php echo htmlspecialchars($settings['flight_convenience_fee'] ?? '250'); ?>" class="w-full text-xs pl-7 pr-3 py-2 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-hidden focus:border-teal-500 font-bold">
                        </div>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-between pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>GDS Provider: <strong class="text-slate-800">Amadeus Self-Service Engine (<?php echo strtoupper($settings['amadeus_environment'] ?? 'TEST'); ?>)</strong></span>
                        </div>
                        <button type="submit" name="save_flight_rules" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                            Save Pricing Rules
                        </button>
                    </div>
                </form>
            </div>

            <!-- Popular Routes Table -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="p-4 bg-slate-50/70 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Top Monitored Flight Sectors &amp; Pricing</h3>
                    <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg border border-teal-200">8 High-Demand Sectors</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-4">Origin &rarr; Destination</th>
                                <th class="py-3.5 px-4">Sector Type</th>
                                <th class="py-3.5 px-4">Major Airlines</th>
                                <th class="py-3.5 px-4">Avg Flight Time</th>
                                <th class="py-3.5 px-4">Net Fare</th>
                                <th class="py-3.5 px-4">Admin Commission</th>
                                <th class="py-3.5 px-4">Customer Price</th>
                                <th class="py-3.5 px-4 text-right">Quick Search</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($popularRoutes as $r): ?>
                                <?php
                                $isDom = $r['type'] === 'domestic';
                                $comm = $isDom ? (float)($settings['flight_commission_domestic'] ?? 350) : (float)($settings['flight_commission_international'] ?? 1200);
                                $conv = (float)($settings['flight_convenience_fee'] ?? 250);
                                $finalPrice = $r['base_fare'] + $comm + $conv;
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                            <span><?php echo htmlspecialchars($r['from_code']); ?> (<?php echo htmlspecialchars($r['from_city']); ?>)</span>
                                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                            <span><?php echo htmlspecialchars($r['to_code']); ?> (<?php echo htmlspecialchars($r['to_city']); ?>)</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo $isDom ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-purple-50 text-purple-700 border border-purple-200'; ?>">
                                            <?php echo ucfirst($r['type']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 font-medium"><?php echo htmlspecialchars($r['airline']); ?></td>
                                    <td class="py-3.5 px-4 text-slate-500 font-medium"><?php echo htmlspecialchars($r['duration']); ?></td>
                                    <td class="py-3.5 px-4 text-slate-600 font-space font-medium">₹<?php echo number_format($r['base_fare']); ?></td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-600 font-space">+₹<?php echo number_format($comm + $conv); ?></td>
                                    <td class="py-3.5 px-4 font-extrabold text-slate-900 font-space text-sm">₹<?php echo number_format($finalPrice); ?></td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="../flights.php?from=<?php echo urlencode($r['from_code']); ?>&to=<?php echo urlencode($r['to_code']); ?>&type=oneway" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-teal-600 hover:text-white rounded-lg text-[11px] font-semibold transition-colors">
                                            <span>Search</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include 'components/footer.php'; ?>
