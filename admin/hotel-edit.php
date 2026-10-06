<?php
/**
 * Admin Dedicated Add / Edit Hotel Controller & View
 * Clean full-page form with zero popup confusion, instant visual hints,
 * location autocomplete, dynamic room builder with room photos, and multi-photo resort gallery.
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

$uploadDir = __DIR__ . '/../uploads/hotels/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

$hotelId = isset($_GET['id']) && (int)$_GET['id'] > 0 ? (int)$_GET['id'] : null;
$isEdit = !empty($hotelId);

// 1. HANDLE POST SAVE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_hotel'])) {
    if ($pdo) {
        try {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }
            $propertyType = trim($_POST['property_type'] ?? 'Luxury Resort');
            $starRating = (float)($_POST['star_rating'] ?? 5.0);
            $city = trim($_POST['city'] ?? '');
            $state = trim($_POST['state'] ?? '');
            $country = trim($_POST['country'] ?? 'India');
            $address = trim($_POST['address'] ?? '');
            $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
            $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
            $startingPrice = (float)($_POST['starting_price'] ?? 0);
            $originalPrice = (float)($_POST['original_price'] ?? ($startingPrice * 1.25));
            $discountPercent = $originalPrice > $startingPrice ? round((($originalPrice - $startingPrice) / $originalPrice) * 100) : 0;
            $badge = trim($_POST['badge'] ?? '5★ Luxury');
            $status = trim($_POST['status'] ?? 'active');
            $checkinTime = trim($_POST['checkin_time'] ?? '02:00 PM');
            $checkoutTime = trim($_POST['checkout_time'] ?? '11:00 AM');
            $description = trim($_POST['description'] ?? '');
            $policies = trim($_POST['policies'] ?? 'Free Cancellation up to 48 hours before check-in.');

            // Process Amenities Array to JSON
            $amenitiesPost = isset($_POST['amenities']) && is_array($_POST['amenities']) ? array_values($_POST['amenities']) : [];
            $amenitiesJson = json_encode($amenitiesPost, JSON_UNESCAPED_UNICODE);

            // Handle Featured Cover Image Upload or URL
            $featuredImage = trim($_POST['existing_featured_image'] ?? '');
            if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['featured_image_file']['tmp_name'];
                $fileName = time() . '_cover_' . preg_replace('/[^A-Za-z0-9._-]/', '', $_FILES['featured_image_file']['name']);
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
            if (empty($city)) {
                throw new Exception("Destination City is required.");
            }

            if ($isEdit) {
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
                $isEdit = true;
                $alertMessage = "Hotel '{$name}' created successfully!";
            }

            // 2. HANDLE RESORT GALLERY IMAGES (hotel_images table)
            // A. Collect existing gallery images retained
            $finalGallery = [];
            if (isset($_POST['gallery_existing']) && is_array($_POST['gallery_existing'])) {
                foreach ($_POST['gallery_existing'] as $gRow) {
                    $url = trim($gRow['url'] ?? '');
                    $caption = trim($gRow['caption'] ?? '');
                    if (!empty($url)) {
                        $finalGallery[] = [
                            'url' => $url,
                            'caption' => $caption
                        ];
                    }
                }
            }

            // B. Collect new URL-based gallery images
            if (isset($_POST['gallery_urls']) && is_array($_POST['gallery_urls'])) {
                foreach ($_POST['gallery_urls'] as $gUrlRow) {
                    $url = trim($gUrlRow['url'] ?? '');
                    $caption = trim($gUrlRow['caption'] ?? '');
                    if (!empty($url)) {
                        $finalGallery[] = [
                            'url' => $url,
                            'caption' => $caption
                        ];
                    }
                }
            }

            // C. Handle multiple file uploads for resort gallery
            if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['name'])) {
                $fileCount = count($_FILES['gallery_files']['name']);
                for ($fi = 0; $fi < $fileCount; $fi++) {
                    if ($_FILES['gallery_files']['error'][$fi] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['gallery_files']['tmp_name'][$fi];
                        $origName = $_FILES['gallery_files']['name'][$fi];
                        $cleanName = time() . '_gal_' . $fi . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $origName);
                        $targetPath = $uploadDir . $cleanName;
                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $finalGallery[] = [
                                'url' => 'uploads/hotels/' . $cleanName,
                                'caption' => pathinfo($origName, PATHINFO_FILENAME)
                            ];
                        }
                    }
                }
            }

            // Save gallery images to database
            $pdo->prepare("DELETE FROM `hotel_images` WHERE `hotel_id` = ?")->execute([$hotelId]);
            if (!empty($finalGallery)) {
                $gInsert = $pdo->prepare("INSERT INTO `hotel_images` (`hotel_id`, `image_url`, `caption`, `sort_order`) VALUES (?, ?, ?, ?)");
                foreach ($finalGallery as $sIdx => $gItem) {
                    $gInsert->execute([$hotelId, $gItem['url'], $gItem['caption'] ?: 'Resort View', $sIdx]);
                }
            }

            // 3. HANDLE DYNAMIC ROOM CATEGORIES WITH ROOM IMAGES (hotel_rooms table)
            if (isset($_POST['rooms']) && is_array($_POST['rooms'])) {
                $pdo->prepare("DELETE FROM `hotel_rooms` WHERE `hotel_id` = ?")->execute([$hotelId]);
                $roomStmt = $pdo->prepare("INSERT INTO `hotel_rooms` (
                    `hotel_id`, `room_name`, `room_type`, `price_per_night`, `original_price`, 
                    `max_adults`, `max_children`, `bed_type`, `room_size`, `meal_plan`, `image_url`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')");

                foreach ($_POST['rooms'] as $rIdx => $room) {
                    $rName = trim($room['room_name'] ?? '');
                    if (!empty($rName)) {
                        $rType = trim($room['room_type'] ?? 'Deluxe Room');
                        $rPrice = (float)($room['price_per_night'] ?? $startingPrice);
                        $rOrig = (float)($room['original_price'] ?? ($rPrice * 1.25));
                        $rAdults = (int)($room['max_adults'] ?? 2);
                        $rChildren = (int)($room['max_children'] ?? 1);
                        $rBed = trim($room['bed_type'] ?? '1 King Bed');
                        $rSize = trim($room['room_size'] ?? '380 sq ft');
                        $rMeal = trim($room['meal_plan'] ?? 'Free Breakfast Included');

                        // Room Image Handling (File Upload or URL or Existing)
                        $rImage = trim($room['image_url'] ?? '');
                        if (empty($rImage)) {
                            $rImage = trim($room['existing_image'] ?? '');
                        }

                        // Check if file uploaded specifically for this room
                        if (isset($_FILES['room_files']) && isset($_FILES['room_files']['error'][$rIdx]) && $_FILES['room_files']['error'][$rIdx] === UPLOAD_ERR_OK) {
                            $rTmp = $_FILES['room_files']['tmp_name'][$rIdx];
                            $rOrigFile = $_FILES['room_files']['name'][$rIdx];
                            $rCleanName = time() . '_room_' . $rIdx . '_' . preg_replace('/[^A-Za-z0-9._-]/', '', $rOrigFile);
                            if (move_uploaded_file($rTmp, $uploadDir . $rCleanName)) {
                                $rImage = 'uploads/hotels/' . $rCleanName;
                            }
                        }

                        if (empty($rImage)) {
                            $rImage = $featuredImage ?: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80';
                        }

                        $roomStmt->execute([
                            $hotelId, $rName, $rType, $rPrice, $rOrig,
                            $rAdults, $rChildren, $rBed, $rSize, $rMeal, $rImage
                        ]);
                    }
                }
            }

            // Redirect back with success flag
            header("Location: hotels.php?saved=1");
            exit();

        } catch (Exception $e) {
            $alertMessage = "Error saving hotel: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// 2. FETCH HOTEL RECORD FOR EDITING
$hotel = [
    'name' => '', 'slug' => '', 'star_rating' => 5, 'property_type' => '5★ Luxury Resort',
    'city' => '', 'state' => '', 'country' => 'India', 'address' => '',
    'latitude' => '', 'longitude' => '', 'featured_image' => '',
    'starting_price' => 7500, 'original_price' => 9500, 'badge' => '5★ Luxury',
    'status' => 'active', 'checkin_time' => '02:00 PM', 'checkout_time' => '11:00 AM',
    'description' => '', 'policies' => 'Free Cancellation up to 48 hours before check-in. Kids under 6 stay free.',
    'amenities' => ''
];
$rooms = [];
$galleryImages = [];

if ($isEdit && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM `hotels` WHERE `id` = ?");
    $stmt->execute([$hotelId]);
    $fetched = $stmt->fetch();
    if ($fetched) {
        $hotel = $fetched;
        
        // Fetch Rooms
        $rStmt = $pdo->prepare("SELECT * FROM `hotel_rooms` WHERE `hotel_id` = ? ORDER BY `id` ASC");
        $rStmt->execute([$hotelId]);
        $rooms = $rStmt->fetchAll();

        // Fetch Gallery Images
        $gStmt = $pdo->prepare("SELECT * FROM `hotel_images` WHERE `hotel_id` = ? ORDER BY `sort_order` ASC, `id` ASC");
        $gStmt->execute([$hotelId]);
        $galleryImages = $gStmt->fetchAll();
    }
}

// Parse selected amenities
$selectedAmenities = [];
if (!empty($hotel['amenities'])) {
    $dec = json_decode($hotel['amenities'], true);
    if (is_array($dec)) {
        foreach ($dec as $item) {
            if (is_array($item) && isset($item['name'])) {
                $selectedAmenities[] = $item['name'];
            } elseif (is_string($item)) {
                $selectedAmenities[] = $item;
            }
        }
    }
}

// Standard Available Amenities Catalog
$standardAmenities = [
    ['name' => 'Free Breakfast', 'icon' => 'fa-solid fa-mug-saucer', 'desc' => 'Daily buffet or continental breakfast'],
    ['name' => 'Swimming Pool', 'icon' => 'fa-solid fa-water-ladder', 'desc' => 'Infinity or outdoor pool access'],
    ['name' => 'Free High-Speed Wi-Fi', 'icon' => 'fa-solid fa-wifi', 'desc' => 'High speed internet in all rooms'],
    ['name' => 'Spa & Wellness', 'icon' => 'fa-solid fa-spa', 'desc' => 'Ayurvedic treatments & massage center'],
    ['name' => 'Beachfront / Ocean View', 'icon' => 'fa-solid fa-umbrella-beach', 'desc' => 'Direct walking access to beach'],
    ['name' => 'Mountain / Valley View', 'icon' => 'fa-solid fa-mountain-sun', 'desc' => 'Balcony with alpine snow/valley view'],
    ['name' => 'Fitness Center / Gym', 'icon' => 'fa-solid fa-dumbbell', 'desc' => 'Cardio equipment & weights room'],
    ['name' => 'Fine Dining Restaurant', 'icon' => 'fa-solid fa-utensils', 'desc' => 'Multi-cuisine in-house chef restaurant'],
    ['name' => 'Bar & Lounge', 'icon' => 'fa-solid fa-martini-glass-citrus', 'desc' => 'Sunset cocktail bar & lounge'],
    ['name' => 'Airport Shuttle Service', 'icon' => 'fa-solid fa-van-shuttle', 'desc' => 'Scheduled airport pickup and drop'],
    ['name' => 'Air Conditioning', 'icon' => 'fa-solid fa-snowflake', 'desc' => 'Individual climate control AC'],
    ['name' => '24x7 Room Service', 'icon' => 'fa-solid fa-bell-concierge', 'desc' => 'Around the clock food & butler support'],
    ['name' => 'Free Valet Parking', 'icon' => 'fa-solid fa-square-parking', 'desc' => 'Secure on-premise vehicle parking'],
    ['name' => 'Jacuzzi / Bathtub', 'icon' => 'fa-solid fa-hot-tub-person', 'desc' => 'Private in-room jacuzzi or bathtub']
];

$pageTitle = $isEdit ? "Edit Hotel: " . htmlspecialchars($hotel['name']) : "Add New Hotel";
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

            <!-- Page Header / Breadcrumb -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border border-[#e5e4dc] p-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-[11px] text-slate-400">
                        <a href="hotels.php" class="text-sage-700 hover:underline font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Back to Hotels List</span>
                        </a>
                        <span>/</span>
                        <span><?php echo $isEdit ? 'Edit Property' : 'New Property'; ?></span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold font-space text-slate-900 tracking-tight">
                        <?php echo $isEdit ? 'Edit Hotel, Gallery &amp; Room Rates' : 'Add New Hotel, Gallery &amp; Room Rates'; ?>
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <a href="hotels.php" class="px-3.5 py-1.5 text-xs font-semibold bg-white hover:bg-cream-100 text-slate-700 border border-[#e5e4dc] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" form="hotelForm" class="px-4 py-1.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white border border-sage-800 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                        <span>Save Hotel</span>
                    </button>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-3.5 text-xs font-semibold flex items-center gap-2.5 <?php echo $alertType === 'success' ? 'bg-sage-50 text-sage-900 border border-sage-300' : 'bg-rose-50 text-rose-900 border border-rose-200'; ?>">
                    <i class="fa-solid <?php echo $alertType === 'success' ? 'fa-circle-check text-sage-700' : 'fa-circle-exclamation text-rose-600'; ?>"></i>
                    <span><?php echo htmlspecialchars($alertMessage); ?></span>
                </div>
            <?php endif; ?>

            <!-- Full Page Form -->
            <form id="hotelForm" method="POST" action="hotel-edit.php<?php echo $isEdit ? '?id=' . $hotelId : ''; ?>" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="save_hotel" value="1">
                <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars((string)($hotelId ?? '')); ?>">

                <!-- Section 1: Basic Information & Property Badging -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Basic Property Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Hotel / Resort Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="hotelName" value="<?php echo htmlspecialchars($hotel['name']); ?>" required placeholder="e.g. Taj Exotica Resort & Spa Goa" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-semibold">
                            <p class="text-[10px] text-slate-400 mt-1">Provide the full official property name as shown to customers.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Property Type</label>
                            <select name="property_type" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                                <option value="5★ Luxury Resort" <?php echo ($hotel['property_type'] === '5★ Luxury Resort') ? 'selected' : ''; ?>>5★ Luxury Resort</option>
                                <option value="5★ Royal Heritage Palace" <?php echo ($hotel['property_type'] === '5★ Royal Heritage Palace') ? 'selected' : ''; ?>>5★ Royal Heritage Palace</option>
                                <option value="5★ Beachfront Resort" <?php echo ($hotel['property_type'] === '5★ Beachfront Resort') ? 'selected' : ''; ?>>5★ Beachfront Resort</option>
                                <option value="5★ Backwater Sanctuary" <?php echo ($hotel['property_type'] === '5★ Backwater Sanctuary') ? 'selected' : ''; ?>>5★ Backwater Sanctuary</option>
                                <option value="4★ Premium Boutique" <?php echo ($hotel['property_type'] === '4★ Premium Boutique') ? 'selected' : ''; ?>>4★ Premium Boutique</option>
                                <option value="Alpine Snow Chalet" <?php echo ($hotel['property_type'] === 'Alpine Snow Chalet') ? 'selected' : ''; ?>>Alpine Snow Chalet</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Star Rating (1 to 5 Stars)</label>
                            <select name="star_rating" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                                <option value="5" <?php echo ((int)$hotel['star_rating'] === 5) ? 'selected' : ''; ?>>★★★★★ (5 Stars Luxury)</option>
                                <option value="4" <?php echo ((int)$hotel['star_rating'] === 4) ? 'selected' : ''; ?>>★★★★☆ (4 Stars Premium)</option>
                                <option value="3" <?php echo ((int)$hotel['star_rating'] === 3) ? 'selected' : ''; ?>>★★★☆☆ (3 Stars Standard)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Marketing Badge Pill</label>
                            <input type="text" name="badge" value="<?php echo htmlspecialchars($hotel['badge']); ?>" placeholder="e.g. 5★ Luxury Beachfront" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Status</label>
                            <select name="status" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                                <option value="active" <?php echo ($hotel['status'] === 'active') ? 'selected' : ''; ?>>Active (Visible on Website)</option>
                                <option value="inactive" <?php echo ($hotel['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive (Draft Mode)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Location & Destination Mapping -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Destination &amp; Location Tabs</h2>
                        </div>
                        <span class="text-[10px] text-sage-800 bg-sage-50 border border-sage-200 px-2 py-0.5 font-bold">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1 text-sage-600"></i> Auto-Fill Supported
                        </span>
                    </div>

                    <!-- Live Location Search & Auto-Fill Field -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-800 mb-1">
                            Search Location to Auto-Fill Details <span class="text-slate-400 font-normal">(Type city or area e.g. "Baga Beach Goa", "Old Manali", "Dubai Marina")</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="locationSearchInput" autocomplete="off" placeholder="Type city or place to search & auto-fill below fields..." class="w-full pl-8 pr-10 py-2.5 text-xs bg-cream-50/80 border border-[#e5e4dc] focus:border-sage-700 focus:bg-white outline-none font-medium">
                            <div id="locationSearchSpinner" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fa-solid fa-circle-notch fa-spin text-xs text-sage-700"></i>
                            </div>
                        </div>
                        <!-- Suggestions Dropdown -->
                        <div id="locationSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-[#e5e4dc] shadow-lg max-h-56 overflow-y-auto z-50 divide-y divide-[#e5e4dc]"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Destination City <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="city" id="hotelCity" value="<?php echo htmlspecialchars($hotel['city']); ?>" required placeholder="e.g. Goa, Manali, Jaipur, Dubai" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-semibold">
                            <p class="text-[10px] text-slate-400 mt-1">This city connects to website filter tabs dynamically.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">State / Province</label>
                            <input type="text" name="state" id="hotelState" value="<?php echo htmlspecialchars($hotel['state']); ?>" placeholder="e.g. Goa, Himachal Pradesh" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Country</label>
                            <input type="text" name="country" id="hotelCountry" value="<?php echo htmlspecialchars($hotel['country']); ?>" placeholder="India" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-800 mb-1">Complete Address</label>
                            <input type="text" name="address" id="hotelAddress" value="<?php echo htmlspecialchars($hotel['address']); ?>" placeholder="e.g. Benaulim Beach, South Goa, Goa 403716" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Pricing & Check-in Timings -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Starting Price &amp; Check-In Timings</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Starting Price (Per Night) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="starting_price" value="<?php echo htmlspecialchars($hotel['starting_price']); ?>" required class="w-full text-xs pl-7 pr-2.5 py-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Original Price (Strikeout)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" name="original_price" value="<?php echo htmlspecialchars($hotel['original_price']); ?>" class="w-full text-xs pl-7 pr-2.5 py-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium text-slate-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Check-In Time</label>
                            <input type="text" name="checkin_time" value="<?php echo htmlspecialchars($hotel['checkin_time']); ?>" placeholder="02:00 PM" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Check-Out Time</label>
                            <input type="text" name="checkout_time" value="<?php echo htmlspecialchars($hotel['checkout_time']); ?>" placeholder="11:00 AM" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Main Cover Photo & Multi-Photo Resort Gallery -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Cover Photo &amp; Resort Photo Gallery</h2>
                        </div>
                        <span class="text-[10px] text-slate-500">Multiple Photos Shown in Luxury Magazine Grid &amp; Modal</span>
                    </div>

                    <!-- Part A: Featured Cover Photo -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-800">
                            Resort Featured Cover Photo <span class="text-slate-400 font-normal">(Main facade / infinity pool photo)</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-cream-50 p-3.5 border border-[#e5e4dc]">
                            <div class="md:col-span-2 space-y-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Cover Image URL</label>
                                    <input type="url" name="featured_image_url" id="coverUrlInput" value="<?php echo htmlspecialchars($hotel['featured_image']); ?>" placeholder="https://images.unsplash.com/photo-..." class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium" oninput="updateCoverPreview(this.value)">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 mb-0.5">Or Upload Local Image File</label>
                                    <input type="file" name="featured_image_file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:border file:border-[#e5e4dc] file:text-[11px] file:font-semibold file:bg-white hover:file:bg-cream-100 file:cursor-pointer">
                                    <input type="hidden" name="existing_featured_image" value="<?php echo htmlspecialchars($hotel['featured_image']); ?>">
                                </div>
                            </div>
                            <div class="h-28 bg-white border border-[#e5e4dc] flex items-center justify-center overflow-hidden relative group">
                                <img id="coverPreviewImg" src="<?php echo !empty($hotel['featured_image']) ? htmlspecialchars($hotel['featured_image']) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80'; ?>" alt="Cover" class="w-full h-full object-cover">
                                <span class="absolute bottom-1 right-1 bg-slate-900/80 text-white text-[9px] px-1.5 py-0.5 font-bold">Cover Preview</span>
                            </div>
                        </div>
                    </div>

                    <!-- Part B: Multi-Photo Resort Gallery Manager -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-xs font-bold text-slate-800">
                                    Resort Gallery Photos <span class="text-slate-400 font-normal">(Lobby, Pools, Beach, Gardens, Spa, Dining)</span>
                                </label>
                                <p class="text-[10px] text-slate-400">Add multiple photos for guest browsing. You can upload files or paste URLs.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="addGalleryUrlRow()" class="px-2.5 py-1 text-xs font-bold bg-cream-50 text-slate-800 border border-[#e5e4dc] hover:bg-cream-100 transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-link text-[10px] text-sage-700"></i>
                                    <span>Add Photo URL</span>
                                </button>
                            </div>
                        </div>

                        <!-- Multi-file Upload Box -->
                        <div class="p-3 bg-white border border-dashed border-sage-400 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-sage-50 text-sage-700 border border-sage-200 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Upload Multiple Resort Gallery Photos</div>
                                    <div class="text-[10px] text-slate-400">Select multiple JPG, PNG, or WebP images from your computer</div>
                                </div>
                            </div>
                            <input type="file" name="gallery_files[]" multiple accept="image/*" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:border file:border-sage-300 file:text-xs file:font-bold file:bg-sage-50 file:text-sage-800 hover:file:bg-sage-100 file:cursor-pointer">
                        </div>

                        <!-- Existing and Dynamic Gallery Items Grid -->
                        <div id="galleryContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            <?php if (!empty($galleryImages)): ?>
                                <?php foreach ($galleryImages as $gi => $gImg): ?>
                                    <div class="gallery-card bg-white border border-[#e5e4dc] p-2 space-y-2 relative group">
                                        <div class="h-28 bg-cream-50 border border-[#e5e4dc] overflow-hidden">
                                            <img src="<?php echo htmlspecialchars($gImg['image_url']); ?>" alt="Gallery" class="w-full h-full object-cover">
                                        </div>
                                        <input type="hidden" name="gallery_existing[<?php echo $gi; ?>][url]" value="<?php echo htmlspecialchars($gImg['image_url']); ?>">
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-500 mb-0.5">Photo Caption / Tag</label>
                                            <input type="text" name="gallery_existing[<?php echo $gi; ?>][caption]" value="<?php echo htmlspecialchars($gImg['caption'] ?? 'Resort View'); ?>" placeholder="e.g. Sunset Infinity Pool" class="w-full text-[11px] p-1.5 bg-cream-50 border border-[#e5e4dc]">
                                        </div>
                                        <button type="button" onclick="this.closest('.gallery-card').remove()" class="absolute top-3 right-3 w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] shadow-sm transition-colors" title="Delete Photo">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Included Amenities Checklist -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">5. Included Stay Amenities</h2>
                        </div>
                        <span class="text-[10px] text-slate-400">Select all that apply</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                        <?php foreach ($standardAmenities as $idx => $am): ?>
                            <?php $isChecked = in_array($am['name'], $selectedAmenities); ?>
                            <label class="flex items-start gap-2.5 p-2.5 border border-[#e5e4dc] hover:bg-cream-50 cursor-pointer transition-colors select-none">
                                <input type="checkbox" name="amenities[<?php echo $idx; ?>][name]" value="<?php echo htmlspecialchars($am['name']); ?>" <?php echo $isChecked ? 'checked' : ''; ?> class="mt-0.5 accent-sage-700">
                                <input type="hidden" name="amenities[<?php echo $idx; ?>][icon]" value="<?php echo htmlspecialchars($am['icon']); ?>">
                                <div class="text-xs">
                                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                        <i class="<?php echo htmlspecialchars($am['icon']); ?> text-sage-700 text-[11px]"></i>
                                        <span><?php echo htmlspecialchars($am['name']); ?></span>
                                    </div>
                                    <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($am['desc']); ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Section 6: Dynamic Room Categories & Room Photo Builder -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e5e4dc]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-sage-700"></span>
                            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">6. Room Categories, Pricing &amp; Room Photos</h2>
                        </div>
                        <button type="button" onclick="addNewRoomRow()" class="px-3 py-1.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Room Category</span>
                        </button>
                    </div>

                    <div id="roomsContainer" class="space-y-4">
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $ri => $room): ?>
                                <div class="room-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group">
                                    <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-sage-800 uppercase tracking-wider">Room #<?php echo $ri + 1; ?></span>
                                        </div>
                                        <button type="button" onclick="this.closest('.room-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Room">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            <span>Delete Category</span>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Category Title <span class="text-rose-500">*</span></label>
                                            <input type="text" name="rooms[<?php echo $ri; ?>][room_name]" value="<?php echo htmlspecialchars($room['room_name']); ?>" required placeholder="e.g. Deluxe Sea Facing Suite with Balcony" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Type / Badge</label>
                                            <input type="text" name="rooms[<?php echo $ri; ?>][room_type]" value="<?php echo htmlspecialchars($room['room_type'] ?? 'Deluxe Room'); ?>" placeholder="Deluxe Room / Suite / Villa" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Price / Night (₹) <span class="text-rose-500">*</span></label>
                                            <input type="number" name="rooms[<?php echo $ri; ?>][price_per_night]" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold text-sage-900 focus:border-sage-700 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Original Price (₹)</label>
                                            <input type="number" name="rooms[<?php echo $ri; ?>][original_price]" value="<?php echo htmlspecialchars($room['original_price'] ?? ($room['price_per_night'] * 1.25)); ?>" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] text-slate-500 focus:border-sage-700 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Bed Type</label>
                                            <input type="text" name="rooms[<?php echo $ri; ?>][bed_type]" value="<?php echo htmlspecialchars($room['bed_type'] ?? '1 King Bed'); ?>" placeholder="1 King Bed" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Size</label>
                                            <input type="text" name="rooms[<?php echo $ri; ?>][room_size]" value="<?php echo htmlspecialchars($room['room_size'] ?? '420 sq ft'); ?>" placeholder="420 sq ft" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meal Plan &amp; Inclusions</label>
                                            <input type="text" name="rooms[<?php echo $ri; ?>][meal_plan]" value="<?php echo htmlspecialchars($room['meal_plan'] ?? 'Free Breakfast Included'); ?>" placeholder="Free Breakfast Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Photo (Image URL or Upload)</label>
                                            <div class="flex items-center gap-2">
                                                <input type="url" name="rooms[<?php echo $ri; ?>][image_url]" value="<?php echo htmlspecialchars($room['image_url'] ?? ''); ?>" placeholder="https://images.unsplash.com/photo-..." class="flex-1 text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                                                <input type="file" name="room_files[<?php echo $ri; ?>]" accept="image/*" class="w-36 text-[10px] text-slate-500 file:py-1 file:px-2 file:border file:border-[#e5e4dc] file:text-[10px] file:bg-white file:font-semibold">
                                                <input type="hidden" name="rooms[<?php echo $ri; ?>][existing_image]" value="<?php echo htmlspecialchars($room['image_url'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default First Room Row -->
                            <div class="room-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group">
                                <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                                    <span class="text-xs font-bold text-sage-800 uppercase tracking-wider">Room #1</span>
                                    <button type="button" onclick="this.closest('.room-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Room">
                                        <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        <span>Delete Category</span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Category Title <span class="text-rose-500">*</span></label>
                                        <input type="text" name="rooms[0][room_name]" value="Deluxe Ocean View Room" required placeholder="e.g. Deluxe Sea Facing Suite with Balcony" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Type / Badge</label>
                                        <input type="text" name="rooms[0][room_type]" value="Deluxe Room" placeholder="Deluxe Room / Suite / Villa" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Price / Night (₹) <span class="text-rose-500">*</span></label>
                                        <input type="number" name="rooms[0][price_per_night]" value="<?php echo htmlspecialchars($hotel['starting_price']); ?>" required class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold text-sage-900 focus:border-sage-700 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Original Price (₹)</label>
                                        <input type="number" name="rooms[0][original_price]" value="<?php echo htmlspecialchars($hotel['original_price']); ?>" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] text-slate-500 focus:border-sage-700 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Bed Type</label>
                                        <input type="text" name="rooms[0][bed_type]" value="1 King Bed" placeholder="1 King Bed" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Size</label>
                                        <input type="text" name="rooms[0][room_size]" value="420 sq ft" placeholder="420 sq ft" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meal Plan &amp; Inclusions</label>
                                        <input type="text" name="rooms[0][meal_plan]" value="Free Breakfast Included" placeholder="Free Breakfast Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Photo (Image URL or Upload)</label>
                                        <div class="flex items-center gap-2">
                                            <input type="url" name="rooms[0][image_url]" value="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80" placeholder="https://images.unsplash.com/photo-..." class="flex-1 text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                                            <input type="file" name="room_files[0]" accept="image/*" class="w-36 text-[10px] text-slate-500 file:py-1 file:px-2 file:border file:border-[#e5e4dc] file:text-[10px] file:bg-white file:font-semibold">
                                            <input type="hidden" name="rooms[0][existing_image]" value="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 7: Description & Policies -->
                <div class="bg-white border border-[#e5e4dc] p-5 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-[#e5e4dc]">
                        <span class="w-2 h-2 bg-sage-700"></span>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">7. Overview Description &amp; Cancellation Policy</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Hotel Overview</label>
                        <textarea name="description" rows="3" placeholder="Brief summary of property atmosphere, beach access, or mountain views..." class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium"><?php echo htmlspecialchars($hotel['description']); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Cancellation &amp; Booking Policy</label>
                        <input type="text" name="policies" value="<?php echo htmlspecialchars($hotel['policies']); ?>" class="w-full text-xs p-2.5 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none font-medium">
                    </div>
                </div>

                <!-- Bottom Save Button Bar -->
                <div class="flex items-center justify-between p-4 bg-white border border-[#e5e4dc]">
                    <a href="hotels.php" class="px-4 py-2 text-xs font-semibold bg-white hover:bg-cream-100 text-slate-700 border border-[#e5e4dc] transition-colors">
                        Cancel &amp; Return
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs font-bold bg-sage-700 hover:bg-sage-800 text-white border border-sage-800 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span><?php echo $isEdit ? 'Save Changes' : 'Publish Hotel Stay'; ?></span>
                    </button>
                </div>

            </form>

        </main>
    </div>
</div>

<script>
    let roomIndex = <?php echo max(count($rooms), 1); ?>;
    let galleryIndex = <?php echo max(count($galleryImages), 1); ?>;

    function updateCoverPreview(url) {
        const preview = document.getElementById('coverPreviewImg');
        if (preview && url && url.trim().length > 5) {
            preview.src = url.trim();
        }
    }

    function addGalleryUrlRow() {
        galleryIndex++;
        const container = document.getElementById('galleryContainer');
        const card = document.createElement('div');
        card.className = 'gallery-card bg-white border border-[#e5e4dc] p-2 space-y-2 relative group';
        card.innerHTML = `
            <div class="h-28 bg-cream-50 border border-[#e5e4dc] overflow-hidden flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80" alt="Gallery Preview" class="w-full h-full object-cover gal-prev-img">
            </div>
            <div>
                <label class="block text-[9px] font-bold text-slate-500 mb-0.5">Image URL</label>
                <input type="url" name="gallery_urls[${galleryIndex}][url]" required placeholder="https://images.unsplash.com/..." class="w-full text-[11px] p-1.5 bg-white border border-[#e5e4dc]" oninput="this.closest('.gallery-card').querySelector('.gal-prev-img').src = this.value">
            </div>
            <div>
                <label class="block text-[9px] font-bold text-slate-500 mb-0.5">Caption</label>
                <input type="text" name="gallery_urls[${galleryIndex}][caption]" placeholder="e.g. Resort Pool / Beach Deck" class="w-full text-[11px] p-1.5 bg-cream-50 border border-[#e5e4dc]">
            </div>
            <button type="button" onclick="this.closest('.gallery-card').remove()" class="absolute top-3 right-3 w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] shadow-sm transition-colors" title="Delete Photo">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        `;
        container.appendChild(card);
    }

    function addNewRoomRow() {
        roomIndex++;
        const container = document.getElementById('roomsContainer');
        const div = document.createElement('div');
        div.className = 'room-row bg-cream-50 border border-[#e5e4dc] p-4 space-y-3 relative group';
        div.innerHTML = `
            <div class="flex items-center justify-between pb-2 border-b border-[#e5e4dc]">
                <span class="text-xs font-bold text-sage-800 uppercase tracking-wider">Room #${roomIndex}</span>
                <button type="button" onclick="this.closest('.room-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs font-semibold flex items-center gap-1" title="Remove Room">
                    <i class="fa-solid fa-trash-can text-[11px]"></i>
                    <span>Delete Category</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Category Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="rooms[${roomIndex}][room_name]" required placeholder="e.g. Heritage Pool Villa / Executive Suite" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold focus:border-sage-700 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Type / Badge</label>
                    <input type="text" name="rooms[${roomIndex}][room_type]" value="Deluxe Suite" placeholder="Deluxe Room / Suite / Villa" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Price / Night (₹) <span class="text-rose-500">*</span></label>
                    <input type="number" name="rooms[${roomIndex}][price_per_night]" value="9500" required class="w-full text-xs p-2 bg-white border border-[#e5e4dc] font-bold text-sage-900 focus:border-sage-700 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Original Price (₹)</label>
                    <input type="number" name="rooms[${roomIndex}][original_price]" value="12000" class="w-full text-xs p-2 bg-white border border-[#e5e4dc] text-slate-500 focus:border-sage-700 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Bed Type</label>
                    <input type="text" name="rooms[${roomIndex}][bed_type]" value="1 King Bed" placeholder="1 King Bed" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Size</label>
                    <input type="text" name="rooms[${roomIndex}][room_size]" value="480 sq ft" placeholder="480 sq ft" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                <div>
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Meal Plan &amp; Inclusions</label>
                    <input type="text" name="rooms[${roomIndex}][meal_plan]" value="Free Breakfast Included" placeholder="Free Breakfast Included" class="w-full text-xs p-2 bg-white border border-[#e5e4dc]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-700 mb-0.5">Room Photo (Image URL or Upload)</label>
                    <div class="flex items-center gap-2">
                        <input type="url" name="rooms[${roomIndex}][image_url]" placeholder="https://images.unsplash.com/photo-..." class="flex-1 text-xs p-2 bg-white border border-[#e5e4dc] focus:border-sage-700 outline-none">
                        <input type="file" name="room_files[${roomIndex}]" accept="image/*" class="w-36 text-[10px] text-slate-500 file:py-1 file:px-2 file:border file:border-[#e5e4dc] file:text-[10px] file:bg-white file:font-semibold">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // Live Location Autocomplete & Auto-fill System
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('locationSearchInput');
        const suggestionsBox = document.getElementById('locationSuggestions');
        const spinner = document.getElementById('locationSearchSpinner');
        const cityField = document.getElementById('hotelCity');
        const stateField = document.getElementById('hotelState');
        const countryField = document.getElementById('hotelCountry');
        const addressField = document.getElementById('hotelAddress');
        const nameField = document.getElementById('hotelName');

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
                fetch(`../api/places.php?q=${encodeURIComponent(val)}&type=hotel`)
                    .then(res => res.json())
                    .then(data => {
                        if (spinner) spinner.classList.add('hidden');
                        if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                            renderSuggestions(data.data);
                        } else {
                            suggestionsBox.innerHTML = '<div class="p-3 text-xs text-slate-400 text-center">No matching locations found. You can enter manually below.</div>';
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
                
                const hasDbPill = item.has_hotels ? `<span class="text-[9px] font-bold bg-sage-50 text-sage-800 border border-sage-200 px-1.5 py-0.5">In Database</span>` : '';
                
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
                    // Auto-fill form fields
                    if (cityField && item.city) cityField.value = item.city;
                    if (stateField && item.state) stateField.value = item.state;
                    if (countryField && item.country) countryField.value = item.country;
                    if (addressField && item.full_address) addressField.value = item.full_address;

                    // If name is blank, suggest a clean name prefix
                    if (nameField && !nameField.value.trim()) {
                        nameField.value = item.name ? `${item.name} Luxury Stay` : `${item.city} Resort & Spa`;
                    }

                    input.value = item.full_address || item.name;
                    suggestionsBox.classList.add('hidden');

                    // Flash feedback highlight
                    [cityField, stateField, countryField, addressField].forEach(f => {
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

        // Close on outside click
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
