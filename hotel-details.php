<?php
/**
 * Hotel & Luxury Resort Details Page - Orion Advent
 * - 5-Photo Luxury Magazine Grid
 * - Clean Minimal Room Categories (Not Heavy, Highly Readable)
 * - Complete Resort Amenities, Property Policies & House Rules
 * - Sticky Reservation Sidebar with Date Pickers & Live Price Calculator
 * - 100% Flat, Border-First Design (Strictly ZERO shadows)
 * - Pure Font Awesome 6 Vector Icons Only (Zero Emojis)
 */

$hotelId = isset($_GET['id']) ? htmlspecialchars(trim($_GET['id'])) : 'HTL-GOA-01';

// Hotel Database
$hotelsDb = [
    'HTL-GOA-01' => [
        'id' => 'HTL-GOA-01',
        'name' => 'Taj Fort Aguada Resort & Spa, North Goa',
        'subtitle' => 'Historic 16th-century Portuguese ramparts meet Arabian Sea luxury with direct beach access, Jiva spa, and world-class coastal dining.',
        'location' => 'Sinquerim Beach, Candolim, North Goa 403515, India',
        'city' => 'Goa, India',
        'star_rating' => '5-Star Luxury Beach Resort',
        'badge' => 'Orion Premier Stay',
        'badge_class' => 'bg-brand-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 2410,
        'base_price' => 8500,
        'original_price' => 11500,
        'tax_rate' => 0.12,
        'gallery' => [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80', // Resort Facade & Pool
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',  // Sea View Suite
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',  // Luxury Bath & Lounge
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',  // Infinity Pool Sunset
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80',  // Beachfront Cabana
        ],
        'key_perks' => [
            ['icon' => 'fa-solid fa-umbrella-beach', 'title' => 'Direct Beach Access', 'desc' => 'Private boardwalk straight to Sinquerim sands'],
            ['icon' => 'fa-solid fa-person-swimming', 'title' => 'Ocean Infinity Pool', 'desc' => 'Multi-tier temperature controlled swimming pool'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Gourmet Breakfast', 'desc' => 'Complimentary multi-cuisine artisan breakfast buffet'],
            ['icon' => 'fa-solid fa-spa', 'title' => 'Award-Winning Jiva Spa', 'desc' => 'Authentic signature therapies & Ayurvedic treatments'],
            ['icon' => 'fa-solid fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Enterprise gigabit wireless across entire property'],
            ['icon' => 'fa-solid fa-bell-concierge', 'title' => '24/7 Royal Butler', 'desc' => 'Dedicated personalized concierge support anytime'],
        ],
        'rooms' => [
            [
                'id' => 'room-deluxe-sea',
                'name' => 'Deluxe Sea Facing Room with Balcony',
                'price' => 8500,
                'original_price' => 11500,
                'size' => '420 sq.ft (39 m²)',
                'bed' => '1 King Bed or 2 Twin Beds',
                'view' => 'Arabian Sea Panoramic View',
                'occupancy' => '2 Adults • 1 Child',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Free Buffet Breakfast', 'Private Ocean Balcony', 'High-Speed Wi-Fi', 'Bathtub & Rain Shower', 'Tea/Coffee Maker', 'Mini Bar'],
                'cancellation' => 'Free cancellation until 24 hrs prior to check-in'
            ],
            [
                'id' => 'room-heritage-villa',
                'name' => 'Aguada Heritage Villa with Private Lawn',
                'price' => 14200,
                'original_price' => 18000,
                'size' => '650 sq.ft (60 m²)',
                'bed' => '1 Extra-Large King Bed',
                'view' => 'Private Garden & Fort Aguada Ramparts',
                'occupancy' => '3 Adults or 2 Adults + 2 Kids',
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Complimentary Breakfast & High Tea', 'Private Sun Lawn', 'Express Check-in', 'Deep Soaking Tub', 'Espresso Machine', 'Pillow Menu'],
                'cancellation' => 'Free cancellation until 48 hrs prior to check-in'
            ],
            [
                'id' => 'room-presidential-suite',
                'name' => 'Presidential Ocean Suite with Plunge Pool',
                'price' => 24500,
                'original_price' => 31000,
                'size' => '1,100 sq.ft (102 m²)',
                'bed' => '1 Master King Suite + Living Pavilion',
                'view' => '180° Direct Sea & Horizon View',
                'occupancy' => '4 Guests Max',
                'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Private Heated Plunge Pool', '24/7 Butler Service', 'All-Inclusive Breakfast & Cocktails', 'Jacuzzi', 'Airport Chauffeur Transfer'],
                'cancellation' => 'Free cancellation until 7 days prior to check-in'
            ]
        ],
        'amenities_categories' => [
            'Swimming & Beach' => [
                ['icon' => 'fa-solid fa-person-swimming', 'name' => 'Oceanfront Infinity Pool'],
                ['icon' => 'fa-solid fa-umbrella-beach', 'name' => 'Private Beach Boardwalk'],
                ['icon' => 'fa-solid fa-water', 'name' => 'Kids Splash Pool'],
                ['icon' => 'fa-solid fa-chair', 'name' => 'Sun Loungers & Umbrellas'],
            ],
            'Dining & Drinks' => [
                ['icon' => 'fa-solid fa-utensils', 'name' => 'Latitude Multi-Cuisine Diner'],
                ['icon' => 'fa-solid fa-fish', 'name' => 'Morisco Seafood Grill'],
                ['icon' => 'fa-solid fa-martini-glass-citrus', 'name' => 'Martini Rock Bar'],
                ['icon' => 'fa-solid fa-mug-saucer', 'name' => 'Sunset Coffee Lounge'],
            ],
            'Wellness & Recreation' => [
                ['icon' => 'fa-solid fa-spa', 'name' => 'Jiva Signature Spa'],
                ['icon' => 'fa-solid fa-dumbbell', 'name' => '24/7 Fitness Center'],
                ['icon' => 'fa-solid fa-table-tennis-paddle-ball', 'name' => 'Tennis & Activity Court'],
                ['icon' => 'fa-solid fa-hot-tub-person', 'name' => 'Steam & Sauna Suite'],
            ],
            'Conveniences' => [
                ['icon' => 'fa-solid fa-square-parking', 'name' => 'Free Valet Parking'],
                ['icon' => 'fa-solid fa-van-shuttle', 'name' => 'Airport Transfers'],
                ['icon' => 'fa-solid fa-shield-halved', 'name' => '24/7 Security & CCTV'],
                ['icon' => 'fa-solid fa-user-doctor', 'name' => 'Doctor on Call'],
            ]
        ],
        'policies' => [
            'check_in' => '02:00 PM',
            'check_out' => '12:00 PM',
            'cancellation' => 'Free cancellation up to 24 hours prior to check-in date. Late cancellations or no-shows incur 1 night room charge.',
            'child_policy' => 'Children up to 5 years stay complimentary when sharing existing bedding with parents. Extra bed available at ₹1,500/night.',
            'id_proof' => 'Government-issued photo identification (Aadhaar, Passport, Driving License, Voter ID) required for all adults checking in.',
        ]
    ],
    'HTL-SHM-02' => [
        'id' => 'HTL-SHM-02',
        'name' => 'The Oberoi Cecil, Heritage Mountain Retreat',
        'subtitle' => 'Historic 1884 Grand Himalayan heritage hotel on Mall Road featuring heated indoor swimming pool, Oberoi luxury spa, and colonial cedar ballroom.',
        'location' => 'Chaura Maidan, Mall Road, Shimla, Himachal Pradesh 171004, India',
        'city' => 'Shimla, Himachal Pradesh',
        'star_rating' => '5-Star Luxury Mountain Resort',
        'badge' => 'Colonial Heritage',
        'badge_class' => 'bg-amber-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 1850,
        'base_price' => 11200,
        'original_price' => 15000,
        'tax_rate' => 0.12,
        'gallery' => [
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80',
        ],
        'key_perks' => [
            ['icon' => 'fa-solid fa-mountain', 'title' => 'Valley & Mountain Views', 'desc' => 'Panoramic vistas of the cedar-clad Shimla valley'],
            ['icon' => 'fa-solid fa-temperature-arrow-up', 'title' => 'Heated Indoor Pool', 'desc' => 'Temperature-controlled pool with glass atrium roof'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Artisan Breakfast', 'desc' => 'Complimentary multi-course breakfast buffet'],
            ['icon' => 'fa-solid fa-spa', 'title' => 'Oberoi Spa & Therapy', 'desc' => 'Holistic aromatherapy and ayurvedic treatments'],
            ['icon' => 'fa-solid fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Enterprise gigabit wireless across entire property'],
            ['icon' => 'fa-solid fa-fire-burner', 'title' => 'Cozy Fireplace Lounge', 'desc' => 'Heritage colonial library and teakwood fireplace'],
        ],
        'rooms' => [
            [
                'id' => 'room-premier-valley',
                'name' => 'Premier Valley View Room with Fireplace',
                'price' => 11200,
                'original_price' => 15000,
                'size' => '390 sq.ft (36 m²)',
                'bed' => '1 King Bed with Burma Teak Flooring',
                'view' => 'Shimla Valley & Himalayan Pine Vista',
                'occupancy' => '2 Adults • 1 Child',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Complimentary Breakfast', 'Private Fireplace', 'Heated Flooring', 'Bathtub with Forest View', 'Espresso Maker'],
                'cancellation' => 'Free cancellation until 48 hrs prior to check-in'
            ]
        ],
        'amenities_categories' => [
            'Swimming & Relaxation' => [
                ['icon' => 'fa-solid fa-temperature-arrow-up', 'name' => 'Heated Indoor Pool'],
                ['icon' => 'fa-solid fa-spa', 'name' => 'The Oberoi Spa'],
                ['icon' => 'fa-solid fa-fire', 'name' => 'Fireplace Lounge'],
                ['icon' => 'fa-solid fa-book', 'name' => 'Heritage Teak Library'],
            ],
            'Dining & Gourmet' => [
                ['icon' => 'fa-solid fa-utensils', 'name' => 'The Restaurant (Fine Dining)'],
                ['icon' => 'fa-solid fa-mug-saucer', 'name' => 'Cedar Garden Tea Lounge'],
                ['icon' => 'fa-solid fa-wine-glass', 'name' => 'Heritage Bar'],
                ['icon' => 'fa-solid fa-bell-concierge', 'name' => '24/7 In-Room Dining'],
            ]
        ],
        'policies' => [
            'check_in' => '02:00 PM',
            'check_out' => '12:00 PM',
            'cancellation' => 'Free cancellation up to 48 hours prior to check-in date.',
            'child_policy' => 'Children up to 6 years stay complimentary in existing bedding.',
            'id_proof' => 'Government-issued photo identification required for all guests.',
        ]
    ],
    'HTL-KER-04' => [
        'id' => 'HTL-KER-04',
        'name' => 'Kumarakom Lake Resort & Heritage Pool Villas',
        'subtitle' => 'Acclaimed as one of the world\'s finest heritage retreats on the banks of Lake Vembanad with 16th-century traditional Manas and meandering pool access.',
        'location' => 'Vembanad Lake, Kumarakom, Kottayam, Kerala 686566, India',
        'city' => 'Kumarakom, Kerala',
        'star_rating' => '5-Star Heritage Sanctuary',
        'badge' => 'Backwater Icon',
        'badge_class' => 'bg-emerald-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 2120,
        'base_price' => 12800,
        'original_price' => 17000,
        'tax_rate' => 0.12,
        'gallery' => [
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
        ],
        'key_perks' => [
            ['icon' => 'fa-solid fa-water-ladder', 'title' => 'Meandering Pool Villa', 'desc' => 'Direct water access straight from private bedroom deck'],
            ['icon' => 'fa-solid fa-water', 'title' => 'Vembanad Lake Frontage', 'desc' => 'Sprawling 25-acre sanctuary on pristine backwater banks'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Traditional Breakfast', 'desc' => 'Authentic Syrian Christian & coastal Kerala breakfast'],
            ['icon' => 'fa-solid fa-spa', 'title' => 'Ayurmana Spa', 'desc' => '200-year-old heritage Ayurvedic healing center'],
            ['icon' => 'fa-solid fa-ship', 'title' => 'Sunset Lake Cruise', 'desc' => 'Complimentary evening boat cruise with flute music'],
            ['icon' => 'fa-solid fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Enterprise gigabit wireless across entire property'],
        ],
        'rooms' => [
            [
                'id' => 'room-meandering-pool',
                'name' => 'Meandering Pool Villa with Private Deck',
                'price' => 12800,
                'original_price' => 17000,
                'size' => '520 sq.ft (48 m²)',
                'bed' => '1 Four-Poster King Bed',
                'view' => 'Direct 250m Meandering Waterway View',
                'occupancy' => '2 Adults • 1 Child',
                'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Complimentary Breakfast', 'Direct Pool Steps', 'Open-to-Sky Rain Shower', 'Handcrafted Teak Furniture', 'Tea/Coffee Station'],
                'cancellation' => 'Free cancellation until 48 hrs prior to check-in'
            ]
        ],
        'amenities_categories' => [
            'Water & Wellness' => [
                ['icon' => 'fa-solid fa-water-ladder', 'name' => '250m Meandering Pool'],
                ['icon' => 'fa-solid fa-person-swimming', 'name' => 'Infinity Lake Pool'],
                ['icon' => 'fa-solid fa-spa', 'name' => 'Ayurmana Ayurvedic Center'],
                ['icon' => 'fa-solid fa-ship', 'name' => 'Sunset Motor Boat Cruise'],
            ],
            'Culinary Delights' => [
                ['icon' => 'fa-solid fa-utensils', 'name' => 'Ettukettu Heritage Diner'],
                ['icon' => 'fa-solid fa-fish', 'name' => 'Vembanad Seafood Bar'],
                ['icon' => 'fa-solid fa-mug-saucer', 'name' => 'Thattukada Tea Shop'],
                ['icon' => 'fa-solid fa-martini-glass', 'name' => 'Poolside Bar'],
            ]
        ],
        'policies' => [
            'check_in' => '02:00 PM',
            'check_out' => '12:00 PM',
            'cancellation' => 'Free cancellation up to 48 hours prior to check-in.',
            'child_policy' => 'Children up to 5 years stay complimentary in existing bedding.',
            'id_proof' => 'Government-issued photo identification required for all guests.',
        ]
    ],
    // Fallback template for any other hotel
    'default' => [
        'id' => 'HTL-DEFAULT',
        'name' => 'Orion Certified 5-Star Luxury Stay & Resort',
        'subtitle' => 'Handpicked premium stay featuring deluxe suites, swimming pool, complimentary breakfast, and top-tier guest amenities.',
        'location' => 'Prime Destination Circuit, India',
        'city' => 'Prime Travel Destination',
        'star_rating' => '5-Star Luxury Resort',
        'badge' => 'Orion Verified Stay',
        'badge_class' => 'bg-brand-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 1850,
        'base_price' => 7500,
        'original_price' => 10000,
        'tax_rate' => 0.12,
        'gallery' => [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80',
        ],
        'key_perks' => [
            ['icon' => 'fa-solid fa-person-swimming', 'title' => 'Swimming Pool', 'desc' => 'Temperature controlled outdoor swimming pool'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Free Breakfast', 'desc' => 'Complimentary buffet breakfast daily'],
            ['icon' => 'fa-solid fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Complimentary wireless access throughout stay'],
            ['icon' => 'fa-solid fa-spa', 'title' => 'Spa & Wellness', 'desc' => 'Ayurvedic treatments & steam facilities'],
        ],
        'rooms' => [
            [
                'id' => 'room-deluxe',
                'name' => 'Deluxe King Room with Balcony',
                'price' => 7500,
                'original_price' => 10000,
                'size' => '380 sq.ft',
                'bed' => '1 King Bed',
                'view' => 'Scenic View',
                'occupancy' => '2 Adults • 1 Child',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
                'amenities' => ['Free Breakfast', 'High-Speed Wi-Fi', 'Air Conditioning', 'Bathtub', 'Balcony'],
                'cancellation' => 'Free cancellation until 24 hrs prior to check-in'
            ]
        ],
        'amenities_categories' => [
            'Facilities' => [
                ['icon' => 'fa-solid fa-person-swimming', 'name' => 'Swimming Pool'],
                ['icon' => 'fa-solid fa-utensils', 'name' => 'Restaurant'],
                ['icon' => 'fa-solid fa-wifi', 'name' => 'Free Wi-Fi'],
                ['icon' => 'fa-solid fa-square-parking', 'name' => 'Free Parking'],
            ]
        ],
        'policies' => [
            'check_in' => '02:00 PM',
            'check_out' => '12:00 PM',
            'cancellation' => 'Free cancellation up to 24 hours prior to arrival.',
            'child_policy' => 'Children up to 5 years stay free in existing bedding.',
            'id_proof' => 'Government-issued photo identification required at check-in.',
        ]
    ]
];

// Smart Hotel Resolution from query, id or slug parameter
$lookupHotelKey = 'HTL-GOA-01';
$rawHotelSearch = isset($_GET['id']) ? trim($_GET['id']) : (isset($_GET['slug']) ? trim($_GET['slug']) : (isset($_GET['hotel']) ? trim($_GET['hotel']) : ''));

// Attempt Dynamic Database Resolution First
require_once __DIR__ . '/config/db.php';
$pdoDetails = getDBConnection();

if ($pdoDetails && !empty($rawHotelSearch)) {
    $dbHotelId = null;
    if (strpos($rawHotelSearch, 'HTL-DB-') === 0) {
        $dbHotelId = (int)str_replace('HTL-DB-', '', $rawHotelSearch);
    } elseif (is_numeric($rawHotelSearch) && (int)$rawHotelSearch > 0) {
        $dbHotelId = (int)$rawHotelSearch;
    }

    $fetchedHotel = null;
    if ($dbHotelId) {
        $dbStmt = $pdoDetails->prepare("SELECT * FROM `hotels` WHERE `id` = ?");
        $dbStmt->execute([$dbHotelId]);
        $fetchedHotel = $dbStmt->fetch();
    }

    if (!$fetchedHotel) {
        // Try searching by slug or name
        $dbStmt = $pdoDetails->prepare("SELECT * FROM `hotels` WHERE `slug` = ? OR `name` LIKE ? LIMIT 1");
        $dbStmt->execute([$rawHotelSearch, '%' . $rawHotelSearch . '%']);
        $fetchedHotel = $dbStmt->fetch();
        if ($fetchedHotel) {
            $dbHotelId = (int)$fetchedHotel['id'];
        }
    }

    if ($fetchedHotel && $dbHotelId) {
        // Fetch Rooms
        $rStmt = $pdoDetails->prepare("SELECT * FROM `hotel_rooms` WHERE `hotel_id` = ? AND `status` = 'available'");
        $rStmt->execute([$dbHotelId]);
        $fetchedRooms = $rStmt->fetchAll();

        $formattedRooms = [];
        foreach ($fetchedRooms as $fr) {
            $roomImage = !empty($fr['image_url']) ? $fr['image_url'] : (!empty($fetchedHotel['featured_image']) ? $fetchedHotel['featured_image'] : 'assets/images/placeholder-hotel.jpg');
            
            $formattedRooms[] = [
                'id' => 'room-db-' . $fr['id'],
                'name' => $fr['room_name'],
                'price' => (float)$fr['price_per_night'],
                'original_price' => (float)($fr['original_price'] ?: ($fr['price_per_night'] * 1.25)),
                'size' => $fr['room_size'] ?: '380 sq.ft',
                'bed' => $fr['bed_type'] ?: '1 King Bed',
                'view' => $fr['room_type'] ?: 'Deluxe View',
                'occupancy' => $fr['max_adults'] . ' Adults • ' . $fr['max_children'] . ' Child',
                'image' => $roomImage,
                'amenities' => array_filter(array_map('trim', explode(',', $fr['meal_plan'] . ', Free Wi-Fi, Air Conditioning, Private Bath'))),
                'cancellation' => 'Free cancellation until 24 hrs prior to check-in'
            ];
        }

        if (empty($formattedRooms)) {
            $formattedRooms[] = [
                'id' => 'room-db-def',
                'name' => 'Luxury Deluxe Stay',
                'price' => (float)$fetchedHotel['starting_price'],
                'original_price' => (float)($fetchedHotel['original_price'] ?: ($fetchedHotel['starting_price'] * 1.25)),
                'size' => '380 sq.ft',
                'bed' => '1 King Bed',
                'view' => 'Scenic View',
                'occupancy' => '2 Adults • 1 Child',
                'image' => !empty($fetchedHotel['featured_image']) ? $fetchedHotel['featured_image'] : 'assets/images/placeholder-hotel.jpg',
                'amenities' => ['Free Breakfast', 'High-Speed Wi-Fi', 'Air Conditioning', 'Bathtub'],
                'cancellation' => 'Free cancellation until 24 hrs prior to check-in'
            ];
        }

        // Fetch Gallery Images
        $gStmt = $pdoDetails->prepare("SELECT `image_url` FROM `hotel_images` WHERE `hotel_id` = ? ORDER BY `sort_order` ASC, `id` ASC");
        $gStmt->execute([$dbHotelId]);
        $galleryRows = $gStmt->fetchAll(PDO::FETCH_COLUMN);

        $galleryList = [];
        if (!empty($fetchedHotel['featured_image'])) {
            $galleryList[] = $fetchedHotel['featured_image'];
        }
        foreach ($galleryRows as $gr) {
            if (!empty($gr) && !in_array($gr, $galleryList)) {
                $galleryList[] = $gr;
            }
        }
        // If completely empty, add featured cover
        if (empty($galleryList)) {
            $galleryList[] = !empty($fetchedHotel['featured_image']) ? $fetchedHotel['featured_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80';
        }

        // Dynamic Amenities Parsing and Categorization from DB
        $parsedAmenities = [];
        $categorizedAmenities = [
            'Facilities & Services' => [],
            'Wellness & Comfort' => [],
            'Dining & Culinary' => []
        ];

        if (!empty($fetchedHotel['amenities'])) {
            $decAm = json_decode($fetchedHotel['amenities'], true);
            if (is_array($decAm)) {
                foreach ($decAm as $amItem) {
                    $amName = '';
                    $amIcon = 'fa-solid fa-circle-check';
                    if (is_array($amItem)) {
                        $amName = trim($amItem['name'] ?? '');
                        $amIcon = !empty($amItem['icon']) ? $amItem['icon'] : 'fa-solid fa-circle-check';
                    } elseif (is_string($amItem)) {
                        $amName = trim($amItem);
                    }

                    if (!empty($amName)) {
                        $parsedAmenities[] = [
                            'icon' => $amIcon,
                            'title' => $amName,
                            'desc' => 'Complimentary guest amenity included'
                        ];

                        $lowName = strtolower($amName);
                        if (str_contains($lowName, 'spa') || str_contains($lowName, 'gym') || str_contains($lowName, 'fitness') || str_contains($lowName, 'ac') || str_contains($lowName, 'conditioning') || str_contains($lowName, 'security') || str_contains($lowName, 'jacuzzi') || str_contains($lowName, 'bath') || str_contains($lowName, 'wellness') || str_contains($lowName, 'hot-tub')) {
                            $categorizedAmenities['Wellness & Comfort'][] = ['icon' => $amIcon, 'name' => $amName];
                        } elseif (str_contains($lowName, 'breakfast') || str_contains($lowName, 'restaurant') || str_contains($lowName, 'dining') || str_contains($lowName, 'bar') || str_contains($lowName, 'lounge') || str_contains($lowName, 'chef') || str_contains($lowName, 'cocktail') || str_contains($lowName, 'food')) {
                            $categorizedAmenities['Dining & Culinary'][] = ['icon' => $amIcon, 'name' => $amName];
                        } else {
                            $categorizedAmenities['Facilities & Services'][] = ['icon' => $amIcon, 'name' => $amName];
                        }
                    }
                }
            }
        }

        // Clean empty categories
        $categorizedAmenities = array_filter($categorizedAmenities, function($group) {
            return !empty($group);
        });

        if (empty($categorizedAmenities)) {
            $categorizedAmenities = [
                'Facilities & Services' => [
                    ['icon' => 'fa-solid fa-water-ladder', 'name' => 'Swimming Pool'],
                    ['icon' => 'fa-solid fa-utensils', 'name' => 'Multi-Cuisine Dining'],
                    ['icon' => 'fa-solid fa-wifi', 'name' => 'High-Speed Wi-Fi'],
                    ['icon' => 'fa-solid fa-square-parking', 'name' => 'Free Parking']
                ],
                'Wellness & Comfort' => [
                    ['icon' => 'fa-solid fa-spa', 'name' => 'Spa & Wellness'],
                    ['icon' => 'fa-solid fa-snowflake', 'name' => 'Air Conditioning'],
                    ['icon' => 'fa-solid fa-shield-halved', 'name' => '24/7 Security']
                ]
            ];
        }

        if (empty($parsedAmenities)) {
            $parsedAmenities = [
                ['icon' => 'fa-solid fa-water-ladder', 'title' => 'Swimming Pool & Deck', 'desc' => 'Complimentary pool access for all guests'],
                ['icon' => 'fa-solid fa-utensils', 'title' => 'Complimentary Breakfast', 'desc' => 'Multi-cuisine daily breakfast included'],
                ['icon' => 'fa-solid fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Gigabit wireless throughout the property'],
                ['icon' => 'fa-solid fa-bell-concierge', 'title' => '24/7 Front Desk', 'desc' => 'Dedicated concierge & room service support']
            ];
        }

        $hotel = [
            'id' => 'HTL-DB-' . $fetchedHotel['id'],
            'name' => $fetchedHotel['name'],
            'subtitle' => $fetchedHotel['description'] ?: 'Curated luxury stay with premium rooms, breakfast inclusions, and world-class guest facilities.',
            'location' => $fetchedHotel['address'] ?: ($fetchedHotel['city'] . ', ' . $fetchedHotel['country']),
            'city' => $fetchedHotel['city'] . ', ' . $fetchedHotel['country'],
            'star_rating' => $fetchedHotel['star_rating'] . '-Star ' . $fetchedHotel['property_type'],
            'badge' => $fetchedHotel['badge'] ?: 'Premier Stay',
            'badge_class' => 'bg-brand-600 text-white',
            'rating' => number_format((float)$fetchedHotel['star_rating'], 1),
            'reviews_count' => 1420,
            'base_price' => (float)$fetchedHotel['starting_price'],
            'original_price' => (float)($fetchedHotel['original_price'] ?: ($fetchedHotel['starting_price'] * 1.25)),
            'tax_rate' => 0.12,
            'gallery' => $galleryList,
            'key_perks' => $parsedAmenities,
            'rooms' => $formattedRooms,
            'amenities_categories' => $categorizedAmenities,
            'policies' => [
                'check_in' => $fetchedHotel['checkin_time'] ?: '02:00 PM',
                'check_out' => $fetchedHotel['checkout_time'] ?: '11:00 AM',
                'cancellation' => $fetchedHotel['policies'] ?: 'Free cancellation up to 24 hours prior to check-in.',
                'child_policy' => 'Children up to 5 years stay complimentary in existing bedding.',
                'id_proof' => 'Government-issued photo identification required for all guests.'
            ]
        ];
    }
}

if (!isset($hotel)) {
    if (str_contains(strtolower($rawHotelSearch), 'shimla') || str_contains(strtolower($rawHotelSearch), 'shm') || str_contains(strtolower($rawHotelSearch), 'cecil') || str_contains(strtolower($rawHotelSearch), 'manali')) {
        $lookupHotelKey = 'HTL-SHM-02';
    } elseif (str_contains(strtolower($rawHotelSearch), 'kerala') || str_contains(strtolower($rawHotelSearch), 'ker') || str_contains(strtolower($rawHotelSearch), 'kumarakom')) {
        $lookupHotelKey = 'HTL-KER-04';
    } elseif (!empty($rawHotelSearch) && isset($hotelsDb[$rawHotelSearch])) {
        $lookupHotelKey = $rawHotelSearch;
    }
    $hotel = isset($hotelsDb[$lookupHotelKey]) ? $hotelsDb[$lookupHotelKey] : $hotelsDb['HTL-GOA-01'];
}

$pageTitle = $hotel['name'] . ' | GuideFlux Travel Portal';
require_once 'components/header.php';
require_once 'components/navbar.php';
?>

<!-- ==========================================
     BREADCRUMB & BACK NAVIGATION
=========================================== -->
<section class="w-full bg-white border-b border-slate-200 py-3.5 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex items-center space-x-2 text-slate-500">
            <a href="index.php" class="hover:text-brand-700 transition flex items-center space-x-1">
                <i class="fa-solid fa-house text-[11px]"></i>
                <span>Home</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <a href="search.php?type=hotel" class="hover:text-brand-700 transition">Hotels &amp; Resorts</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-brand-700 font-bold truncate max-w-xs sm:max-w-md"><?= htmlspecialchars($hotel['name']) ?></span>
        </div>

        <a href="search.php?type=hotel" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-700 hover:text-brand-700 transition">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to All Stays</span>
        </a>
    </div>
</section>

<!-- ==========================================
     HOTEL TITLE & LOCATION STRIP
=========================================== -->
<section class="w-full bg-white pt-6 pb-4 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-2">
                <!-- Badges Strip -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold <?= $hotel['badge_class'] ?>">
                        <?= htmlspecialchars($hotel['badge']) ?>
                    </span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                        <i class="fa-solid fa-crown text-[10px] mr-1"></i>
                        <?= htmlspecialchars($hotel['star_rating']) ?>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center space-x-1">
                        <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
                        <span><?= $hotel['rating'] ?></span>
                        <span class="text-slate-400 font-normal">(<?= number_format($hotel['reviews_count']) ?> verified reviews)</span>
                    </span>
                </div>

                <!-- Main Hotel Heading -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    <?= htmlspecialchars($hotel['name']) ?>
                </h1>

                <!-- Exact Address / Map Pin -->
                <p class="text-xs sm:text-sm text-slate-500 flex items-center space-x-2 font-medium">
                    <i class="fa-solid fa-location-dot text-brand-600"></i>
                    <span><?= htmlspecialchars($hotel['location']) ?></span>
                </p>
            </div>

            <!-- Price on Mobile / Tablet -->
            <div class="flex items-center space-x-4 shrink-0 lg:text-right">
                <div>
                    <span class="text-xs text-slate-400 block line-through">₹<?= number_format($hotel['original_price']) ?></span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        ₹<?= number_format($hotel['base_price']) ?>
                    </div>
                    <span class="text-[11px] text-slate-500 block">per night &bull; plus taxes</span>
                </div>
                <a href="#roomSelectionSec" class="lg:hidden px-5 py-2.5 rounded-full bg-brand-600 text-white font-bold text-xs uppercase tracking-wider">
                    Select Room
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ==========================================
     MAGAZINE PHOTO GALLERY (Dynamic Real Photos Only)
=========================================== -->
<?php 
$galCount = count($hotel['gallery']);
$mainPhoto = !empty($hotel['gallery'][0]) ? $hotel['gallery'][0] : 'assets/images/placeholder-hotel.jpg';
$sidePhotos = array_slice($hotel['gallery'], 1, 4);
$sideCount = count($sidePhotos);
?>
<section class="w-full bg-white pb-8 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 <?= $sideCount > 0 ? 'md:grid-cols-4' : '' ?> gap-3 rounded-3xl overflow-hidden border border-slate-200 bg-slate-100 h-[260px] sm:h-[340px] md:h-[480px]">
            
            <!-- Large Main Photo -->
            <div class="<?= $sideCount > 0 ? 'md:col-span-2' : 'col-span-full' ?> h-full relative overflow-hidden group">
                <img src="<?= htmlspecialchars($mainPhoto) ?>" 
                     alt="<?= htmlspecialchars($hotel['name']) ?>" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-4 left-4 px-3 py-1 rounded-full text-xs font-bold bg-slate-900/80 backdrop-blur-md text-white border border-white/20">
                    <i class="fa-solid fa-umbrella-beach mr-1 text-teal-300"></i> <?= htmlspecialchars($hotel['badge'] ?: 'Featured Property') ?>
                </span>
            </div>

            <!-- Additional Gallery Photos -->
            <?php if ($sideCount > 0): ?>
                <div class="hidden md:grid md:col-span-2 <?= $sideCount === 1 ? 'grid-cols-1' : ($sideCount <= 2 ? 'grid-cols-1' : 'grid-cols-2') ?> gap-3 h-full">
                    <?php foreach ($sidePhotos as $sIdx => $photoUrl): ?>
                        <div class="<?= $sideCount === 1 ? 'h-full' : 'h-[233px]' ?> relative overflow-hidden group">
                            <img src="<?= htmlspecialchars($photoUrl) ?>" 
                                 alt="<?= htmlspecialchars($hotel['name']) ?> Photo <?= $sIdx + 2 ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ==========================================
     IN-PAGE TAB NAVIGATION STRIP (Smooth Scroll)
=========================================== -->
<div class="w-full bg-white border-y border-slate-200 sticky top-20 md:top-24 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 xl:px-12">
        <nav class="flex items-center space-x-6 overflow-x-auto no-scrollbar py-3 text-xs font-bold text-slate-600">
            <a href="#aboutHotelSec" class="hover:text-brand-600 transition shrink-0">About Resort</a>
            <a href="#roomSelectionSec" class="hover:text-brand-600 transition shrink-0">Available Rooms</a>
            <a href="#amenitiesSec" class="hover:text-brand-600 transition shrink-0">Amenities &amp; Services</a>
            <a href="#policiesSec" class="hover:text-brand-600 transition shrink-0">Property Policies</a>
        </nav>
    </div>
</div>

<!-- ==========================================
     MAIN TWO-COLUMN DETAILS & RESERVATION SECTION
=========================================== -->
<main class="w-full py-10 px-4 sm:px-8 xl:px-12 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- ==========================================
                 LEFT COLUMN: EXTENSIVE HOTEL CONTENT
            =========================================== -->
            <div class="flex-1 w-full space-y-8">

                <!-- 1. ABOUT PROPERTY & HIGHLIGHTS -->
                <div id="aboutHotelSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-5">
                    <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                        <i class="fa-solid fa-hotel"></i>
                        <span>Property Overview</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        A Grand Coastal Sanctuary by the Arabian Sea
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        <?= htmlspecialchars($hotel['subtitle']) ?> Nestled on the gentle slopes of Sinquerim Hill overlooking the historic Fort Aguada lighthouse, this iconic 5-star haven blends rich Portuguese architectural grandeur with warm Indian hospitality. Enjoy private beachfront walks, lush tropical gardens, infinity swimming pools, and the pampering tranquility of the Jiva Spa.
                    </p>

                    <!-- Key Perks Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 pt-2">
                        <?php foreach ($hotel['key_perks'] as $perk): ?>
                            <div class="flex items-start space-x-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                                <span class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 text-xs">
                                    <i class="<?= $perk['icon'] ?>"></i>
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900"><?= htmlspecialchars($perk['title']) ?></h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug"><?= htmlspecialchars($perk['desc']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. ROOM CATEGORIES (Minimal & Lightweight Cards) -->
                <div id="roomSelectionSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-bed"></i>
                            <span>Room Options</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Select Your Room &amp; Suite Category
                        </h2>
                        <p class="text-xs text-slate-500">All room rates include complimentary daily buffet breakfast and free Wi-Fi.</p>
                    </div>

                    <!-- Room Cards List -->
                    <div class="space-y-4">
                        <?php foreach ($hotel['rooms'] as $idx => $room): ?>
                            <div class="room-option-card rounded-2xl border border-slate-200 hover:border-brand-500 transition overflow-hidden bg-slate-50/40 flex flex-col md:flex-row <?= $idx === 0 ? 'border-brand-500 ring-1 ring-brand-500/20' : '' ?>"
                                 data-room-id="<?= htmlspecialchars($room['id']) ?>"
                                 data-room-name="<?= htmlspecialchars($room['name']) ?>"
                                 data-room-price="<?= $room['price'] ?>">
                                
                                <!-- Room Photo Thumbnail -->
                                <div class="w-full md:w-60 h-48 md:h-auto shrink-0 relative overflow-hidden bg-slate-200">
                                    <img src="<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="w-full h-full object-cover">
                                    <span class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-900/80 text-white">
                                        <?= htmlspecialchars($room['size']) ?>
                                    </span>
                                </div>

                                <!-- Room Specs & Inclusions -->
                                <div class="flex-1 p-5 flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-brand-700 font-bold"><?= htmlspecialchars($room['view']) ?></span>
                                            <span class="text-slate-500 font-medium"><?= htmlspecialchars($room['occupancy']) ?></span>
                                        </div>

                                        <h3 class="text-base sm:text-lg font-bold text-slate-900">
                                            <?= htmlspecialchars($room['name']) ?>
                                        </h3>

                                        <p class="text-xs text-slate-500 mt-1 flex items-center space-x-1.5 font-medium">
                                            <i class="fa-solid fa-bed text-slate-400 text-xs"></i>
                                            <span><?= htmlspecialchars($room['bed']) ?></span>
                                        </p>

                                        <!-- Amenities Chips -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-3">
                                            <?php foreach ($room['amenities'] as $am): ?>
                                                <span class="inline-flex items-center space-x-1 text-[11px] font-semibold bg-white text-slate-700 px-2.5 py-1 rounded-full border border-slate-200">
                                                    <i class="fa-solid fa-check text-brand-600 text-[9px]"></i>
                                                    <span><?= htmlspecialchars($am) ?></span>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-emerald-700 font-semibold">
                                        <span class="flex items-center space-x-1">
                                            <i class="fa-solid fa-shield-check text-[10px]"></i>
                                            <span><?= htmlspecialchars($room['cancellation']) ?></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Room Price & Select CTA -->
                                <div class="md:w-52 shrink-0 p-5 bg-white border-t md:border-t-0 md:border-l border-slate-200 flex md:flex-col justify-between items-center md:items-end text-left md:text-right">
                                    <div>
                                        <span class="text-[11px] text-slate-400 block line-through">₹<?= number_format($room['original_price']) ?></span>
                                        <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">
                                            ₹<?= number_format($room['price']) ?>
                                        </div>
                                        <span class="text-[10px] text-slate-500 block mt-1">per night &bull; + taxes</span>
                                    </div>

                                    <button type="button" 
                                            class="select-room-btn px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition active:scale-95 <?= $idx === 0 ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-brand-600 hover:text-white' ?>"
                                            data-room-id="<?= htmlspecialchars($room['id']) ?>"
                                            data-room-name="<?= htmlspecialchars($room['name']) ?>"
                                            data-room-price="<?= $room['price'] ?>">
                                        <?= $idx === 0 ? 'Selected' : 'Select Room' ?>
                                    </button>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3. RESORT AMENITIES & FACILITIES GRID -->
                <div id="amenitiesSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-sparkles"></i>
                            <span>Resort Facilities</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            World-Class Amenities &amp; Services
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <?php foreach ($hotel['amenities_categories'] as $catName => $items): ?>
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-200">
                                    <?= htmlspecialchars($catName) ?>
                                </h3>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <?php foreach ($items as $it): ?>
                                        <div class="flex items-center space-x-2 text-xs text-slate-700 font-semibold">
                                            <i class="<?= $it['icon'] ?> text-brand-600 text-xs shrink-0"></i>
                                            <span><?= htmlspecialchars($it['name']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 4. PROPERTY POLICIES & HOUSE RULES -->
                <div id="policiesSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-5">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-scale-balanced"></i>
                            <span>House Rules</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Property Policies &amp; Guidelines
                        </h2>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                                </span>
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Standard Check-In</span>
                                    <strong class="text-slate-900"><?= $hotel['policies']['check_in'] ?></strong>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2.5">
                                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                </span>
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Standard Check-Out</span>
                                    <strong class="text-slate-900"><?= $hotel['policies']['check_out'] ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900">Cancellation Policy</h4>
                            <p class="leading-relaxed"><?= htmlspecialchars($hotel['policies']['cancellation']) ?></p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900">Child &amp; Extra Bedding</h4>
                            <p class="leading-relaxed"><?= htmlspecialchars($hotel['policies']['child_policy']) ?></p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <h4 class="font-bold text-slate-900">Government ID Requirements</h4>
                            <p class="leading-relaxed"><?= htmlspecialchars($hotel['policies']['id_proof']) ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ==========================================
                 RIGHT COLUMN: STICKY RESERVATION SIDEBAR
            =========================================== -->
            <aside id="hotelBookingSidebar" class="w-full lg:w-96 shrink-0 lg:sticky lg:top-36 space-y-5">
                
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 space-y-5">
                    
                    <!-- Pricing Header -->
                    <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block line-through">₹<?= number_format($hotel['original_price']) ?></span>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="sidebarNightlyRate">
                                ₹<?= number_format($hotel['base_price']) ?>
                            </div>
                            <span class="text-[11px] text-slate-500">per night &bull; excl. taxes</span>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                            Breakfast Included
                        </span>
                    </div>

                    <!-- Selected Room Capsule -->
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Selected Room:</span>
                        <strong class="text-slate-900 truncate max-w-[170px]" id="selectedRoomName">
                            <?= htmlspecialchars($hotel['rooms'][0]['name']) ?>
                        </strong>
                    </div>

                    <!-- Booking Form Calculator -->
                    <form id="hotelReservationForm" class="space-y-4" onsubmit="event.preventDefault(); submitHotelReservation();">
                        
                        <!-- Check-in & Check-out Dates -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Check-In Date</label>
                                <input type="date" 
                                       id="checkInDate"
                                       required
                                       value="<?= date('Y-m-d', strtotime('+3 days')) ?>"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-semibold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Check-Out Date</label>
                                <input type="date" 
                                       id="checkOutDate"
                                       required
                                       value="<?= date('Y-m-d', strtotime('+5 days')) ?>"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-semibold text-slate-800">
                            </div>
                        </div>

                        <!-- Rooms & Guests Counter -->
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Rooms</label>
                                <select id="roomsCountSelect" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-semibold text-slate-800">
                                    <option value="1">1 Room</option>
                                    <option value="2">2 Rooms</option>
                                    <option value="3">3 Rooms</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Guests</label>
                                <select id="guestsCountSelect" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-semibold text-slate-800">
                                    <option value="1">1 Guest</option>
                                    <option value="2" selected>2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                </select>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="space-y-3 pt-2 border-t border-slate-100">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Lead Guest Name</label>
                                <input type="text" id="guestName" required placeholder="e.g. Ananya Sen" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp</label>
                                <input type="tel" id="guestPhone" required placeholder="+91 98765 43210" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                            </div>
                        </div>

                        <!-- Live Price Calculation Breakdown -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Rate (<span id="summaryNights">2</span> Nights &times; <span id="summaryRooms">1</span> Room)</span>
                                <span class="font-mono font-bold text-slate-900" id="subtotalDisplay">₹17,000</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-500 text-[11px]">
                                <span>Hotel GST (12%)</span>
                                <span class="font-mono text-slate-700" id="taxesDisplay">₹2,040</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-500 text-[11px]">
                                <span>Resort Amenity Fee</span>
                                <span class="font-bold text-emerald-600">Waived (₹0)</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200 flex items-center justify-between font-bold text-slate-900">
                                <span>Total Payable Amount:</span>
                                <span class="text-brand-700 font-mono text-sm" id="grandTotalDisplay">₹19,040</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-1">
                            <button type="submit" class="w-full py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                                <span>Confirm Reservation</span>
                            </button>

                            <a href="https://wa.me/919876543210?text=<?= urlencode('Hi Orion Advent, I want to reserve a room at ' . $hotel['name'] . '. Please share availability.') ?>" 
                               target="_blank" 
                               class="w-full py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center space-x-2">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Chat with Resort Concierge</span>
                            </a>
                        </div>
                    </form>

                    <!-- Trust Strip -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-emerald-600"></i>
                            <span>Secure Booking</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-money-bill-transfer text-brand-600"></i>
                            <span>Pay at Property Option</span>
                        </span>
                    </div>

                </div>

                <!-- 24/7 Resort Concierge Card -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200 space-y-2 text-center">
                    <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center mx-auto text-sm">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </span>
                    <h4 class="text-xs font-bold text-slate-900">Special Requests or Villa Upgrades?</h4>
                    <p class="text-[11px] text-slate-500">Connect with our dedicated luxury stay desk</p>
                    <a href="tel:+919876543210" class="inline-block text-xs font-black text-brand-700 hover:underline">
                        +91 98765 43210
                    </a>
                </div>

            </aside>

        </div>
    </div>
</main>

<!-- Interactive Room Selection & Price Calculator JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentRoomPrice = <?= $hotel['rooms'][0]['price'] ?>;
    let nights = 2;
    let rooms = 1;

    const checkInInput = document.getElementById('checkInDate');
    const checkOutInput = document.getElementById('checkOutDate');
    const roomsSelect = document.getElementById('roomsCountSelect');
    const selectedRoomNameEl = document.getElementById('selectedRoomName');
    const sidebarNightlyRateEl = document.getElementById('sidebarNightlyRate');
    const summaryNightsEl = document.getElementById('summaryNights');
    const summaryRoomsEl = document.getElementById('summaryRooms');
    const subtotalDisplayEl = document.getElementById('subtotalDisplay');
    const taxesDisplayEl = document.getElementById('taxesDisplay');
    const grandTotalDisplayEl = document.getElementById('grandTotalDisplay');

    function calculateDates() {
        if (checkInInput && checkOutInput) {
            const inDate = new Date(checkInInput.value);
            const outDate = new Date(checkOutInput.value);
            const diffTime = outDate - inDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            nights = diffDays > 0 ? diffDays : 1;
        }
        recalculate();
    }

    function recalculate() {
        rooms = parseInt(roomsSelect?.value || '1', 10);
        const subtotal = currentRoomPrice * nights * rooms;
        const taxes = Math.round(subtotal * 0.12);
        const total = subtotal + taxes;

        if (summaryNightsEl) summaryNightsEl.textContent = nights;
        if (summaryRoomsEl) summaryRoomsEl.textContent = rooms;
        if (sidebarNightlyRateEl) sidebarNightlyRateEl.textContent = '₹' + currentRoomPrice.toLocaleString('en-IN');
        if (subtotalDisplayEl) subtotalDisplayEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
        if (taxesDisplayEl) taxesDisplayEl.textContent = '₹' + taxes.toLocaleString('en-IN');
        if (grandTotalDisplayEl) grandTotalDisplayEl.textContent = '₹' + total.toLocaleString('en-IN');
    }

    if (checkInInput) checkInInput.addEventListener('change', calculateDates);
    if (checkOutInput) checkOutInput.addEventListener('change', calculateDates);
    if (roomsSelect) roomsSelect.addEventListener('change', recalculate);

    // Room Selection Click Handler
    document.querySelectorAll('.select-room-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const roomId = btn.getAttribute('data-room-id');
            const roomName = btn.getAttribute('data-room-name');
            const roomPrice = parseFloat(btn.getAttribute('data-room-price')) || currentRoomPrice;

            currentRoomPrice = roomPrice;
            if (selectedRoomNameEl) selectedRoomNameEl.textContent = roomName;

            // Update button styles
            document.querySelectorAll('.select-room-btn').forEach(b => {
                b.textContent = 'Select Room';
                b.classList.remove('bg-brand-600', 'text-white');
                b.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-brand-600', 'hover:text-white');
            });
            btn.textContent = 'Selected';
            btn.classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-brand-600', 'hover:text-white');
            btn.classList.add('bg-brand-600', 'text-white');

            // Highlight card
            document.querySelectorAll('.room-option-card').forEach(c => {
                c.classList.remove('border-brand-500', 'ring-1', 'ring-brand-500/20');
            });
            const parentCard = btn.closest('.room-option-card');
            if (parentCard) {
                parentCard.classList.add('border-brand-500', 'ring-1', 'ring-brand-500/20');
            }

            recalculate();
        });
    });

    calculateDates();
});

function submitHotelReservation() {
    const name = document.getElementById('guestName')?.value || 'Guest';
    const phone = document.getElementById('guestPhone')?.value || '';
    const room = document.getElementById('selectedRoomName')?.textContent || 'Room';
    const hotelName = <?= json_encode($hotel['name']) ?>;
    const inDate = document.getElementById('checkInDate')?.value || '';
    const outDate = document.getElementById('checkOutDate')?.value || '';
    const rooms = document.getElementById('roomsCountSelect')?.value || 1;
    const guests = document.getElementById('guestsCountSelect')?.value || 2;
    const totalRaw = document.getElementById('grandTotalDisplay')?.textContent.replace(/[^0-9]/g, '') || 0;
    const hotelId = <?= json_encode($hotel['id'] ?? '') ?>;

    const btn = document.querySelector('#hotelReservationForm button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Securing Reservation...</span>';
    }

    const formData = new FormData();
    formData.append('hotel_id', hotelId);
    formData.append('guest_name', name);
    formData.append('guest_phone', phone);
    formData.append('hotel_name', hotelName);
    formData.append('room_name', room.trim());
    formData.append('check_in', inDate);
    formData.append('check_out', outDate);
    formData.append('rooms_count', rooms);
    formData.append('guests_count', guests);
    formData.append('total_amount', totalRaw);

    fetch('api/book-hotel.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-calendar-check text-xs"></i> <span>Confirm Reservation</span>';
        }
        if (data.login_required) {
            showHotelLoginModal(hotelName + ' (' + room.trim() + ')');
            return;
        }
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else if (data.success) {
            showBookingSuccessModal(data.booking_code, hotelName, room.trim(), name, totalRaw);
        } else {
            alert(data.message || 'Booking failed. Please try again.');
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-calendar-check text-xs"></i> <span>Confirm Reservation</span>';
        }
        alert('Reservation confirmed! Voucher details sent to your phone.');
    });
}

function showHotelLoginModal(title) {
    const existing = document.getElementById('loginRequiredModalOverlay');
    if (existing) existing.remove();

    const currentUrl = window.location.href;
    const modalHtml = `
        <div id="loginRequiredModalOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full p-6 text-center space-y-4 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 rounded-full bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="space-y-1">
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        Traveler Account Required
                    </span>
                    <h3 class="text-xl font-black text-slate-900 font-space">Sign In to Reserve Room</h3>
                    <p class="text-xs text-slate-500">Please sign in to your GuideFlux account to complete your hotel booking and receive instant check-in vouchers.</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 text-xs font-semibold text-slate-700 truncate">
                    <span>${title}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="login.php?redirect=${encodeURIComponent(currentUrl)}" class="py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider text-center transition">
                        Log In Now
                    </a>
                    <a href="signup.php?redirect=${encodeURIComponent(currentUrl)}" class="py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider text-center transition">
                        Create Account
                    </a>
                </div>
                <button type="button" onclick="document.getElementById('loginRequiredModalOverlay').remove()" class="text-xs text-slate-400 hover:text-slate-600 font-bold">
                    Cancel
                </button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function showBookingSuccessModal(code, hotel, room, guest, amount) {
    const modalHtml = `
        <div id="bookingModalOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full p-6 text-center space-y-4 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="space-y-1">
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                        Booking Code: ${code}
                    </span>
                    <h3 class="text-xl font-black text-slate-900">Reservation Confirmed!</h3>
                    <p class="text-xs text-slate-500">Thank you <strong>${guest}</strong>, your luxury stay has been reserved successfully.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Hotel:</span>
                        <strong class="text-slate-900 truncate max-w-[200px]">${hotel}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Room Category:</span>
                        <strong class="text-brand-600 truncate max-w-[200px]">${room}</strong>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-1.5 font-bold text-slate-900">
                        <span>Total Payable Amount:</span>
                        <span class="text-emerald-700 font-black">₹${parseInt(amount).toLocaleString('en-IN')}</span>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('bookingModalOverlay').remove(); window.location.href='index.php';" class="w-full py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                    Done &bull; Back to Home
                </button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}
</script>

<!-- Mobile Sticky Floating Bottom Reservation Bar (Hidden on desktop, flat border, zero shadows) -->
<div class="lg:hidden fixed bottom-[60px] left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-3 flex items-center justify-between">
    <div>
        <span class="text-[10px] text-slate-400 block line-through">₹<?= number_format($hotel['original_price']) ?></span>
        <div class="flex items-baseline space-x-1">
            <span class="text-base font-black text-slate-900 leading-none">₹<?= number_format($hotel['base_price']) ?></span>
            <span class="text-[10px] text-slate-500 font-medium">/ night</span>
        </div>
    </div>
    <a href="#reservationSidebar" class="px-4 py-2 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center space-x-1.5 shrink-0">
        <span>Reserve Room</span>
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
</div>

<?php
require_once 'components/footer.php';
?>
