<?php
/**
 * Admin Dedicated Tour Package Add / Edit Controller & View
 * Full-page builder:
 * - Live Location Search & Real-Time Places Geocoding Autocomplete
 * - Travel & Transportation Mode (By Flight, By Train, By Volvo Bus, By Cab, Land Only)
 * - Duration & Token Advance Configuration
 * - Day-by-Day Dynamic Itinerary Builder
 * - Multi-Photo Tour Gallery & Cover Photo
 * - Highlights & Perks Builder
 * - Inclusions & Exclusions Checklist
 * - Linked Hotels & Accommodations
 * - 100% Flat, Sage & Cream Minimal Aesthetic (Strictly ZERO shadows)
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

$uploadDir = __DIR__ . '/../uploads/packages/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

$pkgId = isset($_GET['id']) && (int)$_GET['id'] > 0 ? (int)$_GET['id'] : null;
$isEdit = !empty($pkgId);

// 1. HANDLE POST SAVE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_package'])) {
    if ($pdo) {
        try {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            }
            $subtitle = trim($_POST['subtitle'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $stateCountry = trim($_POST['state_country'] ?? '');
            $circuitType = trim($_POST['circuit_type'] ?? 'Holiday Circuit');
            $travelMode = trim($_POST['travel_mode'] ?? 'flight');
            $departureCity = trim($_POST['departure_city'] ?? 'All Major Cities');
            $category = trim($_POST['category'] ?? 'domestic');
            $durationNights = (int)($_POST['duration_nights'] ?? 4);
            $durationDays = (int)($_POST['duration_days'] ?? 5);
            $durationText = trim($_POST['duration_text'] ?? "{$durationNights} Nights / {$durationDays} Days");
            $price = (float)($_POST['price'] ?? 0);
            $originalPrice = (float)($_POST['original_price'] ?? ($price * 1.25));
            $tokenAdvance = (float)($_POST['token_advance'] ?? 2500);
            $badge = trim($_POST['badge'] ?? 'Bestseller 2026');
            $rating = (float)($_POST['rating'] ?? 4.9);
            $reviewsCount = (int)($_POST['reviews_count'] ?? 350);
            $status = trim($_POST['status'] ?? 'active');
            $policies = trim($_POST['policies'] ?? 'Free Cancellation up to 7 days before tour departure. 100% token refund on emergency medical cancellations.');

            if (empty($title)) {
                throw new Exception("Tour Package Title is required.");
            }
            if (empty($location)) {
                throw new Exception("Tour Destination / Location is required.");
            }

            // Handle Featured Cover Image
            $featuredImage = trim($_POST['existing_featured_image'] ?? '');
            if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['featured_image_file']['tmp_name'];
                $fileName = time() . '_pkg_cover_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['featured_image_file']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $featuredImage = 'uploads/packages/' . $fileName;
                }
            } elseif (!empty($_POST['featured_image_url'])) {
                $featuredImage = trim($_POST['featured_image_url']);
            }

            // Handle Tour Gallery Photos
            $galleryPhotos = [];
            if (isset($_POST['gallery_existing']) && is_array($_POST['gallery_existing'])) {
                foreach ($_POST['gallery_existing'] as $gUrl) {
                    $u = trim($gUrl);
                    if (!empty($u)) $galleryPhotos[] = $u;
                }
            }
            if (isset($_POST['gallery_urls']) && is_array($_POST['gallery_urls'])) {
                foreach ($_POST['gallery_urls'] as $gUrl) {
                    $u = trim($gUrl);
                    if (!empty($u)) $galleryPhotos[] = $u;
                }
            }
            if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['name'])) {
                $fCount = count($_FILES['gallery_files']['name']);
                for ($fi = 0; $fi < $fCount; $fi++) {
                    if ($_FILES['gallery_files']['error'][$fi] === UPLOAD_ERR_OK) {
                        $tmp = $_FILES['gallery_files']['tmp_name'][$fi];
                        $cleanN = time() . '_pkg_gal_' . $fi . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['gallery_files']['name'][$fi]);
                        if (move_uploaded_file($tmp, $uploadDir . $cleanN)) {
                            $galleryPhotos[] = 'uploads/packages/' . $cleanN;
                        }
                    }
                }
            }
            $galleryJson = json_encode(array_values(array_unique($galleryPhotos)), JSON_UNESCAPED_UNICODE);

            // Handle Itinerary (Day-by-Day)
            $itineraryList = [];
            if (isset($_POST['itinerary']) && is_array($_POST['itinerary'])) {
                foreach ($_POST['itinerary'] as $day) {
                    if (!empty($day['title']) || !empty($day['desc'])) {
                        $itineraryList[] = [
                            'day' => trim($day['day'] ?? 'Day'),
                            'title' => trim($day['title'] ?? ''),
                            'desc' => trim($day['desc'] ?? ''),
                            'meals' => trim($day['meals'] ?? 'Breakfast & Dinner Included'),
                            'stay' => trim($day['stay'] ?? '4★ Hotel Stay')
                        ];
                    }
                }
            }
            $itineraryJson = json_encode($itineraryList, JSON_UNESCAPED_UNICODE);

            // Handle Highlights
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

            // Inclusions & Exclusions
            $inclusionsRaw = explode("\n", str_replace("\r", "", $_POST['inclusions_text'] ?? ''));
            $inclusionsList = array_values(array_filter(array_map('trim', $inclusionsRaw)));
            $inclusionsJson = json_encode($inclusionsList, JSON_UNESCAPED_UNICODE);

            $exclusionsRaw = explode("\n", str_replace("\r", "", $_POST['exclusions_text'] ?? ''));
            $exclusionsList = array_values(array_filter(array_map('trim', $exclusionsRaw)));
            $exclusionsJson = json_encode($exclusionsList, JSON_UNESCAPED_UNICODE);

            // Linked Hotels
            $hotelIds = isset($_POST['hotel_ids']) && is_array($_POST['hotel_ids']) ? array_values($_POST['hotel_ids']) : [];
            $hotelIdsJson = json_encode($hotelIds, JSON_UNESCAPED_UNICODE);

            if ($isEdit) {
                // Update Package
                $sql = "UPDATE `packages` SET
                    `title` = ?, `slug` = ?, `subtitle` = ?, `location` = ?, `state_country` = ?,
                    `circuit_type` = ?, `travel_mode` = ?, `departure_city` = ?, `duration` = ?, 
                    `duration_nights` = ?, `duration_days` = ?, `price` = ?, `original_price` = ?, 
                    `token_advance` = ?, `badge` = ?, `rating` = ?, `reviews_count` = ?, 
                    `featured_image` = ?, `gallery` = ?, `highlights` = ?, `itinerary` = ?, 
                    `inclusions` = ?, `exclusions` = ?, `hotel_ids` = ?, `policies` = ?, 
                    `category` = ?, `status` = ?
                    WHERE `id` = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $slug, $subtitle, $location, $stateCountry,
                    $circuitType, $travelMode, $departureCity, $durationText, 
                    $durationNights, $durationDays, $price, $originalPrice, 
                    $tokenAdvance, $badge, $rating, $reviewsCount, 
                    $featuredImage, $galleryJson, $highlightsJson, $itineraryJson, 
                    $inclusionsJson, $exclusionsJson, $hotelIdsJson, $policies, 
                    $category, $status, $pkgId
                ]);
                $alertMessage = "Tour Package '{$title}' updated successfully!";
            } else {
                // Insert New Package
                $sql = "INSERT INTO `packages` (
                    `title`, `slug`, `subtitle`, `location`, `state_country`,
                    `circuit_type`, `travel_mode`, `departure_city`, `duration`, 
                    `duration_nights`, `duration_days`, `price`, `original_price`, 
                    `token_advance`, `badge`, `rating`, `reviews_count`, 
                    `featured_image`, `gallery`, `highlights`, `itinerary`, 
                    `inclusions`, `exclusions`, `hotel_ids`, `policies`, 
                    `category`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $slug, $subtitle, $location, $stateCountry,
                    $circuitType, $travelMode, $departureCity, $durationText, 
                    $durationNights, $durationDays, $price, $originalPrice, 
                    $tokenAdvance, $badge, $rating, $reviewsCount, 
                    $featuredImage, $galleryJson, $highlightsJson, $itineraryJson, 
                    $inclusionsJson, $exclusionsJson, $hotelIdsJson, $policies, 
                    $category, $status
                ]);
                $pkgId = (int)$pdo->lastInsertId();
                $isEdit = true;
                $alertMessage = "Tour Package '{$title}' created successfully!";
            }

            header("Location: packages.php?saved=1");
            exit();

        } catch (Exception $e) {
            $alertMessage = "Error saving package: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// 2. FETCH RECORD FOR EDITING
$pkg = [
    'title' => '', 'slug' => '', 'subtitle' => '', 'location' => '', 'state_country' => '',
    'circuit_type' => 'Holiday Circuit', 'travel_mode' => 'flight', 'departure_city' => 'All Major Cities',
    'category' => 'domestic', 'duration_nights' => 5, 'duration_days' => 6, 'duration' => '5 Nights / 6 Days',
    'price' => 17999, 'original_price' => 22999, 'token_advance' => 2500,
    'badge' => 'Bestseller 2026', 'rating' => 4.9, 'reviews_count' => 350,
    'featured_image' => '', 'gallery' => '[]', 'highlights' => '[]', 'itinerary' => '[]',
    'inclusions' => '[]', 'exclusions' => '[]', 'hotel_ids' => '[]',
    'policies' => 'Free Cancellation up to 7 days before tour departure. 100% token refund on emergency medical cancellations.',
    'status' => 'active'
];

$itinerary = [];
$highlights = [];
$inclusions = [];
$exclusions = [];
$gallery = [];
$selectedHotelIds = [];

if ($isEdit && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM `packages` WHERE `id` = ?");
    $stmt->execute([$pkgId]);
    $fetched = $stmt->fetch();
    if ($fetched) {
        $pkg = $fetched;
        if (empty($pkg['travel_mode'])) {
            $pkg['travel_mode'] = ($pkg['category'] === 'international') ? 'flight' : 'cab';
        }
        $itinerary = json_decode($pkg['itinerary'] ?? '[]', true) ?: [];
        $highlights = json_decode($pkg['highlights'] ?? '[]', true) ?: [];
        $inclusions = json_decode($pkg['inclusions'] ?? '[]', true) ?: [];
        $exclusions = json_decode($pkg['exclusions'] ?? '[]', true) ?: [];
        $gallery = json_decode($pkg['gallery'] ?? '[]', true) ?: [];
        $selectedHotelIds = json_decode($pkg['hotel_ids'] ?? '[]', true) ?: [];
    }
}

// Fetch all available hotels from database for linking
$availableHotels = [];
if ($pdo) {
    try {
        $availableHotels = $pdo->query("SELECT id, name, city, star_rating, starting_price FROM `hotels` WHERE `status` = 'active' ORDER BY city ASC, name ASC")->fetchAll();
    } catch (Exception $e) {
        $availableHotels = [];
    }
}

// Default standard inclusions checklist if empty
$inclusionsText = !empty($inclusions) ? implode("\n", $inclusions) : "Pick-up and drop from Airport / Station in private dedicated AC vehicle\nLuxury hotel stays in verified 4-Star / 5-Star properties\nDaily buffet breakfast and chef-curated dinner included\nAll destination sightseeing excursions as per itinerary\nAll interstate toll taxes, state parking charges, fuel, and driver night allowances\n24/7 on-ground tour manager support";

$exclusionsText = !empty($exclusions) ? implode("\n", $exclusions) : "Personal expenses, laundry, and telephone calls\nOptional adventure activity charges (e.g. Paragliding, Scuba, Cable car)\nMonument entry tickets not mentioned in inclusions";

$pageTitle = $isEdit ? "Edit Tour Package: " . htmlspecialchars($pkg['title']) : "Add New Tour Package";
include 'components/head.php';
?>

<!-- App Wrapper -->
<div class="min-h-screen flex bg-cream-100/70 antialiased selection:bg-sage-600 selection:text-white">
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 xl:pl-68 flex flex-col min-w-0 transition-all">
        <!-- Header -->
        <?php include 'components/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6 lg:p-7 w-full max-w-5xl space-y-6">

            <!-- Breadcrumb Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border border-[#e5e4dc] p-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-[11px] text-slate-400">
                        <a href="packages.php" class="text-sage-700 hover:underline font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Back to Tour Packages</span>
                        </a>
                        <span>/</span>
                        <span><?php echo $isEdit ? 'Edit Tour' : 'New Tour'; ?></span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold font-space text-slate-900 tracking-tight">
                        <?php echo $isEdit ? 'Edit Tour Package &amp; Itinerary' : 'Create New Holiday &amp; Tour Package'; ?>
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <a href="packages.php" class="px-3.5 py-1.5 text-xs font-semibold bg-white hover:bg-cream-100 text-slate-700 border border-[#e5e4dc] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" form="packageForm" class="px-4 py-1.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white border border-sage-800 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                        <span>Save Tour Package</span>
                    </button>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-3.5 text-xs font-semibold flex items-center gap-2.5 <?php echo $alertType === 'success' ? 'bg-sage-50 text-sage-900 border border-sage-300' : 'bg-rose-50 text-rose-900 border border-rose-200'; ?>">
                    <i class="fa-solid <?php echo $alertType === 'success' ? 'fa-circle-check text-sage-700' : 'fa-circle-exclamation text-rose-600'; ?>"></i>
                    <span><?php echo htmlspecialchars($alertMessage); ?></span>
                </div>
            <?php endif; ?>

            <!-- Main Tour Package Form -->
            <form id="packageForm" method="POST" action="package-edit.php<?php echo $isEdit ? '?id=' . $pkgId : ''; ?>" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="save_package" value="1">
                <input type="hidden" name="package_id" value="<?php echo htmlspecialchars((string)($pkgId ?? '')); ?>">

                <!-- Section 1: Basic Tour Information -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Basic Tour Details &amp; Category</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Tour Package Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="packageTitle" value="<?php echo htmlspecialchars($pkg['title']); ?>" required placeholder="e.g. Kashmir Paradise: Shikara, Snow Peaks & Dal Houseboat" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-semibold">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-800 mb-1">Tour Subtitle / Summary</label>
                            <input type="text" name="subtitle" value="<?php echo htmlspecialchars($pkg['subtitle']); ?>" placeholder="e.g. Explore Switzerland of India with private chauffeured transfers, gondola assistance, and houseboat stay." class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Package Category</label>
                            <select name="category" id="pkgCategory" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium" onchange="autoSelectTravelMode(this.value)">
                                <option value="domestic" <?php echo ($pkg['category'] === 'domestic') ? 'selected' : ''; ?>>Domestic Holiday Circuit</option>
                                <option value="international" <?php echo ($pkg['category'] === 'international') ? 'selected' : ''; ?>>International Holiday Circuit (Flight)</option>
                                <option value="adventure" <?php echo ($pkg['category'] === 'adventure') ? 'selected' : ''; ?>>Adventure &amp; Trekking</option>
                                <option value="luxury" <?php echo ($pkg['category'] === 'luxury') ? 'selected' : ''; ?>>Luxury Honeymoon &amp; Royal Stays</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Circuit Type / Theme</label>
                            <input type="text" name="circuit_type" value="<?php echo htmlspecialchars($pkg['circuit_type']); ?>" placeholder="e.g. Beach &amp; Party Circuit, Alpine Snow Trail, Royal Heritage" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Marketing Badge Pill</label>
                            <input type="text" name="badge" value="<?php echo htmlspecialchars($pkg['badge']); ?>" placeholder="e.g. Bestseller 2026, Orion Signature, 50% Off" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Status</label>
                            <select name="status" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                                <option value="active" <?php echo ($pkg['status'] === 'active') ? 'selected' : ''; ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?php echo ($pkg['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive (Draft Mode)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Travel Mode & Transportation -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Travel &amp; Transportation Medium</h2>
                        </div>
                        <span class="text-[10px] text-slate-500">Connected to Search Filters (By Flight, By Train, By Bus, By Cab)</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-2.5">
                        <!-- Flight Option -->
                        <label class="flex flex-col items-center justify-center p-3 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer text-center select-none travel-mode-card transition-colors">
                            <input type="radio" name="travel_mode" value="flight" <?php echo ($pkg['travel_mode'] === 'flight') ? 'checked' : ''; ?> class="accent-sage-700 mb-2">
                            <i class="fa-solid fa-plane-departure text-sky-600 text-lg mb-1"></i>
                            <span class="text-xs font-bold text-slate-800">By Flight</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Airfare Included</span>
                        </label>

                        <!-- Train Option -->
                        <label class="flex flex-col items-center justify-center p-3 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer text-center select-none travel-mode-card transition-colors">
                            <input type="radio" name="travel_mode" value="train" <?php echo ($pkg['travel_mode'] === 'train') ? 'checked' : ''; ?> class="accent-sage-700 mb-2">
                            <i class="fa-solid fa-train text-amber-600 text-lg mb-1"></i>
                            <span class="text-xs font-bold text-slate-800">By Train</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Rail Circuit</span>
                        </label>

                        <!-- Bus / Volvo Option -->
                        <label class="flex flex-col items-center justify-center p-3 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer text-center select-none travel-mode-card transition-colors">
                            <input type="radio" name="travel_mode" value="bus" <?php echo ($pkg['travel_mode'] === 'bus') ? 'checked' : ''; ?> class="accent-sage-700 mb-2">
                            <i class="fa-solid fa-bus text-emerald-600 text-lg mb-1"></i>
                            <span class="text-xs font-bold text-slate-800">By Volvo Bus</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Luxury Coach</span>
                        </label>

                        <!-- Private Cab Option -->
                        <label class="flex flex-col items-center justify-center p-3 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer text-center select-none travel-mode-card transition-colors">
                            <input type="radio" name="travel_mode" value="cab" <?php echo ($pkg['travel_mode'] === 'cab') ? 'checked' : ''; ?> class="accent-sage-700 mb-2">
                            <i class="fa-solid fa-car text-sage-700 text-lg mb-1"></i>
                            <span class="text-xs font-bold text-slate-800">By Private Cab</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Road Trip / Cab</span>
                        </label>

                        <!-- Land Only Option -->
                        <label class="flex flex-col items-center justify-center p-3 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer text-center select-none travel-mode-card transition-colors">
                            <input type="radio" name="travel_mode" value="land_only" <?php echo ($pkg['travel_mode'] === 'land_only') ? 'checked' : ''; ?> class="accent-sage-700 mb-2">
                            <i class="fa-solid fa-hotel text-indigo-600 text-lg mb-1"></i>
                            <span class="text-xs font-bold text-slate-800">Land Only</span>
                            <span class="text-[9px] text-slate-400 mt-0.5">Stays &amp; Sightseeing</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-800 mb-1">Departure / Boarding City <span class="text-slate-400 font-normal">(e.g. "Ex-Delhi", "Ex-Mumbai", "All Major Indian Hubs")</span></label>
                        <input type="text" name="departure_city" value="<?php echo htmlspecialchars($pkg['departure_city'] ?? 'All Major Cities'); ?>" placeholder="e.g. Ex-Delhi / Ex-Mumbai / All Major Cities" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                    </div>
                </div>

                <!-- Section 3: Destination & Location -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Destination &amp; Location Circuit</h2>
                        </div>
                        <span class="text-[10px] text-sage-800 bg-sage-50 border border-sage-200 px-2 py-0.5 font-bold">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1 text-sage-600"></i> Real Geocoding API Auto-Fill
                        </span>
                    </div>

                    <!-- Autocomplete Search -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-800 mb-1">Search Real Places / Cities to Auto-Fill</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="pkgLocSearchInput" autocomplete="off" placeholder="Type real city or place e.g. Srinagar, Manali, Goa, Dubai, Bali, Switzerland..." class="w-full pl-8 pr-10 py-2.5 text-xs bg-cream-50/80 border border-[#e5e4dc] focus:border-sage-700 focus:bg-white outline-none font-medium">
                            <div id="pkgLocSearchSpinner" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fa-solid fa-circle-notch fa-spin text-xs text-sage-700"></i>
                            </div>
                        </div>
                        <div id="pkgLocSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-[#e5e4dc] shadow-lg max-h-60 overflow-y-auto z-50 divide-y divide-[#e5e4dc]"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Tour Circuit Locations <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="location" id="pkgLocation" value="<?php echo htmlspecialchars($pkg['location']); ?>" required placeholder="e.g. Srinagar • Gulmarg • Pahalgam • Sonmarg" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-semibold">
                            <p class="text-[10px] text-slate-400 mt-1">Key stops and cities covered during this tour.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">State / Country</label>
                            <input type="text" name="state_country" id="pkgStateCountry" value="<?php echo htmlspecialchars($pkg['state_country']); ?>" placeholder="e.g. Jammu &amp; Kashmir, India" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Duration, Pricing & Token Advance -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Duration, Pricing &amp; Token Advance</h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Nights</label>
                            <input type="number" name="duration_nights" id="durNights" value="<?php echo htmlspecialchars($pkg['duration_nights']); ?>" min="1" max="30" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] font-bold text-center" oninput="syncDuration()">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Days</label>
                            <input type="number" name="duration_days" id="durDays" value="<?php echo htmlspecialchars($pkg['duration_days']); ?>" min="1" max="31" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] font-bold text-center" oninput="syncDuration()">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Tour Price / Person (₹) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="price" value="<?php echo htmlspecialchars($pkg['price']); ?>" required class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] font-bold text-sage-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Original Price (₹)</label>
                            <input type="number" name="original_price" value="<?php echo htmlspecialchars($pkg['original_price']); ?>" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] text-slate-500">
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-800 mb-1">Token Advance (₹)</label>
                            <input type="number" name="token_advance" value="<?php echo htmlspecialchars($pkg['token_advance']); ?>" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] font-bold text-amber-800 bg-amber-50/50">
                        </div>

                        <input type="hidden" name="duration_text" id="durationText" value="<?php echo htmlspecialchars($pkg['duration']); ?>">
                    </div>
                </div>

                <!-- Section 5: Cover Photo & Multi-Photo Tour Gallery -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">5. Cover Photo &amp; Multi-Photo Tour Gallery</h2>
                        </div>
                        <span class="text-[10px] text-slate-500">Rendered in 5-Photo Luxury Magazine Grid on Frontend</span>
                    </div>

                    <!-- Part A: Main Cover Photo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-cream-50 p-3.5 border border-[#e5e4dc]">
                        <div class="md:col-span-2 space-y-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Tour Cover Image URL</label>
                                <input type="url" name="featured_image_url" id="pkgCoverUrl" value="<?php echo htmlspecialchars($pkg['featured_image']); ?>" placeholder="https://images.unsplash.com/..." class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium" oninput="updatePkgCoverPreview(this.value)">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Or Upload Local Image File</label>
                                <input type="file" name="featured_image_file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:border file:border-[#e5e4dc] file:text-[11px] file:font-semibold file:bg-white hover:file:bg-cream-100 file:cursor-pointer">
                                <input type="hidden" name="existing_featured_image" value="<?php echo htmlspecialchars($pkg['featured_image']); ?>">
                            </div>
                        </div>
                        <div class="h-28 bg-white border border-[#e5e4dc] flex items-center justify-center overflow-hidden relative">
                            <img id="pkgCoverPreview" src="<?php echo !empty($pkg['featured_image']) ? htmlspecialchars($pkg['featured_image']) : 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=600&q=80'; ?>" alt="Cover" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 right-1 bg-slate-900/80 text-white text-[9px] px-1.5 py-0.5 font-bold">Cover Preview</span>
                        </div>
                    </div>

                    <!-- Part B: Tour Gallery Photos -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-xs font-bold text-slate-800">
                                    Tour Gallery Photos <span class="text-slate-400 font-normal">(Scenic stops, monuments, activities, houseboats)</span>
                                </label>
                            </div>
                            <button type="button" onclick="addPkgGalleryUrlRow()" class="px-2.5 py-1 text-xs font-bold bg-cream-50 text-slate-800 border border-[#e5e4dc] hover:bg-cream-100 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-link text-[10px] text-sage-700"></i>
                                <span>Add Photo URL</span>
                            </button>
                        </div>

                        <!-- Multi-file Upload Box -->
                        <div class="p-3 bg-white border border-dashed border-sage-400 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-sage-50 text-sage-700 border border-sage-200 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Upload Multiple Tour Photos</div>
                                    <div class="text-[10px] text-slate-400">Select multiple JPG, PNG, or WebP images from your computer</div>
                                </div>
                            </div>
                            <input type="file" name="gallery_files[]" multiple accept="image/*" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:border file:border-sage-300 file:text-xs file:font-bold file:bg-sage-50 file:text-sage-800 hover:file:bg-sage-100 file:cursor-pointer">
                        </div>

                        <!-- Gallery Photos Grid -->
                        <div id="pkgGalleryContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            <?php if (!empty($gallery)): ?>
                                <?php foreach ($gallery as $gi => $gUrl): ?>
                                    <div class="pkg-gal-card bg-white border border-[#e5e4dc] p-2 space-y-2 relative group">
                                        <div class="h-28 bg-cream-50 border border-[#e5e4dc] overflow-hidden">
                                            <img src="<?php echo htmlspecialchars($gUrl); ?>" alt="Gallery" class="w-full h-full object-cover">
                                        </div>
                                        <input type="hidden" name="gallery_existing[]" value="<?php echo htmlspecialchars($gUrl); ?>">
                                        <div class="text-[10px] text-slate-500 truncate"><?php echo htmlspecialchars($gUrl); ?></div>
                                        <button type="button" onclick="this.closest('.pkg-gal-card').remove()" class="absolute top-3 right-3 w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] shadow-sm transition-colors" title="Delete Photo">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Dynamic Day-by-Day Itinerary Builder -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">6. Day-by-Day Tour Itinerary Builder</h2>
                        </div>
                        <button type="button" onclick="addNewItineraryDay()" class="px-3 py-1.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Next Day</span>
                        </button>
                    </div>

                    <div id="itineraryContainer" class="space-y-4">
                        <?php if (!empty($itinerary)): ?>
                            <?php foreach ($itinerary as $di => $day): ?>
                                <div class="itinerary-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group">
                                    <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-sage-800 uppercase tracking-wider"><?php echo htmlspecialchars($day['day'] ?? 'Day ' . ($di + 1)); ?></span>
                                            <input type="hidden" name="itinerary[<?php echo $di; ?>][day]" value="<?php echo htmlspecialchars($day['day'] ?? 'Day ' . ($di + 1)); ?>">
                                        </div>
                                        <button type="button" onclick="this.closest('.itinerary-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Day">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            <span>Delete Day</span>
                                        </button>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Day Title / Headline <span class="text-rose-500">*</span></label>
                                        <input type="text" name="itinerary[<?php echo $di; ?>][title]" value="<?php echo htmlspecialchars($day['title']); ?>" required placeholder="e.g. Arrival at Srinagar Airport &amp; Romantic Dal Lake Shikara Sunset" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Activities &amp; Sightseeing Schedule</label>
                                        <textarea name="itinerary[<?php echo $di; ?>][desc]" rows="3" placeholder="Describe the day's sightseeing, scenic drives, landmarks, and highlights..." class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium"><?php echo htmlspecialchars($day['desc']); ?></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meals Included</label>
                                            <input type="text" name="itinerary[<?php echo $di; ?>][meals]" value="<?php echo htmlspecialchars($day['meals'] ?? 'Breakfast & Dinner Included'); ?>" placeholder="Breakfast & Dinner Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Overnight Stay</label>
                                            <input type="text" name="itinerary[<?php echo $di; ?>][stay]" value="<?php echo htmlspecialchars($day['stay'] ?? '4★ Hotel Stay'); ?>" placeholder="4★ Hotel in Srinagar" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default Initial Day 1 -->
                            <div class="itinerary-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group">
                                <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                                    <span class="text-xs font-bold text-sage-800 uppercase tracking-wider">Day 01</span>
                                    <input type="hidden" name="itinerary[0][day]" value="Day 01">
                                    <button type="button" onclick="this.closest('.itinerary-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Day">
                                        <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        <span>Delete Day</span>
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Day Title / Headline <span class="text-rose-500">*</span></label>
                                    <input type="text" name="itinerary[0][title]" value="Arrival &amp; Welcome Reception" required placeholder="e.g. Arrival at Destination &amp; Evening Leisure" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Activities &amp; Sightseeing Schedule</label>
                                    <textarea name="itinerary[0][desc]" rows="3" placeholder="Describe arrival transfer, hotel check-in, sunset views, or evening stroll..." class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">Pick-up from Airport/Railway station by dedicated private AC cab. Transfer to your pre-booked luxury stay, check in, and enjoy evening leisure with welcome tea.</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meals Included</label>
                                        <input type="text" name="itinerary[0][meals]" value="Dinner Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Overnight Stay</label>
                                        <input type="text" name="itinerary[0][stay]" value="4★ Luxury Resort" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 7: Linked Hotels & Accommodations -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">7. Linked Accommodations &amp; Hotels</h2>
                        </div>
                        <span class="text-[10px] text-slate-400">Select properties from your GuideFlux hotel database</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-64 overflow-y-auto p-1 border border-[#e5e4dc]">
                        <?php if (!empty($availableHotels)): ?>
                            <?php foreach ($availableHotels as $ht): ?>
                                <?php $isLinked = in_array($ht['id'], $selectedHotelIds); ?>
                                <label class="flex items-start gap-2.5 p-2.5 bg-cream-50/60 border border-[#e5e4dc] hover:bg-cream-100 cursor-pointer select-none transition-colors">
                                    <input type="checkbox" name="hotel_ids[]" value="<?php echo $ht['id']; ?>" <?php echo $isLinked ? 'checked' : ''; ?> class="mt-0.5 accent-sage-700">
                                    <div class="text-xs min-w-0">
                                        <div class="font-bold text-slate-800 truncate"><?php echo htmlspecialchars($ht['name']); ?></div>
                                        <div class="text-[10px] text-slate-500"><?php echo htmlspecialchars($ht['city']); ?> • <?php echo $ht['star_rating']; ?>★</div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-3 text-center text-xs text-slate-400 p-4">No active hotels in database. You can add hotels under Hotel Management.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 8: Tour Inclusions & Exclusions -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">8. Tour Inclusions &amp; Exclusions</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Inclusions <span class="text-slate-400 font-normal">(One item per line)</span>
                            </label>
                            <textarea name="inclusions_text" rows="6" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium leading-relaxed"><?php echo htmlspecialchars($inclusionsText); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Exclusions <span class="text-slate-400 font-normal">(One item per line)</span>
                            </label>
                            <textarea name="exclusions_text" rows="6" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium leading-relaxed"><?php echo htmlspecialchars($exclusionsText); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 9: Policies & Booking Terms -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">9. Cancellation &amp; Booking Policies</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Cancellation Policy &amp; Terms</label>
                        <textarea name="policies" rows="3" placeholder="Specify token refund rules, cancellation deadlines, and payment schedules..." class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium"><?php echo htmlspecialchars($pkg['policies']); ?></textarea>
                    </div>
                </div>

                <!-- Bottom Save Bar -->
                <div class="flex items-center justify-between p-4 bg-white border border-[#e5e4dc]">
                    <a href="packages.php" class="px-4 py-2 text-xs font-semibold bg-white hover:bg-cream-100 text-slate-700 border border-[#e5e4dc] transition-colors">
                        Cancel &amp; Return
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white border border-sage-800 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span><?php echo $isEdit ? 'Save Tour Changes' : 'Publish Tour Package'; ?></span>
                    </button>
                </div>

            </form>

        </main>
    </div>
</div>

<script>
    let dayIndex = <?php echo max(count($itinerary), 1); ?>;
    let galIndex = <?php echo max(count($gallery), 1); ?>;

    function autoSelectTravelMode(cat) {
        if (cat === 'international') {
            const flightRadio = document.querySelector('input[name="travel_mode"][value="flight"]');
            if (flightRadio) flightRadio.checked = true;
        }
    }

    function syncDuration() {
        const n = document.getElementById('durNights').value || 1;
        const d = document.getElementById('durDays').value || 2;
        const txt = document.getElementById('durationText');
        if (txt) {
            txt.value = `${n} Nights / ${d} Days`;
        }
    }

    function updatePkgCoverPreview(url) {
        const prev = document.getElementById('pkgCoverPreview');
        if (prev && url && url.trim().length > 5) {
            prev.src = url.trim();
        }
    }

    function addPkgGalleryUrlRow() {
        galIndex++;
        const container = document.getElementById('pkgGalleryContainer');
        const card = document.createElement('div');
        card.className = 'pkg-gal-card bg-white border border-[#e5e4dc] p-2 space-y-2 relative group';
        card.innerHTML = `
            <div class="h-28 bg-cream-50 border border-[#e5e4dc] overflow-hidden flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=600&q=80" alt="Gallery Preview" class="w-full h-full object-cover pkg-gal-img">
            </div>
            <div>
                <label class="block text-[9px] font-bold text-slate-500 mb-0.5">Image URL</label>
                <input type="url" name="gallery_urls[]" required placeholder="https://images.unsplash.com/..." class="w-full text-[11px] p-1.5 bg-white border border-[#e5e4dc]" oninput="this.closest('.pkg-gal-card').querySelector('.pkg-gal-img').src = this.value">
            </div>
            <button type="button" onclick="this.closest('.pkg-gal-card').remove()" class="absolute top-3 right-3 w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] shadow-sm transition-colors" title="Delete Photo">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        `;
        container.appendChild(card);
    }

    function addNewItineraryDay() {
        dayIndex++;
        const container = document.getElementById('itineraryContainer');
        const div = document.createElement('div');
        div.className = 'itinerary-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group';
        const dayLabel = 'Day ' + (dayIndex < 10 ? '0' + dayIndex : dayIndex);
        div.innerHTML = `
            <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                <span class="text-xs font-bold text-sage-800 uppercase tracking-wider">${dayLabel}</span>
                <input type="hidden" name="itinerary[${dayIndex}][day]" value="${dayLabel}">
                <button type="button" onclick="this.closest('.itinerary-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Day">
                    <i class="fa-solid fa-trash-can text-[11px]"></i>
                    <span>Delete Day</span>
                </button>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Day Title / Headline <span class="text-rose-500">*</span></label>
                <input type="text" name="itinerary[${dayIndex}][title]" required placeholder="e.g. Full Day Sightseeing &amp; Valley Excursions" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Activities &amp; Sightseeing Schedule</label>
                <textarea name="itinerary[${dayIndex}][desc]" rows="3" placeholder="Describe the day's sightseeing, scenic drives, landmarks, and highlights..." class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meals Included</label>
                    <input type="text" name="itinerary[${dayIndex}][meals]" value="Breakfast &amp; Dinner Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Overnight Stay</label>
                    <input type="text" name="itinerary[${dayIndex}][stay]" value="4★ Hotel Stay" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // Live Real-Time Places Geocoding Autocomplete
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('pkgLocSearchInput');
        const suggestionsBox = document.getElementById('pkgLocSuggestions');
        const spinner = document.getElementById('pkgLocSearchSpinner');
        const locField = document.getElementById('pkgLocation');
        const stateCountryField = document.getElementById('pkgStateCountry');
        const titleField = document.getElementById('packageTitle');

        let debounceTimer = null;
        if (!input || !suggestionsBox) return;

        input.addEventListener('input', function() {
            const val = this.value.trim();
            clearTimeout(debounceTimer);

            if (val.length < 2) {
                suggestionsBox.classList.add('hidden');
                suggestionsBox.innerHTML = '';
                return;
            }

            if (spinner) spinner.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                fetch(`../api/places.php?q=${encodeURIComponent(val)}&type=package`)
                    .then(res => res.json())
                    .then(data => {
                        if (spinner) spinner.classList.add('hidden');
                        if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                            renderSuggestions(data.data);
                        } else {
                            suggestionsBox.innerHTML = '<div class="p-3 text-xs text-slate-400 text-center">No matching places found. You can enter manually below.</div>';
                            suggestionsBox.classList.remove('hidden');
                        }
                    })
                    .catch(err => {
                        if (spinner) spinner.classList.add('hidden');
                        suggestionsBox.classList.add('hidden');
                    });
            }, 250);
        });

        function renderSuggestions(items) {
            suggestionsBox.innerHTML = '';
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-cream-100 cursor-pointer flex items-center justify-between gap-3 transition-colors';
                
                const hasDbPill = item.has_packages ? `<span class="text-[9px] font-bold bg-sage-50 text-sage-800 border border-sage-200 px-1.5 py-0.5">Tour in DB</span>` : '';

                div.innerHTML = `
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-6 h-6 bg-sage-50 text-sage-700 border border-sage-200 flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-800 truncate">${escapeHtml(item.name || item.city)}</div>
                            <div class="text-[10px] text-slate-400 truncate">${escapeHtml(item.full_address || '')}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        ${hasDbPill}
                        <span class="text-[10px] text-sage-700 font-bold hover:underline flex items-center gap-1">
                            <span>Auto-Fill</span>
                            <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </span>
                    </div>
                `;

                div.addEventListener('click', function() {
                    if (locField) locField.value = item.name || item.city;
                    if (stateCountryField) {
                        const scParts = [];
                        if (item.state) scParts.push(item.state);
                        if (item.country) scParts.push(item.country);
                        stateCountryField.value = scParts.join(', ') || 'India';
                    }
                    if (titleField && !titleField.value.trim()) {
                        titleField.value = `${item.name || item.city} Luxury Holiday Circuit`;
                    }
                    input.value = item.full_address || item.name;
                    suggestionsBox.classList.add('hidden');

                    [locField, stateCountryField].forEach(f => {
                        if (f) {
                            f.classList.add('bg-sage-50', 'border-sage-600');
                            setTimeout(() => f.classList.remove('bg-sage-50', 'border-sage-600'), 1200);
                        }
                    });
                });
                suggestionsBox.appendChild(div);
            });
            suggestionsBox.classList.remove('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    });
</script>

<?php include 'components/footer.php'; ?>
