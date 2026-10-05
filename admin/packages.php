<?php
/**
 * Admin Tour Packages Management Controller
 * Full-featured package builder:
 * - Live Location Search (OpenStreetMap/Google)
 * - Category & Duration Pickers
 * - Dynamic Day-by-Day Itinerary Builder
 * - Select Hotels from GuideFlux Database (`hotels` table) or custom stays
 * - Highlights, Inclusions & Exclusions Checklists / Lists
 * - Multi-Photo Uploads
 * - Pricing & Token Advance Configuration
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
$uploadDir = __DIR__ . '/../uploads/packages/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

// 1. HANDLE POST ACTIONS (Save / Update Tour Package)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_package'])) {
    if ($pdo) {
        try {
            $pkgId = isset($_POST['package_id']) && (int)$_POST['package_id'] > 0 ? (int)$_POST['package_id'] : null;
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            }
            $subtitle = trim($_POST['subtitle'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $stateCountry = trim($_POST['state_country'] ?? '');
            $circuitType = trim($_POST['circuit_type'] ?? 'Holiday Circuit');
            $category = trim($_POST['category'] ?? 'domestic');
            $durationNights = (int)($_POST['duration_nights'] ?? 4);
            $durationDays = (int)($_POST['duration_days'] ?? 5);
            $durationText = trim($_POST['duration_text'] ?? "{$durationNights} Nights / {$durationDays} Days");
            $price = (float)($_POST['price'] ?? 0);
            $originalPrice = (float)($_POST['original_price'] ?? ($price * 1.25));
            $tokenAdvance = (float)($_POST['token_advance'] ?? 2500);
            $badge = trim($_POST['badge'] ?? 'Bestseller');
            $rating = (float)($_POST['rating'] ?? 4.9);
            $reviewsCount = (int)($_POST['reviews_count'] ?? 350);
            $status = trim($_POST['status'] ?? 'active');
            $policies = trim($_POST['policies'] ?? '');

            // Process Itinerary Array to JSON
            $itineraryList = [];
            if (isset($_POST['itinerary']) && is_array($_POST['itinerary'])) {
                foreach ($_POST['itinerary'] as $day) {
                    if (!empty($day['title']) || !empty($day['desc'])) {
                        $itineraryList[] = [
                            'day' => trim($day['day'] ?? 'Day'),
                            'title' => trim($day['title'] ?? ''),
                            'desc' => trim($day['desc'] ?? ''),
                            'meals' => trim($day['meals'] ?? 'Breakfast & Dinner Included'),
                            'stay' => trim($day['stay'] ?? 'Hotel Included')
                        ];
                    }
                }
            }
            $itineraryJson = json_encode($itineraryList, JSON_UNESCAPED_UNICODE);

            // Process Highlights Array to JSON
            $highlightsList = [];
            if (isset($_POST['highlights']) && is_array($_POST['highlights'])) {
                foreach ($_POST['highlights'] as $hl) {
                    if (!empty($hl['title'])) {
                        $highlightsList[] = [
                            'icon' => trim($hl['icon'] ?? 'fa-solid fa-star'),
                            'title' => trim($hl['title'] ?? ''),
                            'desc' => trim($hl['desc'] ?? '')
                        ];
                    }
                }
            }
            $highlightsJson = json_encode($highlightsList, JSON_UNESCAPED_UNICODE);

            // Inclusions & Exclusions Array to JSON
            $inclusionsRaw = explode("\n", str_replace("\r", "", $_POST['inclusions_text'] ?? ''));
            $inclusionsList = array_values(array_filter(array_map('trim', $inclusionsRaw)));
            $inclusionsJson = json_encode($inclusionsList, JSON_UNESCAPED_UNICODE);

            $exclusionsRaw = explode("\n", str_replace("\r", "", $_POST['exclusions_text'] ?? ''));
            $exclusionsList = array_values(array_filter(array_map('trim', $exclusionsRaw)));
            $exclusionsJson = json_encode($exclusionsList, JSON_UNESCAPED_UNICODE);

            // Selected Database Hotels (Array of Hotel IDs)
            $selectedHotelIds = isset($_POST['hotel_ids']) && is_array($_POST['hotel_ids']) ? $_POST['hotel_ids'] : [];
            $hotelIdsJson = json_encode(array_map('intval', $selectedHotelIds));

            // Featured Image Handling
            $featuredImage = trim($_POST['existing_featured_image'] ?? '');
            if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['featured_image_file']['tmp_name'];
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['featured_image_file']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $featuredImage = 'uploads/packages/' . $fileName;
                }
            } elseif (!empty($_POST['featured_image_url'])) {
                $featuredImage = trim($_POST['featured_image_url']);
            }

            // Gallery Photos Handling
            $galleryList = [];
            if (!empty($_POST['existing_gallery'])) {
                $decG = json_decode($_POST['existing_gallery'], true);
                if (is_array($decG)) $galleryList = $decG;
            }
            if (!empty($featuredImage) && !in_array($featuredImage, $galleryList)) {
                array_unshift($galleryList, $featuredImage);
            }

            if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
                $totalGFiles = count($_FILES['gallery_images']['name']);
                for ($gi = 0; $gi < $totalGFiles; $gi++) {
                    if ($_FILES['gallery_images']['error'][$gi] === UPLOAD_ERR_OK) {
                        $gtmp = $_FILES['gallery_images']['tmp_name'][$gi];
                        $gname = time() . '_g_' . $gi . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['gallery_images']['name'][$gi]);
                        $gtarget = $uploadDir . $gname;
                        if (move_uploaded_file($gtmp, $gtarget)) {
                            $galleryList[] = 'uploads/packages/' . $gname;
                        }
                    }
                }
            }
            $galleryJson = json_encode(array_values(array_unique($galleryList)), JSON_UNESCAPED_UNICODE);

            if (empty($title)) {
                throw new Exception("Tour Package Title is required.");
            }

            if ($pkgId) {
                // Update Package
                $sql = "UPDATE `packages` SET 
                    `title` = ?, `slug` = ?, `subtitle` = ?, `location` = ?, `state_country` = ?,
                    `circuit_type` = ?, `category` = ?, `duration_nights` = ?, `duration_days` = ?, `duration_text` = ?,
                    `price` = ?, `original_price` = ?, `token_advance` = ?, `badge` = ?, `rating` = ?, `reviews_count` = ?,
                    `featured_image` = ?, `gallery` = ?, `highlights` = ?, `itinerary` = ?, `inclusions` = ?, `exclusions` = ?,
                    `hotel_ids` = ?, `policies` = ?, `status` = ?
                    WHERE `id` = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $slug, $subtitle, $location, $stateCountry,
                    $circuitType, $category, $durationNights, $durationDays, $durationText,
                    $price, $originalPrice, $tokenAdvance, $badge, $rating, $reviewsCount,
                    $featuredImage, $galleryJson, $highlightsJson, $itineraryJson, $inclusionsJson, $exclusionsJson,
                    $hotelIdsJson, $policies, $status,
                    $pkgId
                ]);
                $alertMessage = "Package '{$title}' updated successfully!";
            } else {
                // Insert New Package
                $sql = "INSERT INTO `packages` (
                    `title`, `slug`, `subtitle`, `location`, `state_country`,
                    `circuit_type`, `category`, `duration_nights`, `duration_days`, `duration_text`,
                    `price`, `original_price`, `token_advance`, `badge`, `rating`, `reviews_count`,
                    `featured_image`, `gallery`, `highlights`, `itinerary`, `inclusions`, `exclusions`,
                    `hotel_ids`, `policies`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $slug, $subtitle, $location, $stateCountry,
                    $circuitType, $category, $durationNights, $durationDays, $durationText,
                    $price, $originalPrice, $tokenAdvance, $badge, $rating, $reviewsCount,
                    $featuredImage, $galleryJson, $highlightsJson, $itineraryJson, $inclusionsJson, $exclusionsJson,
                    $hotelIdsJson, $policies, $status
                ]);
                $alertMessage = "Tour Package '{$title}' created successfully!";
            }

        } catch (Exception $e) {
            $alertMessage = "Error saving package: " . $e->getMessage();
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
                $pdo->prepare("DELETE FROM `packages` WHERE `id` = ?")->execute([$targetId]);
                $alertMessage = "Tour package deleted successfully.";
            } elseif ($action === 'toggle_status') {
                $currStatus = $pdo->prepare("SELECT `status` FROM `packages` WHERE `id` = ?");
                $currStatus->execute([$targetId]);
                $st = $currStatus->fetchColumn();
                $newStatus = ($st === 'active') ? 'draft' : 'active';
                $pdo->prepare("UPDATE `packages` SET `status` = ? WHERE `id` = ?")->execute([$newStatus, $targetId]);
                $alertMessage = "Package status updated to " . strtoupper($newStatus) . ".";
            }
        } catch (Exception $e) {
            $alertMessage = "Action failed: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// 3. EDIT FETCH (if editing specific package)
$editPackage = null;
if (isset($_GET['edit_id']) && (int)$_GET['edit_id'] > 0) {
    $editId = (int)$_GET['edit_id'];
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM `packages` WHERE `id` = ?");
        $stmt->execute([$editId]);
        $editPackage = $stmt->fetch();
    }
}

// 4. FETCH ALL HOTELS FROM DATABASE (For Hotel Linker Dropdown / Checkboxes)
$availableHotels = [];
if ($pdo) {
    try {
        $availableHotels = $pdo->query("SELECT `id`, `name`, `city`, `star_rating`, `property_type`, `featured_image` FROM `hotels` WHERE `status` = 'active' ORDER BY `city` ASC, `name` ASC")->fetchAll();
    } catch (Exception $e) {
        $availableHotels = [];
    }
}

// 5. FETCH PACKAGES LIST & STATS
$packages = [];
$totalPackages = 0;
$activePackages = 0;
$domesticCount = 0;
$internationalCount = 0;

if ($pdo) {
    try {
        $totalPackages = (int)$pdo->query("SELECT COUNT(*) FROM `packages`")->fetchColumn();
        $activePackages = (int)$pdo->query("SELECT COUNT(*) FROM `packages` WHERE `status` = 'active'")->fetchColumn();
        $domesticCount = (int)$pdo->query("SELECT COUNT(*) FROM `packages` WHERE `category` = 'domestic'")->fetchColumn();
        $internationalCount = (int)$pdo->query("SELECT COUNT(*) FROM `packages` WHERE `category` = 'international'")->fetchColumn();

        $search = trim($_GET['q'] ?? '');
        $filterCat = trim($_GET['category'] ?? '');

        $query = "SELECT * FROM `packages` WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR location LIKE ? OR circuit_type LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($filterCat)) {
            $query .= " AND category = ?";
            $params[] = $filterCat;
        }

        $query .= " ORDER BY id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $packages = $stmt->fetchAll();

    } catch (Exception $e) {
        $packages = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Packages Management - GuideFlux Admin</title>
    
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
                        <h1 class="text-lg xl:text-xl font-extrabold text-slate-900 tracking-tight">Holiday &amp; Tour Packages</h1>
                        <p class="text-xs text-slate-400 font-medium hidden sm:block">Day-by-Day Itineraries • Linked Hotels • Inclusions • Dynamic Advance Pricing</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="openPackageModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-bold shadow-xs transition-colors">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Package</span>
                    </button>
                    <a href="../search.php" target="_blank" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:text-brand-600 transition-colors" title="View Frontend Search">
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
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Packages</span>
                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($totalPackages) ?></div>
                        <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                            <span><?= number_format($activePackages) ?> Active on Website</span>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Domestic Tours</span>
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-mountain-sun"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($domesticCount) ?></div>
                        <div class="text-[11px] text-slate-400 font-medium mt-1">India Circuit Stays</div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">International Tours</span>
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-plane-departure"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= number_format($internationalCount) ?></div>
                        <div class="text-[11px] text-slate-400 font-medium mt-1">Global Gateways</div>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Database Hotels Available</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900"><?= count($availableHotels) ?></div>
                        <div class="text-[11px] text-brand-600 font-semibold mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-link text-[9px]"></i>
                            <span>Ready to link in packages</span>
                        </div>
                    </div>
                </div>

                <!-- Packages Listings Card -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    
                    <!-- Search & Filter Controls -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <form method="GET" class="w-full sm:w-auto flex-1 flex items-center gap-3">
                            <div class="relative flex-1 sm:max-w-xs">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search package title, location, circuit..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            </div>
                            
                            <select name="category" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:outline-none">
                                <option value="">All Categories</option>
                                <option value="domestic" <?= ($_GET['category'] ?? '') === 'domestic' ? 'selected' : '' ?>>Domestic</option>
                                <option value="international" <?= ($_GET['category'] ?? '') === 'international' ? 'selected' : '' ?>>International</option>
                                <option value="adventure" <?= ($_GET['category'] ?? '') === 'adventure' ? 'selected' : '' ?>>Adventure</option>
                                <option value="luxury" <?= ($_GET['category'] ?? '') === 'luxury' ? 'selected' : '' ?>>Luxury</option>
                                <option value="honeymoon" <?= ($_GET['category'] ?? '') === 'honeymoon' ? 'selected' : '' ?>>Honeymoon</option>
                            </select>

                            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                                Filter
                            </button>
                            <?php if (!empty($_GET['q']) || !empty($_GET['category'])): ?>
                                <a href="packages.php" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold">Clear</a>
                            <?php endif; ?>
                        </form>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <span class="text-xs text-slate-500 font-medium">Found <strong><?= count($packages) ?></strong> package(s)</span>
                        </div>
                    </div>

                    <!-- Packages Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-4">Tour Package</th>
                                    <th class="py-3.5 px-4">Destination &amp; Circuit</th>
                                    <th class="py-3.5 px-4">Duration &amp; Category</th>
                                    <th class="py-3.5 px-4">Price / Token</th>
                                    <th class="py-3.5 px-4 text-center">Hotels Linked</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (empty($packages)): ?>
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-slate-400">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                            </div>
                                            <p class="font-bold text-slate-600 text-sm">No packages found</p>
                                            <p class="text-xs text-slate-400 mt-1">Click "Add New Package" to create your first holiday tour package with complete itinerary and hotel linking.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($packages as $pkg): ?>
                                        <?php
                                        $linkedIds = json_decode($pkg['hotel_ids'] ?? '[]', true);
                                        $hotelCount = is_array($linkedIds) ? count($linkedIds) : 0;
                                        ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <!-- Package Title & Image -->
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-14 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                                        <img src="<?= htmlspecialchars(!empty($pkg['featured_image']) ? (strpos($pkg['featured_image'], 'http') === 0 ? $pkg['featured_image'] : '../' . $pkg['featured_image']) : 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=200&q=80') ?>" alt="<?= htmlspecialchars($pkg['title']) ?>" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <a href="../package-details.php?id=PKG-DB-<?= $pkg['id'] ?>" target="_blank" class="font-extrabold text-slate-900 text-sm hover:text-brand-600 transition-colors block max-w-xs truncate" title="<?= htmlspecialchars($pkg['title']) ?>">
                                                            <?= htmlspecialchars($pkg['title']) ?>
                                                        </a>
                                                        <div class="text-[11px] text-slate-400 font-medium">
                                                            <?= htmlspecialchars($pkg['badge'] ?? 'Bestseller') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Destination -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-location-dot text-brand-600 text-[11px]"></i>
                                                    <span><?= htmlspecialchars($pkg['location']) ?></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400">
                                                    <?= htmlspecialchars($pkg['state_country'] ?: 'Circuit') ?>
                                                </div>
                                            </td>

                                            <!-- Duration & Category -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-slate-800 flex items-center gap-1">
                                                    <i class="fa-regular fa-clock text-slate-400 text-[11px]"></i>
                                                    <span><?= htmlspecialchars($pkg['duration_text'] ?: ($pkg['duration_nights'] . 'N / ' . $pkg['duration_days'] . 'D')) ?></span>
                                                </div>
                                                <span class="inline-block px-2 py-0.5 mt-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">
                                                    <?= htmlspecialchars($pkg['category']) ?>
                                                </span>
                                            </td>

                                            <!-- Pricing -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-black text-slate-900 text-sm">
                                                    ₹<?= number_format($pkg['price']) ?>
                                                </div>
                                                <div class="text-[10px] text-emerald-600 font-semibold">
                                                    Token: ₹<?= number_format($pkg['token_advance']) ?>
                                                </div>
                                            </td>

                                            <!-- Linked Hotels Count -->
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[11px]">
                                                    <i class="fa-solid fa-hotel text-[10px]"></i>
                                                    <?= $hotelCount ?> Hotel(s)
                                                </span>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-3.5 px-4">
                                                <a href="packages.php?action=toggle_status&id=<?= $pkg['id'] ?>" title="Click to toggle status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all <?= $pkg['status'] === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= $pkg['status'] === 'active' ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                                    <span><?= strtoupper($pkg['status']) ?></span>
                                                </a>
                                            </td>

                                            <!-- Actions -->
                                            <td class="py-3.5 px-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="packages.php?edit_id=<?= $pkg['id'] ?>" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-brand-50 hover:text-brand-600 hover:border-brand-300 flex items-center justify-center text-slate-600 transition-colors" title="Edit Package & Itinerary">
                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                    </a>
                                                    <a href="packages.php?action=delete&id=<?= $pkg['id'] ?>" onclick="return confirm('Are you sure you want to delete this tour package?');" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 flex items-center justify-center text-slate-400 transition-colors" title="Delete Package">
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
         MODAL / DRAWER: ADD & EDIT TOUR PACKAGE WITH STEP TABS
         ======================================================== -->
    <div id="packageModal" class="<?= $editPackage ? 'flex' : 'hidden' ?> fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-5xl max-h-[94vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-base">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight" id="modalTitle">
                            <?= $editPackage ? 'Edit Package: ' . htmlspecialchars($editPackage['title']) : 'Create New Holiday &amp; Tour Package' ?>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Real-Time Location • Day-by-Day Itinerary • Link GuideFlux DB Hotels • Inclusions</p>
                    </div>
                </div>
                <button type="button" onclick="closePackageModal()" class="w-8 h-8 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Tab Switcher Bar -->
            <div class="px-6 py-2 border-b border-slate-200 bg-white flex items-center gap-2 overflow-x-auto scrollbar-none shrink-0">
                <button type="button" onclick="switchPackageTab('ptab-basic')" class="ptab-btn active-tab px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-brand-600 text-white border-brand-600" data-target="ptab-basic">
                    <i class="fa-solid fa-map-pin mr-1.5"></i> 1. Location &amp; Circuit
                </button>
                <button type="button" onclick="switchPackageTab('ptab-pricing')" class="ptab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="ptab-pricing">
                    <i class="fa-solid fa-tag mr-1.5"></i> 2. Pricing &amp; Duration
                </button>
                <button type="button" onclick="switchPackageTab('ptab-itinerary')" class="ptab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="ptab-itinerary">
                    <i class="fa-solid fa-calendar-days mr-1.5"></i> 3. Day-by-Day Itinerary (<span id="daysCountBadge">5</span> Days)
                </button>
                <button type="button" onclick="switchPackageTab('ptab-hotels')" class="ptab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="ptab-hotels">
                    <i class="fa-solid fa-hotel mr-1.5"></i> 4. Link DB Hotels
                </button>
                <button type="button" onclick="switchPackageTab('ptab-inclusions')" class="ptab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-white text-slate-600 border-slate-200 hover:bg-slate-50" data-target="ptab-inclusions">
                    <i class="fa-solid fa-list-check mr-1.5"></i> 5. Inclusions &amp; Photos
                </button>
            </div>

            <!-- Form Container -->
            <form action="packages.php" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                <input type="hidden" name="save_package" value="1">
                <input type="hidden" name="package_id" value="<?= $editPackage ? $editPackage['id'] : '' ?>">
                <input type="hidden" name="existing_featured_image" value="<?= $editPackage ? htmlspecialchars($editPackage['featured_image'] ?? '') : '' ?>">
                <input type="hidden" name="existing_gallery" value="<?= $editPackage ? htmlspecialchars($editPackage['gallery'] ?? '[]') : '[]' ?>">

                <!-- ================= TAB 1: LOCATION & BASIC CIRCUIT ================= -->
                <div id="ptab-basic" class="ptab-content space-y-5">
                    
                    <!-- Real-Time Location Search for Packages -->
                    <div class="p-4 rounded-2xl bg-brand-50/60 border border-brand-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black uppercase tracking-wider text-brand-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-crosshairs text-brand-600"></i>
                                <span>Live Destination / Circuit Search (OpenStreetMap &amp; Google)</span>
                            </label>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                Real-Time Autocomplete
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Search any destination (e.g. <em>"Kashmir"</em>, <em>"Kerala"</em>, <em>"Manali"</em>, <em>"Dubai"</em>, <em>"Goa"</em>). Click to auto-fill location and state details.
                        </p>
                        
                        <div class="relative">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" id="pkgLocationSearchInput" autocomplete="off" placeholder="Type destination name (e.g. Srinagar, Munnar, Jaipur, Bali)..." class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-brand-300 bg-white text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <span id="pkgSearchSpinner" class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-brand-600">
                                    <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                                </span>
                            </div>

                            <!-- Autocomplete Dropdown List -->
                            <div id="pkgLocationSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-slate-100 custom-scrollbar"></div>
                        </div>
                    </div>

                    <!-- Package Title & Subtitle Grid -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tour Package Title *</label>
                            <input type="text" name="title" id="pkgTitleInput" required value="<?= $editPackage ? htmlspecialchars($editPackage['title']) : '' ?>" placeholder="e.g. Kashmir Paradise: Shikara, Snow Peaks &amp; Luxury Dal Lake Houseboat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Package Subtitle / Teaser</label>
                            <textarea name="subtitle" rows="2" placeholder="e.g. Explore the Switzerland of India with private chauffeured transfers, gondola assistance, and authentic royal houseboat hospitality." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= $editPackage ? htmlspecialchars($editPackage['subtitle'] ?? '') : '' ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Main Destination(s) *</label>
                                <input type="text" name="location" id="pkgLocationInput" required value="<?= $editPackage ? htmlspecialchars($editPackage['location']) : '' ?>" placeholder="e.g. Srinagar • Gulmarg • Pahalgam" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">State &amp; Country</label>
                                <input type="text" name="state_country" id="pkgStateCountryInput" value="<?= $editPackage ? htmlspecialchars($editPackage['state_country'] ?? '') : '' ?>" placeholder="e.g. Jammu &amp; Kashmir, India" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Holiday Category</label>
                                <select name="category" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                                    <option value="domestic" <?= ($editPackage && $editPackage['category'] === 'domestic') ? 'selected' : '' ?>>Domestic Holidays (India)</option>
                                    <option value="international" <?= ($editPackage && $editPackage['category'] === 'international') ? 'selected' : '' ?>>International Holidays</option>
                                    <option value="honeymoon" <?= ($editPackage && $editPackage['category'] === 'honeymoon') ? 'selected' : '' ?>>Honeymoon Special</option>
                                    <option value="luxury" <?= ($editPackage && $editPackage['category'] === 'luxury') ? 'selected' : '' ?>>Luxury Curated</option>
                                    <option value="adventure" <?= ($editPackage && $editPackage['category'] === 'adventure') ? 'selected' : '' ?>>Adventure &amp; Trekking</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 2: PRICING & DURATION ================= -->
                <div id="ptab-pricing" class="ptab-content hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Nights)</label>
                            <input type="number" id="pkgNightsInput" name="duration_nights" value="<?= $editPackage ? htmlspecialchars($editPackage['duration_nights']) : '5' ?>" onchange="updateDurationText()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Days)</label>
                            <input type="number" id="pkgDaysInput" name="duration_days" value="<?= $editPackage ? htmlspecialchars($editPackage['duration_days']) : '6' ?>" onchange="updateDurationText()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration Label</label>
                            <input type="text" id="pkgDurationText" name="duration_text" value="<?= $editPackage ? htmlspecialchars($editPackage['duration_text'] ?? '') : '5 Nights / 6 Days' ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-brand-700 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Package Price / Person (₹) *</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="price" required value="<?= $editPackage ? htmlspecialchars($editPackage['price']) : '17999' ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Original Strikethrough Price (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="original_price" value="<?= $editPackage ? htmlspecialchars($editPackage['original_price'] ?? '') : '22999' ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Token Advance for Booking (₹)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="token_advance" value="<?= $editPackage ? htmlspecialchars($editPackage['token_advance'] ?? '2500') : '2500' ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-black text-emerald-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Badge Highlight</label>
                            <input type="text" name="badge" value="<?= $editPackage ? htmlspecialchars($editPackage['badge'] ?? 'Bestseller 2026') : 'Bestseller 2026' ?>" placeholder="e.g. Bestseller, Honeymoon Fav, 40% Off" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Rating Score</label>
                            <input type="number" step="0.1" name="rating" value="<?= $editPackage ? htmlspecialchars($editPackage['rating'] ?? '4.9') : '4.9' ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-amber-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Publish Status</label>
                            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900">
                                <option value="active" <?= ($editPackage && $editPackage['status'] === 'active') ? 'selected' : '' ?>>Active (Visible on Website)</option>
                                <option value="draft" <?= ($editPackage && $editPackage['status'] === 'draft') ? 'selected' : '' ?>>Draft / Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 3: DYNAMIC DAY-BY-DAY ITINERARY ================= -->
                <div id="ptab-itinerary" class="ptab-content hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700">Day-by-Day Tour Itinerary</h4>
                            <p class="text-xs text-slate-400">Step-by-step sightseeing plan with meals and overnight stay details.</p>
                        </div>
                        <button type="button" onclick="addItineraryDay()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 text-xs font-bold transition">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Next Day</span>
                        </button>
                    </div>

                    <?php
                    $savedItinerary = [];
                    if ($editPackage && !empty($editPackage['itinerary'])) {
                        $decIt = json_decode($editPackage['itinerary'], true);
                        if (is_array($decIt)) $savedItinerary = $decIt;
                    }
                    ?>

                    <div id="itineraryDaysContainer" class="space-y-4">
                        <?php if (!empty($savedItinerary)): ?>
                            <?php foreach ($savedItinerary as $dIdx => $day): ?>
                                <div class="itinerary-day-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center day-counter-num"><?= $dIdx + 1 ?></span>
                                            <input type="text" name="itinerary[<?= $dIdx ?>][day]" value="<?= htmlspecialchars($day['day'] ?? ('Day 0' . ($dIdx + 1))) ?>" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-800 w-24">
                                        </div>
                                        <button type="button" onclick="removeItineraryDay(this)" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-2 py-1 rounded-lg hover:bg-rose-50 transition">
                                            <i class="fa-solid fa-trash-can"></i> Remove
                                        </button>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Day Title / Highlights</label>
                                        <input type="text" name="itinerary[<?= $dIdx ?>][title]" value="<?= htmlspecialchars($day['title'] ?? '') ?>" placeholder="e.g. Arrival &amp; Sunset Shikara Ride on Dal Lake" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Detailed Activities Description</label>
                                        <textarea name="itinerary[<?= $dIdx ?>][desc]" rows="2" placeholder="Full day description of sightseeing, drive distance, viewpoints..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800"><?= htmlspecialchars($day['desc'] ?? '') ?></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Meals Inclusions</label>
                                            <input type="text" name="itinerary[<?= $dIdx ?>][meals]" value="<?= htmlspecialchars($day['meals'] ?? 'Breakfast & Dinner Included') ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Overnight Stay</label>
                                            <input type="text" name="itinerary[<?= $dIdx ?>][stay]" value="<?= htmlspecialchars($day['stay'] ?? '4★ Hotel in Srinagar') ?>" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default 1 Day Row -->
                            <div class="itinerary-day-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center day-counter-num">1</span>
                                        <input type="text" name="itinerary[0][day]" value="Day 01" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-800 w-24">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Day Title / Highlights</label>
                                    <input type="text" name="itinerary[0][title]" value="Arrival &amp; Romantic Sunset Sightseeing" placeholder="e.g. Arrival &amp; Sunset Shikara Ride on Dal Lake" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Detailed Activities Description</label>
                                    <textarea name="itinerary[0][desc]" rows="2" placeholder="Full day description of sightseeing, drive distance, viewpoints..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">Arrive at airport/railway station where our dedicated chauffeur greets you. Transfer to the luxury hotel for check-in followed by evening leisure sightseeing.</textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Meals Inclusions</label>
                                        <input type="text" name="itinerary[0][meals]" value="Dinner Included" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Overnight Stay</label>
                                        <input type="text" name="itinerary[0][stay]" value="4★ Luxury Resort" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ================= TAB 4: LINK DATABASE HOTELS ================= -->
                <div id="ptab-hotels" class="ptab-content hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700">Select Accommodations Included in this Package</h4>
                            <p class="text-xs text-slate-400">
                                Select from your existing GuideFlux Hotels database. (If a hotel is not listed, add it first in <a href="hotels.php" target="_blank" class="text-brand-600 font-bold underline">Hotels &amp; Resorts</a>).
                            </p>
                        </div>
                        <a href="hotels.php" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold hover:bg-brand-100 transition">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add New Hotel in DB</span>
                        </a>
                    </div>

                    <?php
                    $selectedHotelIds = [];
                    if ($editPackage && !empty($editPackage['hotel_ids'])) {
                        $decHids = json_decode($editPackage['hotel_ids'], true);
                        if (is_array($decHids)) $selectedHotelIds = $decHids;
                    }
                    ?>

                    <?php if (empty($availableHotels)): ?>
                        <div class="p-8 text-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 text-slate-500">
                            <i class="fa-solid fa-hotel text-2xl text-slate-400 mb-2"></i>
                            <p class="font-bold text-sm text-slate-700">No active hotels in database yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Create hotels in the Hotels &amp; Resorts panel to link them with tour packages.</p>
                            <a href="hotels.php" target="_blank" class="inline-block mt-3 px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs">Create Hotel Now</a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 max-h-80 overflow-y-auto custom-scrollbar p-1">
                            <?php foreach ($availableHotels as $h): ?>
                                <?php $isHotelSelected = in_array((int)$h['id'], $selectedHotelIds); ?>
                                <label class="flex items-start gap-3 p-3 rounded-2xl border transition cursor-pointer select-none <?= $isHotelSelected ? 'border-brand-500 bg-brand-50/50' : 'border-slate-200 bg-slate-50/50 hover:border-brand-300' ?>">
                                    <input type="checkbox" name="hotel_ids[]" value="<?= $h['id'] ?>" <?= $isHotelSelected ? 'checked' : '' ?> class="mt-1 w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-200">
                                        <img src="<?= htmlspecialchars(!empty($h['featured_image']) ? (strpos($h['featured_image'], 'http') === 0 ? $h['featured_image'] : '../' . $h['featured_image']) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=150&q=80') ?>" alt="<?= htmlspecialchars($h['name']) ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-bold text-slate-900 truncate" title="<?= htmlspecialchars($h['name']) ?>"><?= htmlspecialchars($h['name']) ?></div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                            <i class="fa-solid fa-location-dot text-[9px] text-brand-600"></i>
                                            <span class="truncate"><?= htmlspecialchars($h['city']) ?></span>
                                        </div>
                                        <div class="text-[10px] text-amber-600 font-bold mt-1">
                                            <?= htmlspecialchars($h['star_rating']) ?>★ <?= htmlspecialchars($h['property_type']) ?>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ================= TAB 5: INCLUSIONS, EXCLUSIONS & GALLERY ================= -->
                <div id="ptab-inclusions" class="ptab-content hidden space-y-5">
                    
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
                                <input type="url" name="featured_image_url" value="<?= ($editPackage && strpos($editPackage['featured_image'] ?? '', 'http') === 0) ? htmlspecialchars($editPackage['featured_image']) : '' ?>" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-800">
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Gallery Photos -->
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700">Additional Magazine Gallery Photos (Multiple)</label>
                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>

                    <!-- Inclusions & Exclusions Textareas -->
                    <?php
                    $incText = "Pick-up and drop from Airport / Railway station by private AC vehicle\nVerified 4★ / 5★ Hotel Accommodations as per itinerary\nDaily Buffet Breakfast and Chef-curated Dinners\nAll interstate toll taxes, parking fees, fuel, and driver night allowances\n24/7 dedicated Tour Manager assistance on ground";
                    if ($editPackage && !empty($editPackage['inclusions'])) {
                        $decIncs = json_decode($editPackage['inclusions'], true);
                        if (is_array($decIncs)) $incText = implode("\n", $decIncs);
                    }

                    $excText = "Airfare / Train tickets to destination (can be booked on request)\nPersonal expenses like laundry, minibar, telephone charges\nEntry tickets to monuments, activities, and camera permits\nOptional adventure rides, cable cars, and personal tips";
                    if ($editPackage && !empty($editPackage['exclusions'])) {
                        $decExcs = json_decode($editPackage['exclusions'], true);
                        if (is_array($decExcs)) $excText = implode("\n", $decExcs);
                    }
                    ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Package Inclusions (1 per line)</label>
                            <textarea name="inclusions_text" rows="5" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= htmlspecialchars($incText) ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Package Exclusions (1 per line)</label>
                            <textarea name="exclusions_text" rows="5" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white"><?= htmlspecialchars($excText) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Sticky Footer Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                    <button type="button" onclick="closePackageModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                        Cancel
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" id="prevPkgTabBtn" onclick="navigatePkgTab(-1)" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold transition hidden">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Previous
                        </button>
                        <button type="button" id="nextPkgTabBtn" onclick="navigatePkgTab(1)" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition">
                            Next Step <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                        <button type="submit" id="savePkgSubmitBtn" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-black shadow-xs transition hidden">
                            <i class="fa-solid fa-check mr-1.5"></i> Save &amp; Publish Package
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- JavaScript for Package Controller -->
    <script>
        const pkgTabList = ['ptab-basic', 'ptab-pricing', 'ptab-itinerary', 'ptab-hotels', 'ptab-inclusions'];
        let currentPkgTabIndex = 0;

        function switchPackageTab(targetId) {
            document.querySelectorAll('.ptab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(targetId).classList.remove('hidden');

            document.querySelectorAll('.ptab-btn').forEach(btn => {
                if (btn.getAttribute('data-target') === targetId) {
                    btn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
                    btn.classList.add('bg-brand-600', 'text-white', 'border-brand-600');
                } else {
                    btn.classList.remove('bg-brand-600', 'text-white', 'border-brand-600');
                    btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                }
            });

            currentPkgTabIndex = pkgTabList.indexOf(targetId);
            updatePkgNavButtons();
        }

        function navigatePkgTab(direction) {
            currentPkgTabIndex += direction;
            if (currentPkgTabIndex < 0) currentPkgTabIndex = 0;
            if (currentPkgTabIndex >= pkgTabList.length) currentPkgTabIndex = pkgTabList.length - 1;
            switchPackageTab(pkgTabList[currentPkgTabIndex]);
        }

        function updatePkgNavButtons() {
            const prevBtn = document.getElementById('prevPkgTabBtn');
            const nextBtn = document.getElementById('nextPkgTabBtn');
            const submitBtn = document.getElementById('savePkgSubmitBtn');

            if (currentPkgTabIndex === 0) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }

            if (currentPkgTabIndex === pkgTabList.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function openPackageModal() {
            document.getElementById('packageModal').classList.remove('hidden');
            document.getElementById('packageModal').classList.add('flex');
            switchPackageTab('ptab-basic');
        }

        function closePackageModal() {
            document.getElementById('packageModal').classList.add('hidden');
            document.getElementById('packageModal').classList.remove('flex');
            if (window.location.search.includes('edit_id=')) {
                window.location.href = 'packages.php';
            }
        }

        function updateDurationText() {
            const n = document.getElementById('pkgNightsInput')?.value || 4;
            const d = document.getElementById('pkgDaysInput')?.value || 5;
            document.getElementById('pkgDurationText').value = `${n} Nights / ${d} Days`;
        }

        // Add / Remove Itinerary Days
        function addItineraryDay() {
            const container = document.getElementById('itineraryDaysContainer');
            const newIndex = container.children.length;
            const dayNum = newIndex + 1;
            const dayStr = dayNum < 10 ? `Day 0${dayNum}` : `Day ${dayNum}`;

            const div = document.createElement('div');
            div.className = 'itinerary-day-row p-4 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-3 relative animate-in fade-in duration-150';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center day-counter-num">${dayNum}</span>
                        <input type="text" name="itinerary[${newIndex}][day]" value="${dayStr}" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-800 w-24">
                    </div>
                    <button type="button" onclick="removeItineraryDay(this)" class="text-slate-400 hover:text-rose-600 text-xs font-bold px-2 py-1 rounded-lg hover:bg-rose-50 transition">
                        <i class="fa-solid fa-trash-can"></i> Remove
                    </button>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Day Title / Highlights</label>
                    <input type="text" name="itinerary[${newIndex}][title]" placeholder="e.g. Excursion to Scenic Valley &amp; Mountain Viewpoint" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Detailed Activities Description</label>
                    <textarea name="itinerary[${newIndex}][desc]" rows="2" placeholder="Full day description of sightseeing, drive distance, viewpoints..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Meals Inclusions</label>
                        <input type="text" name="itinerary[${newIndex}][meals]" value="Breakfast &amp; Dinner Included" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Overnight Stay</label>
                        <input type="text" name="itinerary[${newIndex}][stay]" value="4★ Hotel" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800">
                    </div>
                </div>
            `;
            container.appendChild(div);
            updateItineraryCounters();
        }

        function removeItineraryDay(btn) {
            const container = document.getElementById('itineraryDaysContainer');
            if (container.children.length > 1) {
                btn.closest('.itinerary-day-row').remove();
                updateItineraryCounters();
            } else {
                alert('At least 1 day itinerary is required.');
            }
        }

        function updateItineraryCounters() {
            const rows = document.querySelectorAll('#itineraryDaysContainer .itinerary-day-row');
            rows.forEach((row, idx) => {
                const numEl = row.querySelector('.day-counter-num');
                if (numEl) numEl.textContent = idx + 1;
            });
            document.getElementById('daysCountBadge').textContent = rows.length;
        }

        // ========================================================
        // LIVE REAL-TIME LOCATION SEARCH AUTOCOMPLETE (OSM / Google)
        // ========================================================
        const pkgLocInput = document.getElementById('pkgLocationSearchInput');
        const pkgSuggestionsBox = document.getElementById('pkgLocationSuggestions');
        const pkgSpinner = document.getElementById('pkgSearchSpinner');
        let pkgDebounceTimer;

        if (pkgLocInput) {
            pkgLocInput.addEventListener('input', function() {
                const q = this.value.trim();
                clearTimeout(pkgDebounceTimer);

                if (q.length < 2) {
                    pkgSuggestionsBox.classList.add('hidden');
                    return;
                }

                pkgSpinner.classList.remove('hidden');
                pkgDebounceTimer = setTimeout(() => {
                    fetch(`../api/places.php?q=${encodeURIComponent(q)}`)
                        .then(res => res.json())
                        .then(data => {
                            pkgSpinner.classList.add('hidden');
                            if (data.success && data.data && data.data.length > 0) {
                                pkgSuggestionsBox.innerHTML = '';
                                data.data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item p-3 transition flex items-start gap-2.5';
                                    div.innerHTML = `
                                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="fa-solid fa-map-pin text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-900 truncate">${item.name || item.city}</div>
                                            <div class="text-[11px] text-slate-500 truncate">${item.full_address}</div>
                                        </div>
                                    `;
                                    div.addEventListener('click', () => {
                                        if (item.name && (!document.getElementById('pkgTitleInput').value || document.getElementById('pkgTitleInput').value === '')) {
                                            document.getElementById('pkgTitleInput').value = `${item.name} Holiday Discovery Circuit`;
                                        }
                                        document.getElementById('pkgLocationInput').value = item.name || item.city;
                                        document.getElementById('pkgStateCountryInput').value = [item.state, item.country].filter(Boolean).join(', ');
                                        pkgLocInput.value = item.name || item.city;
                                        pkgSuggestionsBox.classList.add('hidden');
                                    });
                                    pkgSuggestionsBox.appendChild(div);
                                });
                                pkgSuggestionsBox.classList.remove('hidden');
                            } else {
                                pkgSuggestionsBox.innerHTML = '<div class="p-3 text-xs text-slate-400 text-center font-medium">No locations found. You can type manually.</div>';
                                pkgSuggestionsBox.classList.remove('hidden');
                            }
                        })
                        .catch(() => {
                            pkgSpinner.classList.add('hidden');
                        });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!pkgLocInput.contains(e.target) && !pkgSuggestionsBox.contains(e.target)) {
                    pkgSuggestionsBox.classList.add('hidden');
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
