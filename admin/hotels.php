<?php
/**
 * Admin Hotels & Resorts Management Controller
 * Comprehensive hotel manager with real location search (OpenStreetMap/Google), 
 * dynamic room builder, amenities checklists, image galleries, and pricing controls.
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

// Ensure upload directory exists
$uploadDir = __DIR__ . '/../uploads/hotels/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

// 1. HANDLE POST ACTIONS (Create / Edit Hotel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_hotel'])) {
    if ($pdo) {
        try {
            $hotelId = isset($_POST['hotel_id']) && (int)$_POST['hotel_id'] > 0 ? (int)$_POST['hotel_id'] : null;
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }
            $propertyType = trim($_POST['property_type'] ?? 'Hotel');
            $starRating = (float)($_POST['star_rating'] ?? 5.0);
            $city = trim($_POST['city'] ?? '');
            $state = trim($_POST['state'] ?? '');
            $country = trim($_POST['country'] ?? 'India');
            $address = trim($_POST['address'] ?? '');
            $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
            $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
            $startingPrice = (float)($_POST['starting_price'] ?? 0);
            $originalPrice = (float)($_POST['original_price'] ?? 0);
            $discountPercent = (int)($_POST['discount_percent'] ?? 0);
            $badge = trim($_POST['badge'] ?? '5★ Luxury');
            $status = trim($_POST['status'] ?? 'active');
            $checkinTime = trim($_POST['checkin_time'] ?? '02:00 PM');
            $checkoutTime = trim($_POST['checkout_time'] ?? '11:00 AM');
            $description = trim($_POST['description'] ?? '');
            $policies = trim($_POST['policies'] ?? 'Free Cancellation up to 24 hours before check-in.');

            // Process Amenities Array to JSON
            $amenitiesPost = isset($_POST['amenities']) && is_array($_POST['amenities']) ? $_POST['amenities'] : [];
            $amenitiesJson = json_encode($amenitiesPost, JSON_UNESCAPED_UNICODE);

            // Handle Featured Image
            $featuredImage = trim($_POST['existing_featured_image'] ?? '');
            if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['featured_image_file']['tmp_name'];
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['featured_image_file']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $featuredImage = 'uploads/hotels/' . $fileName;
                }
            } elseif (!empty($_POST['featured_image_url'])) {
                $featuredImage = trim($_POST['featured_image_url']);
            }

            if (empty($name)) {
                throw new Exception("Hotel Name is required.");
            }

            if ($hotelId) {
                // Update Hotel
                $sql = "UPDATE `hotels` SET 
                    `name` = ?, `slug` = ?, `star_rating` = ?, `property_type` = ?,
                    `city` = ?, `state` = ?, `country` = ?, `address` = ?,
                    `latitude` = ?, `longitude` = ?, `featured_image` = ?,
                    `starting_price` = ?, `original_price` = ?, `discount_percent` = ?,
                    `amenities` = ?, `description` = ?, `policies` = ?,
                    `checkin_time` = ?, `checkout_time` = ?, `badge` = ?, `status` = ?
                    WHERE `id` = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $name, $slug, $starRating, $propertyType,
                    $city, $state, $country, $address,
                    $latitude, $longitude, $featuredImage,
                    $startingPrice, $originalPrice, $discountPercent,
                    $amenitiesJson, $description, $policies,
                    $checkinTime, $checkoutTime, $badge, $status,
                    $hotelId
                ]);
                $alertMessage = "Hotel '{$name}' updated successfully!";
            } else {
                // Insert New Hotel
                $sql = "INSERT INTO `hotels` (
                    `name`, `slug`, `star_rating`, `property_type`,
                    `city`, `state`, `country`, `address`,
                    `latitude`, `longitude`, `featured_image`,
                    `starting_price`, `original_price`, `discount_percent`,
                    `amenities`, `description`, `policies`,
                    `checkin_time`, `checkout_time`, `badge`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $name, $slug, $starRating, $propertyType,
                    $city, $state, $country, $address,
                    $latitude, $longitude, $featuredImage,
                    $startingPrice, $originalPrice, $discountPercent,
                    $amenitiesJson, $description, $policies,
                    $checkinTime, $checkoutTime, $badge, $status
                ]);
                $hotelId = (int)$pdo->lastInsertId();
                $alertMessage = "Hotel '{$name}' created successfully!";
            }

            // Save Dynamic Rooms
            if (isset($_POST['rooms']) && is_array($_POST['rooms'])) {
                // Delete existing rooms if editing
                $pdo->prepare("DELETE FROM `hotel_rooms` WHERE `hotel_id` = ?")->execute([$hotelId]);
                $roomStmt = $pdo->prepare("INSERT INTO `hotel_rooms` (
                    `hotel_id`, `room_name`, `room_type`, `price_per_night`, `original_price`, 
                    `max_adults`, `max_children`, `bed_type`, `room_size`, `meal_plan`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')");

                foreach ($_POST['rooms'] as $room) {
                    $rName = trim($room['room_name'] ?? '');
                    if (!empty($rName)) {
                        $rType = trim($room['room_type'] ?? 'Deluxe');
                        $rPrice = (float)($room['price_per_night'] ?? $startingPrice);
                        $rOrig = (float)($room['original_price'] ?? $originalPrice);
                        $rAdults = (int)($room['max_adults'] ?? 2);
                        $rChildren = (int)($room['max_children'] ?? 1);
                        $rBed = trim($room['bed_type'] ?? '1 King Bed');
                        $rSize = trim($room['room_size'] ?? '350 sq ft');
                        $rMeal = trim($room['meal_plan'] ?? 'Free Breakfast Included');

                        $roomStmt->execute([
                            $hotelId, $rName, $rType, $rPrice, $rOrig,
                            $rAdults, $rChildren, $rBed, $rSize, $rMeal
                        ]);
                    }
                }
            }

            // Handle Gallery Uploads
            if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
                $imgStmt = $pdo->prepare("INSERT INTO `hotel_images` (`hotel_id`, `image_url`, `caption`, `sort_order`) VALUES (?, ?, ?, ?)");
                $totalFiles = count($_FILES['gallery_images']['name']);
                for ($i = 0; $i < $totalFiles; $i++) {
                    if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                        $gTmp = $_FILES['gallery_images']['tmp_name'][$i];
                        $gName = time() . '_' . $i . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['gallery_images']['name'][$i]);
                        $gTarget = $uploadDir . $gName;
                        if (move_uploaded_file($gTmp, $gTarget)) {
                            $imgStmt->execute([$hotelId, 'uploads/hotels/' . $gName, $name . ' Photo', $i + 1]);
                        }
                    }
                }
            }

        } catch (Exception $e) {
            $alertMessage = "Error saving hotel: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// 2. HANDLE GET ACTIONS (Delete / Toggle Status)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $targetId = (int)$_GET['id'];
    $action = $_GET['action'];
    if ($pdo && $targetId > 0) {
        try {
            if ($action === 'delete') {
                $pdo->prepare("DELETE FROM `hotel_images` WHERE `hotel_id` = ?")->execute([$targetId]);
                $pdo->prepare("DELETE FROM `hotel_rooms` WHERE `hotel_id` = ?")->execute([$targetId]);
                $pdo->prepare("DELETE FROM `hotels` WHERE `id` = ?")->execute([$targetId]);
                $alertMessage = "Hotel and its associated rooms have been deleted.";
            } elseif ($action === 'toggle_status') {
                $currStatus = $pdo->prepare("SELECT `status` FROM `hotels` WHERE `id` = ?");
                $currStatus->execute([$targetId]);
                $st = $currStatus->fetchColumn();
                $newStatus = ($st === 'active') ? 'inactive' : 'active';
                $pdo->prepare("UPDATE `hotels` SET `status` = ? WHERE `id` = ?")->execute([$newStatus, $targetId]);
                $alertMessage = "Hotel status updated to " . strtoupper($newStatus) . ".";
            }
        } catch (Exception $e) {
            $alertMessage = "Action failed: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// 3. EDIT FETCH (if editing specific hotel)
$editHotel = null;
$editRooms = [];
$editImages = [];
if (isset($_GET['edit_id']) && (int)$_GET['edit_id'] > 0) {
    $editId = (int)$_GET['edit_id'];
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM `hotels` WHERE `id` = ?");
        $stmt->execute([$editId]);
        $editHotel = $stmt->fetch();
        if ($editHotel) {
            $rStmt = $pdo->prepare("SELECT * FROM `hotel_rooms` WHERE `hotel_id` = ? ORDER BY `id` ASC");
            $rStmt->execute([$editId]);
            $editRooms = $rStmt->fetchAll();

            $iStmt = $pdo->prepare("SELECT * FROM `hotel_images` WHERE `hotel_id` = ? ORDER BY `sort_order` ASC");
            $iStmt->execute([$editId]);
            $editImages = $iStmt->fetchAll();
        }
    }
}

// 4. FETCH HOTELS LIST & STATS
$hotels = [];
$totalHotels = 0;
$activeHotels = 0;
$totalRoomsCount = 0;
$cityCount = 0;

if ($pdo) {
    try {
        $totalHotels = (int)$pdo->query("SELECT COUNT(*) FROM `hotels`")->fetchColumn();
        $activeHotels = (int)$pdo->query("SELECT COUNT(*) FROM `hotels` WHERE `status` = 'active'")->fetchColumn();
        $totalRoomsCount = (int)$pdo->query("SELECT COUNT(*) FROM `hotel_rooms`")->fetchColumn();
        $cityCount = (int)$pdo->query("SELECT COUNT(DISTINCT `city`) FROM `hotels` WHERE `city` != ''")->fetchColumn();

        $search = trim($_GET['q'] ?? '');
        $filterCity = trim($_GET['city'] ?? '');
        
        $query = "SELECT h.*, (SELECT COUNT(*) FROM `hotel_rooms` hr WHERE hr.hotel_id = h.id) as room_count FROM `hotels` h WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (h.name LIKE ? OR h.city LIKE ? OR h.property_type LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($filterCity)) {
            $query .= " AND h.city = ?";
            $params[] = $filterCity;
        }

        $query .= " ORDER BY h.id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $hotels = $stmt->fetchAll();

    } catch (Exception $e) {
        $hotels = [];
    }
}

// Standard available amenities catalog
$standardAmenities = [
    ['key' => 'Free Breakfast', 'icon' => 'fa-solid fa-mug-saucer', 'label' => 'Free Breakfast'],
    ['key' => 'Swimming Pool', 'icon' => 'fa-solid fa-water-ladder', 'label' => 'Swimming Pool'],
    ['key' => 'Free High-Speed Wi-Fi', 'icon' => 'fa-solid fa-wifi', 'label' => 'Free High-Speed Wi-Fi'],
    ['key' => 'Spa & Wellness', 'icon' => 'fa-solid fa-spa', 'label' => 'Spa & Wellness'],
    ['key' => 'Beachfront / Ocean View', 'icon' => 'fa-solid fa-umbrella-beach', 'label' => 'Beachfront / Ocean View'],
    ['key' => 'Mountain / Valley View', 'icon' => 'fa-solid fa-mountain-sun', 'label' => 'Mountain / Valley View'],
    ['key' => 'Fitness Center / Gym', 'icon' => 'fa-solid fa-dumbbell', 'label' => 'Fitness Center / Gym'],
    ['key' => 'Fine Dining Restaurant', 'icon' => 'fa-solid fa-utensils', 'label' => 'Fine Dining Restaurant'],
    ['key' => 'Bar & Lounge', 'icon' => 'fa-solid fa-martini-glass-citrus', 'label' => 'Bar & Lounge'],
    ['key' => 'Airport Shuttle Service', 'icon' => 'fa-solid fa-van-shuttle', 'label' => 'Airport Shuttle'],
    ['key' => 'Air Conditioning', 'icon' => 'fa-solid fa-snowflake', 'label' => 'Air Conditioning'],
    ['key' => '24x7 Room Service', 'icon' => 'fa-solid fa-bell-concierge', 'label' => '24x7 Room Service'],
    ['key' => 'Free Valet Parking', 'icon' => 'fa-solid fa-square-parking', 'label' => 'Free Valet Parking'],
    ['key' => 'Jacuzzi / Bathtub', 'icon' => 'fa-solid fa-hot-tub-person', 'label' => 'Jacuzzi / Bathtub']
];
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels & Resorts Management - GuideFlux Admin</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef6ff',
                            100: '#d9ebff',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#172554',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        space: ['"Space Grotesk"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .suggestion-item:hover { background-color: #f1f5f9; cursor: pointer; }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased">
    
    <div class="min-h-full flex">
        <!-- Admin Sidebar -->
        <?php include_once __DIR__ . '/components/sidebar.php'; ?>

        <!-- Main Workspace -->
        <main class="flex-1 lg:pl-64 xl:pl-72 flex flex-col min-w-0 bg-slate-50">
            
            <!-- Top Navigation Bar -->
            <header class="h-16 xl:h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="text-lg xl:text-xl font-extrabold text-slate-900 tracking-tight">Hotels &amp; Luxury Stays</h1>
                        <p class="text-xs text-slate-400 font-medium hidden sm:block">Real API Location Search • Dynamic Room Types • Photo Galleries • Pricing</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="openHotelModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-bold shadow-xs transition-colors">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Hotel</span>
                    </button>
                    <a href="../index.php#hotels-section" target="_blank" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:text-brand-600 transition-colors" title="View Frontend Stays">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                </div>
            </header>

            <!-- Main Content Canvas -->
            <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl w-full mx-auto">
                
                <!-- Status Alerts -->
                <?php if (!empty($alertMessage)): ?>
                    <div class="p-4 rounded-xl flex items-center justify-between <?= $alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid <?= $alertType === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-rose-600' ?> text-lg"></i>
                            <span class="text-sm font-semibold"><?= htmlspecialchars($alertMessage) ?></span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Stat Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Hotels</span>
                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($totalHotels) ?></div>
                        <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                            <span><?= number_format($activeHotels) ?> Published Active</span>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Configured Rooms</span>
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-bed"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($totalRoomsCount) ?></div>
                        <div class="text-[11px] text-slate-400 font-medium mt-1">Multi-Category Suites</div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Destinations</span>
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($cityCount) ?></div>
                        <div class="text-[11px] text-slate-400 font-medium mt-1">Cities &amp; Locations</div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Location API</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                        </div>
                        <div class="text-base font-extrabold text-slate-900">OpenStreetMap / Google</div>
                        <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-bolt text-[9px]"></i>
                            <span>Real-Time Search Active</span>
                        </div>
                    </div>
                </div>

                <!-- Hotel Listings Card -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    
                    <!-- Search & Filter Controls -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <form method="GET" class="w-full sm:w-auto flex-1 flex items-center gap-3">
                            <div class="relative flex-1 sm:max-w-xs">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search hotel name, city, category..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                                Search
                            </button>
                            <?php if (!empty($_GET['q']) || !empty($_GET['city'])): ?>
                                <a href="hotels.php" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold">Clear</a>
                            <?php endif; ?>
                        </form>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <span class="text-xs text-slate-500 font-medium">Found <strong><?= count($hotels) ?></strong> hotel(s)</span>
                        </div>
                    </div>

                    <!-- Hotels Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-4">Hotel / Property</th>
                                    <th class="py-3.5 px-4">Location &amp; Coordinates</th>
                                    <th class="py-3.5 px-4">Rating &amp; Type</th>
                                    <th class="py-3.5 px-4">Starting Price</th>
                                    <th class="py-3.5 px-4 text-center">Rooms</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (empty($hotels)): ?>
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-slate-400">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                                <i class="fa-solid fa-hotel"></i>
                                            </div>
                                            <p class="font-bold text-slate-600 text-sm">No hotels found</p>
                                            <p class="text-xs text-slate-400 mt-1">Click "Add New Hotel" to create your first hotel listing with live API location search.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($hotels as $h): ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <!-- Property & Image -->
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-14 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                                        <img src="<?= htmlspecialchars(!empty($h['featured_image']) ? (strpos($h['featured_image'], 'http') === 0 ? $h['featured_image'] : '../' . $h['featured_image']) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=200&q=80') ?>" alt="<?= htmlspecialchars($h['name']) ?>" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <div class="font-extrabold text-slate-900 text-sm hover:text-brand-600 transition-colors">
                                                            <?= htmlspecialchars($h['name']) ?>
                                                        </div>
                                                        <div class="text-[11px] text-slate-400 font-medium">
                                                            <?= htmlspecialchars($h['badge'] ?? 'Featured Stay') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Location & Coordinates -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-location-dot text-brand-600 text-[11px]"></i>
                                                    <span><?= htmlspecialchars($h['city'] ?: 'City Not Set') ?></span>
                                                    <?php if (!empty($h['country'])): ?>
                                                        <span class="text-slate-400 font-normal">, <?= htmlspecialchars($h['country']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-[10px] text-slate-400 truncate max-w-xs" title="<?= htmlspecialchars($h['address']) ?>">
                                                    <?= htmlspecialchars($h['address'] ?: 'Manual address') ?>
                                                </div>
                                            </td>

                                            <!-- Rating & Property Type -->
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-amber-700 font-black text-[10px] flex items-center gap-1">
                                                        <i class="fa-solid fa-star text-[9px] text-amber-500"></i>
                                                        <?= htmlspecialchars($h['star_rating']) ?>★
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px]">
                                                        <?= htmlspecialchars($h['property_type']) ?>
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Pricing -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-black text-slate-900 text-sm">
                                                    ₹<?= number_format($h['starting_price']) ?>
                                                    <span class="text-[10px] font-normal text-slate-400">/ night</span>
                                                </div>
                                                <?php if ($h['original_price'] > $h['starting_price']): ?>
                                                    <div class="text-[10px] text-slate-400 line-through">
                                                        ₹<?= number_format($h['original_price']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Room Types Count -->
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-50 border border-purple-200 text-purple-700 font-bold text-[11px]">
                                                    <i class="fa-solid fa-bed text-[10px]"></i>
                                                    <?= (int)$h['room_count'] ?> Types
                                                </span>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-3.5 px-4">
                                                <a href="hotels.php?action=toggle_status&id=<?= $h['id'] ?>" title="Click to toggle status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all <?= $h['status'] === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $h['status'] === 'active' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                                    <span><?= strtoupper($h['status']) ?></span>
                                                </a>
                                            </td>

                                            <!-- Actions -->
                                            <td class="py-3.5 px-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="hotels.php?edit_id=<?= $h['id'] ?>" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-300 flex items-center justify-center text-slate-600 transition-colors" title="Edit Hotel & Rooms">
                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                    </a>
                                                    <a href="hotels.php?action=delete&id=<?= $h['id'] ?>" onclick="return confirm('Are you sure you want to delete this hotel and all its rooms?');" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 flex items-center justify-center text-slate-400 transition-colors" title="Delete Hotel">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ========================================================
         MODAL / DRAWER: ADD & EDIT HOTEL WITH STEP-BY-STEP TABS
         ======================================================== -->
    <div id="hotelModal" class="<?= $editHotel ? 'flex' : 'hidden' ?> fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-base">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight" id="modalTitle">
                            <?= $editHotel ? 'Edit Hotel: ' . htmlspecialchars($editHotel['name']) : 'Add New Hotel & Luxury Resort' ?>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Real-time Location API • Custom Room Plans • Amenities • Gallery</p>
                    </div>
                </div>
                <button type="button" onclick="closeHotelModal()" class="w-8 h-8 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Tab Switcher Bar -->
            <div class="px-6 py-2 border-b border-slate-200 bg-white flex items-center gap-2 overflow-x-auto scrollbar-none shrink-0">
                <button type="button" onclick="switchHotelTab('tab-basic')" class="tab-btn active-tab px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-brand-600 text-white border-brand-600" data-target="tab-basic">
                    <i class="fa-solid fa-map-pin mr-1.5"></i> 1. Location &amp; Basic Info
                </button>
                <button type="button" onclick="switchHotelTab('tab-pricing')" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="tab-pricing">
                    <i class="fa-solid fa-tag mr-1.5"></i> 2. Pricing &amp; Stars
                </button>
                <button type="button" onclick="switchHotelTab('tab-amenities')" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="tab-amenities">
                    <i class="fa-solid fa-spa mr-1.5"></i> 3. Amenities Checklist
                </button>
                <button type="button" onclick="switchHotelTab('tab-rooms')" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="tab-rooms">
                    <i class="fa-solid fa-bed mr-1.5"></i> 4. Room Types (<span id="roomsBadgeCount"><?= count($editRooms) ?: '1' ?></span>)
                </button>
                <button type="button" onclick="switchHotelTab('tab-gallery')" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="tab-gallery">
                    <i class="fa-solid fa-images mr-1.5"></i> 5. Images &amp; Policies
                </button>
            </div>

            <!-- Form Container -->
            <form action="hotels.php" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                <input type="hidden" name="save_hotel" value="1">
                <input type="hidden" name="hotel_id" value="<?= $editHotel ? $editHotel['id'] : '' ?>">
                <input type="hidden" name="existing_featured_image" value="<?= $editHotel ? htmlspecialchars($editHotel['featured_image']) : '' ?>">

                <!-- ================= TAB 1: LOCATION & BASIC INFO ================= -->
                <div id="tab-basic" class="tab-content space-y-5">
                    
                    <!-- Real-Time Location Search Box (OpenStreetMap / Google) -->
                    <div class="p-4 rounded-2xl bg-brand-50/60 border border-brand-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black uppercase tracking-wider text-brand-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-crosshairs text-brand-600"></i>
                                <span>Live Location / Hotel Search (OpenStreetMap &amp; Google)</span>
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                Real-Time Autocomplete
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Type hotel name or city (e.g. <em>"Taj Exotica Goa"</em>, <em>"Manali Resort"</em>, <em>"Jaipur Palace"</em>). Click any suggestion to auto-fill address, city, and GPS coordinates.
                        </p>
                        
                        <div class="relative">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" id="locationSearchInput" autocomplete="off" placeholder="Start typing location, hotel name, landmark or city..." class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-brand-300 bg-white text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <span id="searchSpinner" class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-brand-600">
                                    <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                                </span>
                            </div>

                            <!-- Autocomplete Dropdown List -->
                            <div id="locationSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-slate-100 custom-scrollbar">
                                <!-- Dynamic Items -->
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Basic Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotel / Property Name *</label>
                            <input type="text" name="name" id="hotelNameInput" required value="<?= $editHotel ? htmlspecialchars($editHotel['name']) : '' ?>" placeholder="e.g. Taj Fort Aguada Resort & Spa" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Property Type</label>
                            <select name="property_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                <?php
                                $types = ['Luxury Resort', '5★ Palace', '4★ Boutique Hotel', 'Heritage Haveli', 'Beach Villa', 'Alpine Mountain Chalet', 'City Business Hotel', 'Eco Wildlife Sanctuary'];
                                foreach ($types as $t) {
                                    $sel = ($editHotel && $editHotel['property_type'] === $t) ? 'selected' : '';
                                    echo "<option value=\"{$t}\" {$sel}>{$t}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">City / Destination *</label>
                            <input type="text" name="city" id="hotelCityInput" required value="<?= $editHotel ? htmlspecialchars($editHotel['city']) : '' ?>" placeholder="e.g. Goa, Manali, Jaipur, Udaipur" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">State / Province</label>
                            <input type="text" name="state" id="hotelStateInput" value="<?= $editHotel ? htmlspecialchars($editHotel['state']) : '' ?>" placeholder="e.g. Goa, Himachal Pradesh, Rajasthan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Physical Address / Landmark</label>
                            <textarea name="address" id="hotelAddressInput" rows="2" placeholder="e.g. Sinquerim Beach, Candolim, North Goa, 403515" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= $editHotel ? htmlspecialchars($editHotel['address']) : '' ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Latitude (GPS)</label>
                            <input type="text" name="latitude" id="hotelLatInput" value="<?= $editHotel ? htmlspecialchars($editHotel['latitude']) : '' ?>" placeholder="e.g. 15.4989" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-mono text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Longitude (GPS)</label>
                            <input type="text" name="longitude" id="hotelLonInput" value="<?= $editHotel ? htmlspecialchars($editHotel['longitude']) : '' ?>" placeholder="e.g. 73.7684" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-mono text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 2: PRICING & STAR RATING ================= -->
                <div id="tab-pricing" class="tab-content hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Starting Price (₹/Night) *</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="starting_price" required value="<?= $editHotel ? htmlspecialchars($editHotel['starting_price']) : '7499' ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Original / Strikethrough Price (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="original_price" value="<?= $editHotel ? htmlspecialchars($editHotel['original_price']) : '9999' ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Star Rating</label>
                            <select name="star_rating" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-amber-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                <option value="5.0" <?= ($editHotel && (float)$editHotel['star_rating'] == 5.0) ? 'selected' : '' ?>>5.0 ★ Luxury Superior</option>
                                <option value="4.9" <?= ($editHotel && (float)$editHotel['star_rating'] == 4.9) ? 'selected' : '' ?>>4.9 ★ Exceptional</option>
                                <option value="4.8" <?= ($editHotel && (float)$editHotel['star_rating'] == 4.8) ? 'selected' : '' ?>>4.8 ★ Superb</option>
                                <option value="4.5" <?= ($editHotel && (float)$editHotel['star_rating'] == 4.5) ? 'selected' : '' ?>>4.5 ★ Excellent</option>
                                <option value="4.0" <?= ($editHotel && (float)$editHotel['star_rating'] == 4.0) ? 'selected' : '' ?>>4.0 ★ Great</option>
                                <option value="3.5" <?= ($editHotel && (float)$editHotel['star_rating'] == 3.5) ? 'selected' : '' ?>>3.5 ★ Standard Comfort</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge / Highlight Tag</label>
                            <input type="text" name="badge" value="<?= $editHotel ? htmlspecialchars($editHotel['badge']) : '5★ Luxury Beachfront' ?>" placeholder="e.g. 5★ Luxury, Bestseller, Mountain View" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Publishing Status</label>
                            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                <option value="active" <?= ($editHotel && $editHotel['status'] === 'active') ? 'selected' : '' ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?= ($editHotel && $editHotel['status'] === 'inactive') ? 'selected' : '' ?>>Draft / Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Standard Check-In Time</label>
                            <input type="text" name="checkin_time" value="<?= $editHotel ? htmlspecialchars($editHotel['checkin_time']) : '02:00 PM' ?>" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-800">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Standard Check-Out Time</label>
                            <input type="text" name="checkout_time" value="<?= $editHotel ? htmlspecialchars($editHotel['checkout_time']) : '11:00 AM' ?>" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-800">
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 3: AMENITIES CHECKLIST ================= -->
                <div id="tab-amenities" class="tab-content hidden space-y-4">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-1">Select Property Amenities</h4>
                        <p class="text-xs text-slate-400">These will appear in the continuous amenities slider on the live website cards.</p>
                    </div>

                    <?php
                    $savedAmenities = [];
                    if ($editHotel && !empty($editHotel['amenities'])) {
                        $decoded = json_decode($editHotel['amenities'], true);
                        if (is_array($decoded)) {
                            foreach ($decoded as $am) {
                                if (is_array($am) && isset($am['name'])) {
                                    $savedAmenities[] = $am['name'];
                                } elseif (is_string($am)) {
                                    $savedAmenities[] = $am;
                                }
                            }
                        }
                    } else {
                        // Default selections
                        $savedAmenities = ['Free Breakfast', 'Swimming Pool', 'Free High-Speed Wi-Fi', 'Spa & Wellness', 'Fine Dining Restaurant'];
                    }
                    ?>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <?php foreach ($standardAmenities as $idx => $am): ?>
                            <?php $isChecked = in_array($am['label'], $savedAmenities) || in_array($am['key'], $savedAmenities); ?>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-brand-400 transition cursor-pointer select-none">
                                <input type="checkbox" name="amenities[<?= $idx ?>][name]" value="<?= htmlspecialchars($am['label']) ?>" <?= $isChecked ? 'checked' : '' ?> class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                <input type="hidden" name="amenities[<?= $idx ?>][icon]" value="<?= htmlspecialchars($am['icon']) ?>">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="<?= htmlspecialchars($am['icon']) ?> text-brand-600 text-xs shrink-0"></i>
                                    <span class="text-xs font-semibold text-slate-800 truncate"><?= htmlspecialchars($am['label']) ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ================= TAB 4: DYNAMIC ROOM TYPES BUILDER ================= -->
                <div id="tab-rooms" class="tab-content hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700">Room Types &amp; Nightly Tariffs</h4>
                            <p class="text-xs text-slate-400">Add multiple room types (e.g. Deluxe Room, Sea View Suite, Presidential Villa).</p>
                        </div>
                        <button type="button" onclick="addRoomRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 text-xs font-bold transition">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Room Type</span>
                        </button>
                    </div>

                    <!-- Room Rows Container -->
                    <div id="roomsContainer" class="space-y-3">
                        <?php if (!empty($editRooms)): ?>
                            <?php foreach ($editRooms as $rIdx => $r): ?>
                                <div class="room-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-purple-900 flex items-center gap-1.5">
                                            <i class="fa-solid fa-bed text-purple-600"></i>
                                            <span>Room Plan #<span class="room-num"><?= $rIdx + 1 ?></span></span>
                                        </span>
                                        <button type="button" onclick="removeRoomRow(this)" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-2 py-1 rounded-lg hover:bg-rose-50 transition">
                                            <i class="fa-solid fa-trash-can"></i> Remove
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Name</label>
                                            <input type="text" name="rooms[<?= $rIdx ?>][room_name]" required value="<?= htmlspecialchars($r['room_name']) ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Type / Category</label>
                                            <input type="text" name="rooms[<?= $rIdx ?>][room_type]" value="<?= htmlspecialchars($r['room_type']) ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Price / Night (₹)</label>
                                            <input type="number" name="rooms[<?= $rIdx ?>][price_per_night]" required value="<?= htmlspecialchars($r['price_per_night']) ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-black text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Bed Configuration</label>
                                            <input type="text" name="rooms[<?= $rIdx ?>][bed_type]" value="<?= htmlspecialchars($r['bed_type']) ?>" placeholder="1 King Bed / 2 Twin Beds" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Max Guests (Adults)</label>
                                            <input type="number" name="rooms[<?= $rIdx ?>][max_adults]" value="<?= htmlspecialchars($r['max_adults']) ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Area (sq ft)</label>
                                            <input type="text" name="rooms[<?= $rIdx ?>][room_size]" value="<?= htmlspecialchars($r['room_size']) ?>" placeholder="e.g. 450 sq ft" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Meal Plan Inclusions</label>
                                            <input type="text" name="rooms[<?= $rIdx ?>][meal_plan]" value="<?= htmlspecialchars($r['meal_plan']) ?>" placeholder="e.g. Free Breakfast & Dinner Included" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default 1 Room Row -->
                            <div class="room-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-purple-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-bed text-purple-600"></i>
                                        <span>Room Plan #<span class="room-num">1</span> (Primary Category)</span>
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Name</label>
                                        <input type="text" name="rooms[0][room_name]" required value="Luxury Deluxe Heritage Room" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Type / Category</label>
                                        <input type="text" name="rooms[0][room_type]" value="Deluxe" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Price / Night (₹)</label>
                                        <input type="number" name="rooms[0][price_per_night]" required value="7499" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-black text-slate-900">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Bed Configuration</label>
                                        <input type="text" name="rooms[0][bed_type]" value="1 King Bed" placeholder="1 King Bed / 2 Twin Beds" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Max Guests (Adults)</label>
                                        <input type="number" name="rooms[0][max_adults]" value="2" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Area (sq ft)</label>
                                        <input type="text" name="rooms[0][room_size]" value="380 sq ft" placeholder="e.g. 380 sq ft" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Meal Plan Inclusions</label>
                                        <input type="text" name="rooms[0][meal_plan]" value="Free Buffet Breakfast Included" placeholder="e.g. Free Buffet Breakfast Included" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ================= TAB 5: IMAGES & POLICIES ================= -->
                <div id="tab-gallery" class="tab-content hidden space-y-4">
                    
                    <!-- Featured Image -->
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700">Primary Featured Image</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Upload Image File</label>
                                <input type="file" name="featured_image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Or Paste Direct Image URL</label>
                                <input type="url" name="featured_image_url" value="<?= ($editHotel && strpos($editHotel['featured_image'], 'http') === 0) ? htmlspecialchars($editHotel['featured_image']) : '' ?>" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-800">
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Gallery Photos -->
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700">Additional Gallery Photos (Multiple)</label>
                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>

                    <!-- Description & Policies -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Property Description &amp; Highlights</label>
                            <textarea name="description" rows="3" placeholder="Describe the property ambiance, views, pool facilities, nearby attractions..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= $editHotel ? htmlspecialchars($editHotel['description']) : '' ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Cancellation Policy &amp; Rules</label>
                            <textarea name="policies" rows="3" placeholder="e.g. Free Cancellation up to 24 hours before check-in. Valid ID proof required." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= $editHotel ? htmlspecialchars($editHotel['policies']) : 'Free Cancellation up to 24 hours before check-in.' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Sticky Footer Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                    <button type="button" onclick="closeHotelModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                        Cancel
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" id="prevTabBtn" onclick="navigateTab(-1)" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold transition hidden">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Previous
                        </button>
                        <button type="button" id="nextTabBtn" onclick="navigateTab(1)" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition">
                            Next Step <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                        <button type="submit" id="saveHotelSubmitBtn" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-black shadow-xs transition hidden">
                            <i class="fa-solid fa-check mr-1.5"></i> Save &amp; Publish Hotel
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- JavaScript for Interactive Controls & Live Autocomplete -->
    <script>
        // Tab switching logic
        const tabList = ['tab-basic', 'tab-pricing', 'tab-amenities', 'tab-rooms', 'tab-gallery'];
        let currentTabIndex = 0;

        function switchHotelTab(targetId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(targetId).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.getAttribute('data-target') === targetId) {
                    btn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
                    btn.classList.add('bg-brand-600', 'text-white', 'border-brand-600');
                } else {
                    btn.classList.remove('bg-brand-600', 'text-white', 'border-brand-600');
                    btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                }
            });

            currentTabIndex = tabList.indexOf(targetId);
            updateNavButtons();
        }

        function navigateTab(direction) {
            currentTabIndex += direction;
            if (currentTabIndex < 0) currentTabIndex = 0;
            if (currentTabIndex >= tabList.length) currentTabIndex = tabList.length - 1;
            switchHotelTab(tabList[currentTabIndex]);
        }

        function updateNavButtons() {
            const prevBtn = document.getElementById('prevTabBtn');
            const nextBtn = document.getElementById('nextTabBtn');
            const submitBtn = document.getElementById('saveHotelSubmitBtn');

            if (currentTabIndex === 0) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }

            if (currentTabIndex === tabList.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function openHotelModal() {
            document.getElementById('hotelModal').classList.remove('hidden');
            document.getElementById('hotelModal').classList.add('flex');
            switchHotelTab('tab-basic');
        }

        function closeHotelModal() {
            document.getElementById('hotelModal').classList.add('hidden');
            document.getElementById('hotelModal').classList.remove('flex');
            if (window.location.search.includes('edit_id=')) {
                window.location.href = 'hotels.php';
            }
        }

        // Add / Remove Room Types Dynamically
        let roomCount = <?= count($editRooms) ?: 1 ?>;
        function addRoomRow() {
            roomCount++;
            const container = document.getElementById('roomsContainer');
            const newIndex = container.children.length;
            
            const div = document.createElement('div');
            div.className = 'room-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative animate-in fade-in duration-150';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-purple-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-bed text-purple-600"></i>
                        <span>Room Plan #<span class="room-num">${newIndex + 1}</span></span>
                    </span>
                    <button type="button" onclick="removeRoomRow(this)" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-2 py-1 rounded-lg hover:bg-rose-50 transition">
                        <i class="fa-solid fa-trash-can"></i> Remove
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Name</label>
                        <input type="text" name="rooms[${newIndex}][room_name]" required placeholder="e.g. Executive Sea View Suite" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Type / Category</label>
                        <input type="text" name="rooms[${newIndex}][room_type]" value="Suite" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Price / Night (₹)</label>
                        <input type="number" name="rooms[${newIndex}][price_per_night]" required value="9499" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-black text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Bed Configuration</label>
                        <input type="text" name="rooms[${newIndex}][bed_type]" value="1 King Bed" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Max Guests (Adults)</label>
                        <input type="number" name="rooms[${newIndex}][max_adults]" value="2" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Room Area (sq ft)</label>
                        <input type="text" name="rooms[${newIndex}][room_size]" value="520 sq ft" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Meal Plan Inclusions</label>
                        <input type="text" name="rooms[${newIndex}][meal_plan]" value="Free Breakfast &amp; Afternoon Tea" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                </div>
            `;
            container.appendChild(div);
            updateRoomCounters();
        }

        function removeRoomRow(btn) {
            const container = document.getElementById('roomsContainer');
            if (container.children.length > 1) {
                btn.closest('.room-row').remove();
                updateRoomCounters();
            } else {
                alert('At least 1 room type must be configured for the hotel.');
            }
        }

        function updateRoomCounters() {
            const rows = document.querySelectorAll('#roomsContainer .room-row');
            rows.forEach((row, idx) => {
                const numEl = row.querySelector('.room-num');
                if (numEl) numEl.textContent = idx + 1;
            });
            document.getElementById('roomsBadgeCount').textContent = rows.length;
        }

        // ========================================================
        // LIVE REAL-TIME LOCATION SEARCH AUTOCOMPLETE (OSM / Google)
        // ========================================================
        const locationInput = document.getElementById('locationSearchInput');
        const suggestionsBox = document.getElementById('locationSuggestions');
        const spinner = document.getElementById('searchSpinner');
        let debounceTimer;

        if (locationInput) {
            locationInput.addEventListener('input', function() {
                const q = this.value.trim();
                clearTimeout(debounceTimer);

                if (q.length < 2) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }

                spinner.classList.remove('hidden');
                debounceTimer = setTimeout(() => {
                    fetch(`../api/places.php?q=${encodeURIComponent(q)}`)
                        .then(res => res.json())
                        .then(data => {
                            spinner.classList.add('hidden');
                            if (data.success && data.data && data.data.length > 0) {
                                suggestionsBox.innerHTML = '';
                                data.data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item p-3 transition flex items-start gap-2.5';
                                    div.innerHTML = `
                                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="fa-solid fa-location-dot text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-900 truncate">${item.name || item.city}</div>
                                            <div class="text-[11px] text-slate-500 truncate">${item.full_address}</div>
                                            <div class="text-[10px] text-brand-600 font-medium mt-0.5 flex items-center gap-2">
                                                <span>City: <strong>${item.city}</strong></span>
                                                ${item.country ? `<span>• ${item.country}</span>` : ''}
                                            </div>
                                        </div>
                                    `;
                                    div.addEventListener('click', () => {
                                        // Auto-fill fields
                                        if (item.name && (!document.getElementById('hotelNameInput').value || document.getElementById('hotelNameInput').value === '')) {
                                            document.getElementById('hotelNameInput').value = item.name;
                                        }
                                        document.getElementById('hotelCityInput').value = item.city || '';
                                        document.getElementById('hotelStateInput').value = item.state || '';
                                        document.getElementById('hotelAddressInput').value = item.full_address || '';
                                        document.getElementById('hotelLatInput').value = item.lat || '';
                                        document.getElementById('hotelLonInput').value = item.lon || '';
                                        locationInput.value = item.name || item.full_address;
                                        suggestionsBox.classList.add('hidden');
                                    });
                                    suggestionsBox.appendChild(div);
                                });
                                suggestionsBox.classList.remove('hidden');
                            } else {
                                suggestionsBox.innerHTML = '<div class="p-3 text-xs text-slate-400 text-center font-medium">No map locations found. You can still type details manually.</div>';
                                suggestionsBox.classList.remove('hidden');
                            }
                        })
                        .catch(() => {
                            spinner.classList.add('hidden');
                        });
                }, 300);
            });

            // Close suggestions on outside click
            document.addEventListener('click', function(e) {
                if (!locationInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        }

        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
