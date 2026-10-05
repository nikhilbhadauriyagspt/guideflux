<?php
session_start();

require_once __DIR__ . '/../config/db.php';

// Auth check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch live data from Database
$pdo = getDBConnection();
$totalRevenue = 128450;
$totalBookingsCount = 1482;
$totalPackagesCount = 48;
$recentBookings = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total_rev FROM `bookings`");
        $stat = $stmt->fetch();
        if ($stat && $stat['count'] > 0) {
            $totalBookingsCount = $stat['count'];
            $totalRevenue = $stat['total_rev'];
        }

        $stmtPkg = $pdo->query("SELECT COUNT(*) as count FROM `packages`");
        $pkgStat = $stmtPkg->fetch();
        if ($pkgStat && $pkgStat['count'] > 0) {
            $totalPackagesCount = $pkgStat['count'];
        }

        $stmtRecent = $pdo->query("SELECT * FROM `bookings` ORDER BY `id` DESC LIMIT 10");
        $recentBookings = $stmtRecent->fetchAll();
    } catch (Exception $e) {
        // Fallback
    }
}

$pageTitle = "Executive Dashboard";
include 'components/head.php';
?>

<!-- App Wrapper -->
<div class="min-h-screen flex bg-[#f8fafc] antialiased selection:bg-teal-600 selection:text-white">
    
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content Area (100% Full Width & Fluid) -->
    <div class="flex-1 lg:pl-64 xl:pl-72 flex flex-col min-w-0 transition-all">
        
        <!-- Header / Topbar -->
        <?php include 'components/header.php'; ?>

        <!-- Dashboard Content Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full space-y-6">

            <!-- 1. Premium Hero Header Banner with Live Telemetry -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-brand-900 text-white p-6 sm:p-8 border border-slate-700/50 shadow-sm">
                <!-- Ambient Subtle Background Glow -->
                <div class="absolute -right-16 -top-16 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/3 -bottom-20 w-64 h-64 bg-brand-400/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Left: Greetings & Status -->
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/10 text-xs font-semibold text-teal-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Live Travel Engine v2.4</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold font-space tracking-tight text-white">
                            Executive Dashboard 👋
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                            Welcome back, <span class="text-teal-300 font-semibold"><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin Manager'; ?></span>. Here is today's real-time sales velocity, guide reservations, and travel inquiries.
                        </p>
                    </div>

                    <!-- Right: Quick Action Controls & Mini Stats Pill -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="hidden sm:flex items-center gap-4 px-4 py-2 bg-white/10 backdrop-blur rounded-2xl border border-white/10">
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">Target Pace</div>
                                <div class="text-sm font-bold text-emerald-400">88.4% Achieved</div>
                            </div>
                            <div class="h-6 w-px bg-white/10"></div>
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">Active Tours</div>
                                <div class="text-sm font-bold text-white">12 Departing Today</div>
                            </div>
                        </div>

                        <a href="setup.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur text-white text-xs font-semibold rounded-xl border border-white/15 transition-all">
                            <i class="fa-solid fa-database text-teal-300"></i>
                            <span>DB Sync</span>
                        </a>

                        <button onclick="alert('Quick Package Creator launched')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Create Package</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. High-Impact KPI Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- Card 1: Total Revenue -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/90 relative overflow-hidden group hover:border-teal-500/50 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gross Revenue</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900">$<?php echo number_format($totalRevenue); ?></span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +14.2%
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Target: $150,000</span>
                        <span class="text-emerald-600 font-semibold">On Track</span>
                    </div>
                </div>

                <!-- Card 2: Total Bookings -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/90 relative overflow-hidden group hover:border-teal-500/50 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Bookings</span>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-passport"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900"><?php echo number_format($totalBookingsCount); ?></span>
                        <span class="text-xs font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +8.6%
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>38 Pending review</span>
                        <span class="text-amber-600 font-semibold">14 New Today</span>
                    </div>
                </div>

                <!-- Card 3: Active Packages -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/90 relative overflow-hidden group hover:border-teal-500/50 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Packages</span>
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900"><?php echo $totalPackagesCount; ?></span>
                        <span class="text-xs font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full">
                            +4 New
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>32 Int'l · 16 Domestic</span>
                        <span class="text-sky-600 font-semibold">100% Live</span>
                    </div>
                </div>

                <!-- Card 4: Traveler Rating -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/90 relative overflow-hidden group hover:border-teal-500/50 transition-all duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Satisfaction</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold font-space text-slate-900">4.9<span class="text-sm font-normal text-slate-400">/5.0</span></span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fa-solid fa-thumbs-up text-[10px]"></i> 99.2%
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>850+ Verified Reviews</span>
                        <span class="text-emerald-600 font-semibold">Excellent</span>
                    </div>
                </div>
            </div>

            <!-- 3. Rich Analytics & Visual Trend Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                
                <!-- Main Area Chart: Revenue Velocity -->
                <div class="xl:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 flex flex-col justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-bold text-slate-900">Financial Growth & Booking Volume</h2>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700">Real-Time</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">Comparing 2026 performance against previous fiscal period</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-teal-600 text-white">Monthly</button>
                            <button class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">Quarterly</button>
                            <button class="px-3 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">Yearly</button>
                        </div>
                    </div>
                    <div class="h-64 sm:h-72 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Right Side: Top Destination Share & Progress Bars -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-base font-bold text-slate-900">Regional Booking Share</h2>
                            <i class="fa-solid fa-earth-americas text-slate-400"></i>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Top travel hubs by traveler demand</p>
                        
                        <div class="h-44 w-full flex items-center justify-center">
                            <canvas id="destinationChart"></canvas>
                        </div>
                    </div>

                    <!-- Visual Progress List -->
                    <div class="space-y-3 pt-4 border-t border-slate-100 mt-4">
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal-600"></span> Dubai & Middle East</span>
                                <span>38% ($48.8k)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-full rounded-full" style="width: 38%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Bali & South East Asia</span>
                                <span>27% ($34.6k)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: 27%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span> European Scenic Tours</span>
                                <span>20% ($25.6k)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-sky-500 h-full rounded-full" style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Top Performing Packages Highlight Grid -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Featured & Trending Tour Packages</h2>
                        <p class="text-xs text-slate-400">Most requested packages this season</p>
                    </div>
                    <a href="#packages" class="text-xs font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1">
                        <span>View All Packages</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Package 1 -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 flex items-center gap-3.5 hover:border-slate-300 transition-colors">
                        <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=160&h=160&q=80" alt="Dubai" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded">Trending #1</span>
                                <span class="text-xs font-bold text-slate-900">$1,450</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 truncate mt-1">Dubai Luxury Desert Safari</h3>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1">
                                <span><i class="fa-solid fa-clock mr-1"></i>6D/5N</span>
                                <span><i class="fa-solid fa-fire text-amber-500 mr-1"></i>340 Booked</span>
                            </div>
                        </div>
                    </div>

                    <!-- Package 2 -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 flex items-center gap-3.5 hover:border-slate-300 transition-colors">
                        <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=160&h=160&q=80" alt="Bali" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Trending #2</span>
                                <span class="text-xs font-bold text-slate-900">$2,100</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 truncate mt-1">Bali Tropical Island Villa</h3>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1">
                                <span><i class="fa-solid fa-clock mr-1"></i>7D/6N</span>
                                <span><i class="fa-solid fa-fire text-amber-500 mr-1"></i>280 Booked</span>
                            </div>
                        </div>
                    </div>

                    <!-- Package 3 -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 flex items-center gap-3.5 hover:border-slate-300 transition-colors">
                        <img src="https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?auto=format&fit=crop&w=160&h=160&q=80" alt="Swiss Alps" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded">Trending #3</span>
                                <span class="text-xs font-bold text-slate-900">$3,850</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 truncate mt-1">Swiss Alps Scenic Train Tour</h3>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1">
                                <span><i class="fa-solid fa-clock mr-1"></i>8D/7N</span>
                                <span><i class="fa-solid fa-fire text-amber-500 mr-1"></i>195 Booked</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Recent Bookings Table Section -->
            <div id="bookings" class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden">
                <!-- Table Action Header -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-slate-900">Recent Customer Bookings</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">Live Telemetry</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Manage reservations, payment verifications & traveler itineraries</p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" placeholder="Search traveler or code..." class="pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl outline-none focus:bg-white focus:border-teal-600 transition-colors w-48 sm:w-60">
                        </div>
                        <button class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-filter text-[10px]"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[700px]">
                        <thead class="bg-slate-50/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-5">Traveler Info</th>
                                <th class="py-3.5 px-5">Tour Package</th>
                                <th class="py-3.5 px-5">Travel Date</th>
                                <th class="py-3.5 px-5">Amount</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $bk): ?>
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-4 px-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs shrink-0">
                                                    <?php echo strtoupper(substr($bk['customer_name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900"><?php echo htmlspecialchars($bk['customer_name']); ?></div>
                                                    <div class="text-[11px] text-slate-400"><?php echo htmlspecialchars($bk['customer_email']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-5">
                                            <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($bk['package_title']); ?></div>
                                            <span class="text-[10px] text-teal-600 font-mono"><?php echo isset($bk['booking_code']) ? htmlspecialchars($bk['booking_code']) : '#GF-BOOK'; ?></span>
                                        </td>
                                        <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                                            <i class="fa-regular fa-calendar mr-1 text-[11px] text-slate-400"></i>
                                            <?php echo htmlspecialchars($bk['travel_date']); ?>
                                        </td>
                                        <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap">
                                            $<?php echo number_format($bk['amount'], 2); ?>
                                        </td>
                                        <td class="py-4 px-5 whitespace-nowrap">
                                            <?php
                                            $status = strtolower($bk['status']);
                                            $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                            if ($status === 'confirmed') $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                                            elseif ($status === 'pending') $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200/80';
                                            elseif ($status === 'completed') $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200/80';
                                            ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border <?php echo $badgeClass; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $status === 'confirmed' ? 'bg-emerald-500' : ($status === 'pending' ? 'bg-amber-500' : 'bg-sky-500'); ?>"></span>
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button title="View Details" onclick="alert('Viewing booking details for <?php echo htmlspecialchars($bk['customer_name']); ?>')" class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition-colors">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                                <button title="Edit Record" class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Static Default Rows -->
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80" alt="Arjun" class="w-8 h-8 rounded-full object-cover shrink-0">
                                            <div>
                                                <div class="font-bold text-slate-900">Arjun Mehta</div>
                                                <div class="text-[11px] text-slate-400">arjun.m@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="font-semibold text-slate-800">Dubai Desert Safari & Burj Khalifa Experience</div>
                                        <span class="text-[10px] text-teal-600 font-mono">#GF-1001</span>
                                    </td>
                                    <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                                        <i class="fa-regular fa-calendar mr-1 text-[11px] text-slate-400"></i> Oct 18, 2026
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap">$1,450.00</td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Confirmed
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <button class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-teal-600"><i class="fa-regular fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=80&h=80&q=80" alt="Sophia" class="w-8 h-8 rounded-full object-cover shrink-0">
                                            <div>
                                                <div class="font-bold text-slate-900">Sophia Martinez</div>
                                                <div class="text-[11px] text-slate-400">sophia.m@gmail.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="font-semibold text-slate-800">Bali Luxury Villa 7D/6N Tropical Getaway</div>
                                        <span class="text-[10px] text-teal-600 font-mono">#GF-1002</span>
                                    </td>
                                    <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                                        <i class="fa-regular fa-calendar mr-1 text-[11px] text-slate-400"></i> Nov 02, 2026
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap">$2,100.00</td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <button class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-teal-600"><i class="fa-regular fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=80&h=80&q=80" alt="David" class="w-8 h-8 rounded-full object-cover shrink-0">
                                            <div>
                                                <div class="font-bold text-slate-900">David Wilson</div>
                                                <div class="text-[11px] text-slate-400">david.w@corp.org</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="font-semibold text-slate-800">Swiss Alps Ski & Scenic Train Tour</div>
                                        <span class="text-[10px] text-teal-600 font-mono">#GF-1003</span>
                                    </td>
                                    <td class="py-4 px-5 text-slate-500 whitespace-nowrap">
                                        <i class="fa-regular fa-calendar mr-1 text-[11px] text-slate-400"></i> Dec 15, 2026
                                    </td>
                                    <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap">$3,850.00</td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Confirmed
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <button class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-teal-600"><i class="fa-regular fa-eye"></i></button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination & Live Indicator -->
                <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Showing live active customer bookings</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">Prev</button>
                        <button class="px-2.5 py-1 rounded-lg bg-teal-600 text-white font-bold">1</button>
                        <button class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">2</button>
                        <button class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">Next</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Chart.js Scripts with Smooth Gradients -->
<script>
    // 1. Revenue Line Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 280);
    revenueGradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
    revenueGradient.addColorStop(1, 'rgba(13, 148, 136, 0.0)');

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [
                {
                    label: 'Revenue ($)',
                    data: [65000, 78000, 92000, 89000, 112000, <?php echo $totalRevenue; ?>],
                    borderColor: '#0d9488',
                    backgroundColor: revenueGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0d9488',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Previous Period ($)',
                    data: [52000, 61000, 74000, 70000, 88000, 96000],
                    borderColor: '#e2e8f0',
                    borderDash: [4, 4],
                    borderWidth: 2,
                    fill: false,
                    tension: 0.35,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: { 
                    grid: { color: '#f1f5f9' }, 
                    ticks: { 
                        callback: v => '$' + (v/1000) + 'k', 
                        font: { size: 11 }, 
                        color: '#94a3b8' 
                    } 
                }
            }
        }
    });

    // 2. Destination Doughnut Chart
    const ctxDest = document.getElementById('destinationChart').getContext('2d');
    new Chart(ctxDest, {
        type: 'doughnut',
        data: {
            labels: ['Dubai & UAE', 'Bali & Asia', 'Europe Scenic', 'Domestic Tours'],
            datasets: [{
                data: [38, 27, 20, 15],
                backgroundColor: ['#0d9488', '#10b981', '#0ea5e9', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 8,
                    cornerRadius: 8
                }
            }
        }
    });
</script>

<?php include 'components/footer.php'; ?>
