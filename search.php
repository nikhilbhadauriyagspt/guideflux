<?php
/**
 * Search Results & Listing Portal - Orion Advent
 * - Universal Search across Tour Packages, Flights & Hotels
 * - 100% Flat, Border-First Design (Strictly ZERO shadows)
 * - Zero Emojis (Pure Font Awesome 6 Vector Icons)
 * - Lightweight, Clean, Non-Heavy Minimal Card Architecture
 * - Instant Live Client-Side Filtering, Sorting & URL Synchronization
 * - Integrated Quick Concierge Booking/Inquiry Modal
 */

$pageTitle = 'Search Holiday Packages, Flights & Hotels | Orion Advent';

// Initial Server-Side Query Fallbacks (can be refined via JS client-side instantly)
$initialType = isset($_GET['type']) ? htmlspecialchars(trim($_GET['type'])) : 'all';
$initialQuery = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';

// Master Datasets: Packages, Flights, Hotels
$searchItems = [
    // ==========================================
    // 1. TOUR PACKAGES (10 Handpicked Circuits)
    // ==========================================
    [
        'id' => 'PKG-KASHMIR-01',
        'type' => 'package',
        'sub_type' => 'Domestic Tour',
        'title' => 'Kashmir Paradise & Dal Lake Houseboat',
        'destination' => 'Kashmir • Srinagar • Gulmarg • Pahalgam',
        'route' => 'Srinagar • Gulmarg • Pahalgam • Sonmarg',
        'duration' => '5N / 6D',
        'badge' => 'Bestseller',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 17999,
        'original_price' => 22999,
        'rating' => 4.9,
        'reviews' => '1,420 reviews',
        'image' => 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-sailboat', 'label' => 'Houseboat Stay'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Breakfast & Dinner'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Private Cab'],
            ['icon' => 'fa-solid fa-camera', 'label' => 'Sightseeing'],
        ],
        'perks' => 'Free Cancellation till 48 hrs • Instant Voucher',
        'tags' => 'kashmir srinagar gulmarg pahalgam domestic hills honeymoon breakfast cancellation cab sightseeing houseboat',
    ],
    [
        'id' => 'PKG-KERALA-02',
        'type' => 'package',
        'sub_type' => 'Domestic Tour',
        'title' => 'Kerala Backwaters & Misty Munnar Hills',
        'destination' => 'Kerala • Munnar • Alleppey • Thekkady',
        'route' => 'Kochi • Munnar • Thekkady • Alleppey',
        'duration' => '4N / 5D',
        'badge' => 'Honeymoon Fav',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 18500,
        'original_price' => 24000,
        'rating' => 4.8,
        'reviews' => '980 reviews',
        'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-sailboat', 'label' => 'Luxury Houseboat'],
            ['icon' => 'fa-solid fa-mug-hot', 'label' => 'Tea Plantation Tour'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Meals Included'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Private Sedan'],
        ],
        'perks' => 'Ayurvedic Massage Voucher • Free Reschedule',
        'tags' => 'kerala munnar alleppey thekkady domestic nature honeymoon breakfast cancellation cab houseboat sightseeing',
    ],
    [
        'id' => 'PKG-HIMACHAL-03',
        'type' => 'package',
        'sub_type' => 'Domestic Tour',
        'title' => 'Himachal Alpine Valley & Solang Adventure',
        'destination' => 'Himachal • Shimla • Manali • Solang',
        'route' => 'Shimla • Kufri • Kullu • Manali • Solang',
        'duration' => '5N / 6D',
        'badge' => 'Snow Peaks',
        'badge_class' => 'bg-sky-600 text-white',
        'price' => 14999,
        'original_price' => 19500,
        'rating' => 4.8,
        'reviews' => '1,650 reviews',
        'image' => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-mountain', 'label' => 'Valley Resort'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Daily Breakfast'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Solang & Atal Cab'],
            ['icon' => 'fa-solid fa-camera', 'label' => 'Local Sightseeing'],
        ],
        'perks' => 'Campfire Night Included • Free Cancellation',
        'tags' => 'manali shimla solang himachal domestic hills adventure breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-RAJASTHAN-04',
        'type' => 'package',
        'sub_type' => 'Domestic Tour',
        'title' => 'Royal Rajasthan Havelis & Fort Heritage',
        'destination' => 'Rajasthan • Jaipur • Udaipur • Jodhpur',
        'route' => 'Jaipur • Ajmer • Pushkar • Jodhpur • Udaipur',
        'duration' => '5N / 6D',
        'badge' => 'Heritage Circuit',
        'badge_class' => 'bg-amber-600 text-white',
        'price' => 16500,
        'original_price' => 21000,
        'rating' => 4.7,
        'reviews' => '890 reviews',
        'image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-chess-rook', 'label' => 'Heritage Haveli'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Royal Breakfast'],
            ['icon' => 'fa-solid fa-water', 'label' => 'Pichola Boat Ride'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Private Chauffeur'],
        ],
        'perks' => 'Fort Entry Fast-Track • Free Cancellation',
        'tags' => 'rajasthan jaipur udaipur jodhpur heritage royal domestic breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-GOA-05',
        'type' => 'package',
        'sub_type' => 'Domestic Tour',
        'title' => 'Goa Beachside Luxury & Mandovi Sunset Cruise',
        'destination' => 'Goa • Calangute • Baga • Mandovi River',
        'route' => 'North Goa Beaches • Fort Aguada • Cruise',
        'duration' => '3N / 4D',
        'badge' => 'Beach Special',
        'badge_class' => 'bg-teal-600 text-white',
        'price' => 11999,
        'original_price' => 15500,
        'rating' => 4.9,
        'reviews' => '2,100 reviews',
        'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-umbrella-beach', 'label' => 'Beachside Resort'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Buffet Breakfast'],
            ['icon' => 'fa-solid fa-ship', 'label' => 'Sunset Cruise Pass'],
            ['icon' => 'fa-solid fa-van-shuttle', 'label' => 'Airport Transfers'],
        ],
        'perks' => 'Free Pool Access • Zero Cancellation Charges',
        'tags' => 'goa beach cruise calangute baga domestic pool breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-DUBAI-06',
        'type' => 'package',
        'sub_type' => 'International Tour',
        'title' => 'Dubai Extravaganza, Burj Khalifa & Desert Safari',
        'destination' => 'Dubai, UAE • Downtown • Marina • Desert',
        'route' => 'Dubai City • Burj Khalifa • Desert Camp • Marina',
        'duration' => '4N / 5D',
        'badge' => 'Trending Global',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 34999,
        'original_price' => 42500,
        'rating' => 4.9,
        'reviews' => '1,780 reviews',
        'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-city', 'label' => '4★ Central Hotel'],
            ['icon' => 'fa-solid fa-building', 'label' => 'Burj Khalifa 124F'],
            ['icon' => 'fa-solid fa-car-side', 'label' => 'Desert Dune Safari'],
            ['icon' => 'fa-solid fa-passport', 'label' => 'UAE Visa Guidance'],
        ],
        'perks' => 'BBQ Dinner & Belly Dance Show • Instant Confirmation',
        'tags' => 'dubai uae desert burj khalifa international luxury visa breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-BALI-07',
        'type' => 'package',
        'sub_type' => 'International Tour',
        'title' => 'Bali Tropical Island Escape & Private Pool Villa',
        'destination' => 'Bali, Indonesia • Ubud • Seminyak • Nusa Penida',
        'route' => 'Ubud Terraces • Seminyak Beach • Nusa Penida Island',
        'duration' => '5N / 6D',
        'badge' => 'Island Paradise',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 28500,
        'original_price' => 36000,
        'rating' => 4.9,
        'reviews' => '1,340 reviews',
        'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-water-ladder', 'label' => '2N Private Pool Villa'],
            ['icon' => 'fa-solid fa-ship', 'label' => 'Nusa Penida Boat'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Floating Breakfast'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Private Chauffeur'],
        ],
        'perks' => 'Balinese Spa Massage Included • Free Date Change',
        'tags' => 'bali indonesia ubud seminyak nusa penida international pool villa honeymoon breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-THAILAND-08',
        'type' => 'package',
        'sub_type' => 'International Tour',
        'title' => 'Thailand Explorer: Bangkok & Coral Island Phuket',
        'destination' => 'Thailand • Bangkok • Pattaya • Phuket',
        'route' => 'Bangkok City • Coral Island Speedboat • Phuket Beach',
        'duration' => '4N / 5D',
        'badge' => 'Top Value',
        'badge_class' => 'bg-indigo-600 text-white',
        'price' => 25999,
        'original_price' => 32000,
        'rating' => 4.8,
        'reviews' => '1,150 reviews',
        'image' => 'https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-hotel', 'label' => '4★ City Stays'],
            ['icon' => 'fa-solid fa-ship', 'label' => 'Coral Island Speedboat'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Daily Breakfast'],
            ['icon' => 'fa-solid fa-van-shuttle', 'label' => 'Airport Transfers'],
        ],
        'perks' => 'Chao Phraya Cruise Dinner Included • Free Reschedule',
        'tags' => 'thailand bangkok pattaya phuket international beach budget breakfast cancellation cab sightseeing',
    ],
    [
        'id' => 'PKG-MALDIVES-09',
        'type' => 'package',
        'sub_type' => 'International Tour',
        'title' => 'Maldives Luxury Overwater Pool Villa Sanctuary',
        'destination' => 'Maldives • North Male Atoll • Private Island',
        'route' => 'Private Speedboat • Overwater Villa • Coral Lagoon',
        'duration' => '3N / 4D',
        'badge' => 'Ultra Luxury',
        'badge_class' => 'bg-cyan-600 text-white',
        'price' => 52000,
        'original_price' => 68000,
        'rating' => 5.0,
        'reviews' => '820 reviews',
        'image' => 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-house-chimney-water', 'label' => 'Overwater Villa'],
            ['icon' => 'fa-solid fa-champagne-glasses', 'label' => 'All-Inclusive Dine'],
            ['icon' => 'fa-solid fa-ship', 'label' => 'Return Speedboat'],
            ['icon' => 'fa-solid fa-fish-fins', 'label' => 'Snorkeling Kit'],
        ],
        'perks' => 'Champagne on Arrival • Free Cancellation',
        'tags' => 'maldives overwater villa luxury honeymoon island international pool breakfast cancellation spa',
    ],
    [
        'id' => 'PKG-VIETNAM-10',
        'type' => 'package',
        'sub_type' => 'International Tour',
        'title' => 'Vietnam Discovery: Hanoi & Halong Bay Luxury Cruise',
        'destination' => 'Vietnam • Hanoi • Halong Bay • Da Nang',
        'route' => 'Hanoi French Quarter • Halong Bay Overnight • Da Nang',
        'duration' => '5N / 6D',
        'badge' => 'Culture & Cruise',
        'badge_class' => 'bg-teal-600 text-white',
        'price' => 36999,
        'original_price' => 45000,
        'rating' => 4.8,
        'reviews' => '640 reviews',
        'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=800&q=80',
        'inclusions' => [
            ['icon' => 'fa-solid fa-ship', 'label' => 'Overnight 5★ Cruise'],
            ['icon' => 'fa-solid fa-archway', 'label' => 'Golden Bridge Pass'],
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Meals on Cruise'],
            ['icon' => 'fa-solid fa-car', 'label' => 'Private AC Vehicle'],
        ],
        'perks' => 'Kayaking in Halong Included • Free Cancellation',
        'tags' => 'vietnam hanoi halong bay cruise international culture breakfast cancellation cab sightseeing',
    ],

    // ==========================================
    // 2. FLIGHT DEALS (8 Verified Flight Routes)
    // ==========================================
    [
        'id' => 'FL-IND-101',
        'type' => 'flight',
        'sub_type' => 'Domestic Flight',
        'airline' => 'IndiGo',
        'airline_code' => '6E-2134',
        'airline_class' => 'Economy Regular',
        'airline_badge' => 'bg-indigo-600 text-white',
        'destination' => 'New Delhi (DEL) to Mumbai (BOM)',
        'title' => 'IndiGo: New Delhi (DEL) to Mumbai (BOM)',
        'from_city' => 'New Delhi',
        'from_code' => 'DEL',
        'from_time' => '06:15 AM',
        'from_airport' => 'IGI Airport, Terminal 3',
        'to_city' => 'Mumbai',
        'to_code' => 'BOM',
        'to_time' => '08:30 AM',
        'to_airport' => 'CSMIA, Terminal 2',
        'duration' => '2h 15m',
        'stops' => 'Non-Stop',
        'badge' => 'Lowest Fare',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 3899,
        'original_price' => 4999,
        'rating' => 4.8,
        'reviews' => '5,400+ bookings',
        'baggage' => '15 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Free Reschedule • Instant Web Check-in • Direct Flight',
        'tags' => 'delhi mumbai indigo domestic flight nonstop cancellation baggage 6e',
    ],
    [
        'id' => 'FL-AI-102',
        'type' => 'flight',
        'sub_type' => 'Domestic Flight',
        'airline' => 'Air India',
        'airline_code' => 'AI-804',
        'airline_class' => 'Economy Standard',
        'airline_badge' => 'bg-rose-600 text-white',
        'destination' => 'Bengaluru (BLR) to New Delhi (DEL)',
        'title' => 'Air India: Bengaluru (BLR) to New Delhi (DEL)',
        'from_city' => 'Bengaluru',
        'from_code' => 'BLR',
        'from_time' => '09:40 AM',
        'from_airport' => 'Kempegowda Intl, Terminal 2',
        'to_city' => 'New Delhi',
        'to_code' => 'DEL',
        'to_time' => '12:25 PM',
        'to_airport' => 'IGI Airport, Terminal 3',
        'duration' => '2h 45m',
        'stops' => 'Non-Stop',
        'badge' => 'Complimentary Meal',
        'badge_class' => 'bg-amber-600 text-white',
        'price' => 4850,
        'original_price' => 5999,
        'rating' => 4.6,
        'reviews' => '3,100+ bookings',
        'baggage' => '20 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Hot Meal Included • Free Seat Selection • Non-Stop',
        'tags' => 'bangalore bengaluru delhi air india domestic flight nonstop breakfast baggage ai',
    ],
    [
        'id' => 'FL-VIS-103',
        'type' => 'flight',
        'sub_type' => 'Domestic Flight',
        'airline' => 'Vistara',
        'airline_code' => 'UK-817',
        'airline_class' => 'Premium Economy',
        'airline_badge' => 'bg-purple-700 text-white',
        'destination' => 'Mumbai (BOM) to Goa (GOI)',
        'title' => 'Vistara: Mumbai (BOM) to Goa Dabolim (GOI)',
        'from_city' => 'Mumbai',
        'from_code' => 'BOM',
        'from_time' => '11:15 AM',
        'from_airport' => 'CSMIA, Terminal 2',
        'to_city' => 'Goa',
        'to_code' => 'GOI',
        'to_time' => '12:35 PM',
        'to_airport' => 'Dabolim Airport',
        'duration' => '1h 20m',
        'stops' => 'Non-Stop',
        'badge' => 'Premium Service',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 3299,
        'original_price' => 4400,
        'rating' => 4.9,
        'reviews' => '4,200+ bookings',
        'baggage' => '15 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Gourmet In-Flight Snack • Priority Baggage • Free Date Change',
        'tags' => 'mumbai goa vistara domestic flight nonstop cancellation baggage breakfast uk',
    ],
    [
        'id' => 'FL-AKA-104',
        'type' => 'flight',
        'sub_type' => 'Domestic Flight',
        'airline' => 'Akasa Air',
        'airline_code' => 'QP-1302',
        'airline_class' => 'Economy Saver',
        'airline_badge' => 'bg-orange-600 text-white',
        'destination' => 'New Delhi (DEL) to Leh Ladakh (IXL)',
        'title' => 'Akasa Air: New Delhi (DEL) to Leh Ladakh (IXL)',
        'from_city' => 'New Delhi',
        'from_code' => 'DEL',
        'from_time' => '07:00 AM',
        'from_airport' => 'IGI Airport, Terminal 2',
        'to_city' => 'Leh Ladakh',
        'to_code' => 'IXL',
        'to_time' => '08:25 AM',
        'to_airport' => 'Kushok Bakula Rimpochee',
        'duration' => '1h 25m',
        'stops' => 'Non-Stop',
        'badge' => 'Scenic Route',
        'badge_class' => 'bg-sky-600 text-white',
        'price' => 5499,
        'original_price' => 6800,
        'rating' => 4.7,
        'reviews' => '1,890+ bookings',
        'baggage' => '15 Kg Check-in • 7 Kg Cabin',
        'perks' => 'In-Seat USB Charging • Café Akasa Snack Pack • Direct',
        'tags' => 'delhi leh ladakh akasa domestic flight nonstop baggage qp',
    ],
    [
        'id' => 'FL-SPJ-105',
        'type' => 'flight',
        'sub_type' => 'Domestic Flight',
        'airline' => 'SpiceJet',
        'airline_code' => 'SG-124',
        'airline_class' => 'Economy Promo',
        'airline_badge' => 'bg-red-600 text-white',
        'destination' => 'New Delhi (DEL) to Srinagar (SXR)',
        'title' => 'SpiceJet: New Delhi (DEL) to Srinagar Kashmir (SXR)',
        'from_city' => 'New Delhi',
        'from_code' => 'DEL',
        'from_time' => '02:10 PM',
        'from_airport' => 'IGI Airport, Terminal 3',
        'to_city' => 'Srinagar',
        'to_code' => 'SXR',
        'to_time' => '03:40 PM',
        'to_airport' => 'Sheikh ul-Alam Intl Airport',
        'duration' => '1h 30m',
        'stops' => 'Non-Stop',
        'badge' => 'Direct Kashmir',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 4199,
        'original_price' => 5200,
        'rating' => 4.5,
        'reviews' => '2,400+ bookings',
        'baggage' => '15 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Instant Boarding Pass • SpiceMax Option Available',
        'tags' => 'delhi srinagar kashmir spicejet domestic flight nonstop baggage sg',
    ],
    [
        'id' => 'FL-EMI-106',
        'type' => 'flight',
        'sub_type' => 'International Flight',
        'airline' => 'Emirates',
        'airline_code' => 'EK-511',
        'airline_class' => 'Economy Flex',
        'airline_badge' => 'bg-red-700 text-white',
        'destination' => 'New Delhi (DEL) to Dubai (DXB)',
        'title' => 'Emirates: New Delhi (DEL) to Dubai Intl (DXB)',
        'from_city' => 'New Delhi',
        'from_code' => 'DEL',
        'from_time' => '10:35 AM',
        'from_airport' => 'IGI Airport, Terminal 3',
        'to_city' => 'Dubai',
        'to_code' => 'DXB',
        'to_time' => '01:00 PM',
        'to_airport' => 'Dubai Intl, Terminal 3',
        'duration' => '3h 55m',
        'stops' => 'Non-Stop',
        'badge' => 'World-Class',
        'badge_class' => 'bg-amber-600 text-white',
        'price' => 18990,
        'original_price' => 23500,
        'rating' => 4.9,
        'reviews' => '6,800+ bookings',
        'baggage' => '30 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Multi-Course Dining • In-Flight Wi-Fi & ice Entertainment',
        'tags' => 'delhi dubai emirates international flight nonstop breakfast baggage cancellation wifi ek',
    ],
    [
        'id' => 'FL-SIA-107',
        'type' => 'flight',
        'sub_type' => 'International Flight',
        'airline' => 'Singapore Airlines',
        'airline_code' => 'SQ-403',
        'airline_class' => 'Economy Standard',
        'airline_badge' => 'bg-blue-900 text-white',
        'destination' => 'New Delhi (DEL) to Singapore Changi (SIN)',
        'title' => 'Singapore Airlines: New Delhi (DEL) to Singapore (SIN)',
        'from_city' => 'New Delhi',
        'from_code' => 'DEL',
        'from_time' => '09:50 AM',
        'from_airport' => 'IGI Airport, Terminal 3',
        'to_city' => 'Singapore',
        'to_code' => 'SIN',
        'to_time' => '06:15 PM',
        'to_airport' => 'Changi Airport, Terminal 3',
        'duration' => '5h 55m',
        'stops' => 'Non-Stop',
        'badge' => '5-Star Airline',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 22400,
        'original_price' => 28000,
        'rating' => 5.0,
        'reviews' => '4,900+ bookings',
        'baggage' => '25 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Generous Legroom • KrisWorld Entertainment • Free Meals',
        'tags' => 'delhi singapore singapore airlines international flight nonstop breakfast baggage cancellation wifi sq',
    ],
    [
        'id' => 'FL-QAT-108',
        'type' => 'flight',
        'sub_type' => 'International Flight',
        'airline' => 'Qatar Airways',
        'airline_code' => 'QR-571',
        'airline_class' => 'Economy Classic',
        'airline_badge' => 'bg-pink-900 text-white',
        'destination' => 'Mumbai (BOM) to Doha (DOH)',
        'title' => 'Qatar Airways: Mumbai (BOM) to Doha Hamad Intl (DOH)',
        'from_city' => 'Mumbai',
        'from_code' => 'BOM',
        'from_time' => '04:30 AM',
        'from_airport' => 'CSMIA, Terminal 2',
        'to_city' => 'Doha',
        'to_code' => 'DOH',
        'to_time' => '06:05 AM',
        'to_airport' => 'Hamad Intl Airport',
        'duration' => '4h 05m',
        'stops' => 'Non-Stop',
        'badge' => 'Global Winner',
        'badge_class' => 'bg-rose-700 text-white',
        'price' => 19850,
        'original_price' => 25000,
        'rating' => 4.9,
        'reviews' => '3,700+ bookings',
        'baggage' => '30 Kg Check-in • 7 Kg Cabin',
        'perks' => 'Oryx Screen • Gourmet Snacks • Free Date Reschedule',
        'tags' => 'mumbai doha qatar international flight nonstop breakfast baggage cancellation wifi qr',
    ],

    // ==========================================
    // 3. HOTELS & LUXURY RESORTS (8 Top Stays)
    // ==========================================
    [
        'id' => 'HTL-GOA-01',
        'type' => 'hotel',
        'sub_type' => '5-Star Beach Resort',
        'title' => 'Taj Fort Aguada Resort & Spa, North Goa',
        'destination' => 'Goa • Sinquerim Beach • Candolim',
        'room_type' => 'Deluxe Sea Facing Room with Private Balcony',
        'location' => 'Sinquerim Beach, Candolim, North Goa',
        'badge' => '5★ Luxury Beachfront',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 8500,
        'original_price' => 11500,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '2,410 reviews',
        'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Free Breakfast'],
            ['icon' => 'fa-solid fa-person-swimming', 'label' => 'Ocean Infinity Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Jiva Spa'],
            ['icon' => 'fa-solid fa-wifi', 'label' => 'High-Speed Wi-Fi'],
            ['icon' => 'fa-solid fa-umbrella-beach', 'label' => 'Beach Access'],
        ],
        'perks' => 'Free Cancellation till 24 hrs • Welcome Drinks on Arrival',
        'tags' => 'goa candolim calangute beach taj resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-SHM-02',
        'type' => 'hotel',
        'sub_type' => '5-Star Heritage Stay',
        'title' => 'The Oberoi Cecil, Heritage Mountain Retreat',
        'destination' => 'Shimla • Mall Road • Chaura Maidan',
        'room_type' => 'Premier Mountain View Room with Fireplace',
        'location' => 'Chaura Maidan, Mall Road, Shimla, Himachal',
        'badge' => '5★ Colonial Heritage',
        'badge_class' => 'bg-amber-600 text-white',
        'price' => 11200,
        'original_price' => 15000,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '1,850 reviews',
        'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Free Breakfast'],
            ['icon' => 'fa-solid fa-temperature-arrow-up', 'label' => 'Heated Indoor Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Oberoi Spa'],
            ['icon' => 'fa-solid fa-wifi', 'label' => 'Free Wi-Fi'],
        ],
        'perks' => 'Heritage Walk with Historian • Zero Cancellation Fee',
        'tags' => 'shimla manali himachal hills oberoi cecil resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-GOA-03',
        'type' => 'hotel',
        'sub_type' => '5-Star Luxury Resort',
        'title' => 'W Goa Beachfront Retreat & Sunset Rock',
        'destination' => 'Goa • Vagator Beach • North Goa',
        'room_type' => 'Wonderful King Room with Private Garden Terrace',
        'location' => 'Vagator Beach, North Goa',
        'badge' => '5★ Vibrant Luxury',
        'badge_class' => 'bg-teal-600 text-white',
        'price' => 9800,
        'original_price' => 13000,
        'price_unit' => '/ night',
        'rating' => 4.8,
        'reviews' => '1,590 reviews',
        'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Artisan Breakfast'],
            ['icon' => 'fa-solid fa-person-swimming', 'label' => 'WET Outdoor Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Away Spa'],
            ['icon' => 'fa-solid fa-martini-glass-citrus', 'label' => 'Rock Bar Access'],
        ],
        'perks' => 'Direct Beach Boardwalk • Free Reschedule',
        'tags' => 'goa vagator beach w goa luxury resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-KER-04',
        'type' => 'hotel',
        'sub_type' => '5-Star Heritage Sanctuary',
        'title' => 'Kumarakom Lake Resort & Heritage Villas',
        'destination' => 'Kerala • Vembanad Lake • Kottayam',
        'room_type' => 'Heritage Villa with Private Meandering Pool Access',
        'location' => 'Vembanad Lake, Kumarakom, Kerala',
        'badge' => '5★ Lake Backwaters',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 12800,
        'original_price' => 17000,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '2,120 reviews',
        'image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Traditional Breakfast'],
            ['icon' => 'fa-solid fa-water-ladder', 'label' => 'Infinity Lake Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Ayurmana Spa'],
            ['icon' => 'fa-solid fa-ship', 'label' => 'Evening Sunset Cruise'],
        ],
        'perks' => 'Complimentary Pottery & Weaving Workshop • Free Cancellation',
        'tags' => 'kerala kumarakom alleppey lake backwaters resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-UDR-05',
        'type' => 'hotel',
        'sub_type' => '5-Star Palace Stay',
        'title' => 'Taj Lake Palace, Floating Marble Jewel',
        'destination' => 'Rajasthan • Lake Pichola • Udaipur',
        'room_type' => 'Luxury Lake View Room with Royal Butler Service',
        'location' => 'Lake Pichola, Udaipur, Rajasthan',
        'badge' => '5★ Royal Palace Icon',
        'badge_class' => 'bg-amber-700 text-white',
        'price' => 28000,
        'original_price' => 36000,
        'price_unit' => '/ night',
        'rating' => 5.0,
        'reviews' => '3,150 reviews',
        'image' => 'https://images.unsplash.com/photo-1590073242678-70ee3fc28e8e?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Royal Mewari Breakfast'],
            ['icon' => 'fa-solid fa-person-swimming', 'label' => 'Jharokha Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Jiva Spa Boat'],
            ['icon' => 'fa-solid fa-bell-concierge', 'label' => '24/7 Royal Butler'],
        ],
        'perks' => 'Private Boat Transfer Included • Guaranteed Palace Lake View',
        'tags' => 'udaipur rajasthan taj lake palace heritage royal 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-KSH-06',
        'type' => 'hotel',
        'sub_type' => '5-Star Alpine Resort',
        'title' => 'The Khyber Himalayan Resort & Spa, Gulmarg',
        'destination' => 'Kashmir • Gulmarg • Pir Panjal Range',
        'room_type' => 'Premier Pine View Room with Central Glass Hearth',
        'location' => 'Gulmarg, Jammu & Kashmir',
        'badge' => '5★ Snow Ski Sanctuary',
        'badge_class' => 'bg-sky-600 text-white',
        'price' => 16500,
        'original_price' => 22000,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '1,740 reviews',
        'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Kashmiri Buffet'],
            ['icon' => 'fa-solid fa-temperature-arrow-up', 'label' => 'Heated Indoor Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => "L'Occitane Spa"],
            ['icon' => 'fa-solid fa-person-skiing', 'label' => 'Ski In / Ski Out'],
        ],
        'perks' => 'Gondola Station Distance: 3 Mins • Free Cancellation',
        'tags' => 'kashmir gulmarg khyber snow hills resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-DXB-07',
        'type' => 'hotel',
        'sub_type' => '5-Star Global Landmark',
        'title' => 'Atlantis, The Palm Island Luxury Resort',
        'destination' => 'Dubai, UAE • Palm Jumeirah Crescent',
        'room_type' => 'Ocean King Room with Panoramic Arabian Gulf View',
        'location' => 'Crescent Road, Palm Jumeirah, Dubai',
        'badge' => '5★ World Icon',
        'badge_class' => 'bg-brand-600 text-white',
        'price' => 32000,
        'original_price' => 42000,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '4,800 reviews',
        'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Gourmet Breakfast'],
            ['icon' => 'fa-solid fa-water-ladder', 'label' => 'Aquaventure Access'],
            ['icon' => 'fa-solid fa-fish', 'label' => 'The Lost Chambers'],
            ['icon' => 'fa-solid fa-umbrella-beach', 'label' => 'Private Beach'],
        ],
        'perks' => 'Free Unlimited Waterpark Tickets Included • Instant Booking',
        'tags' => 'dubai uae atlantis palm jumeirah international resort 5star breakfast pool spa wifi cancellation',
    ],
    [
        'id' => 'HTL-BALI-08',
        'type' => 'hotel',
        'sub_type' => '5-Star Rainforest Retreat',
        'title' => 'The Kayon Jungle Resort & Valley Sanctuary',
        'destination' => 'Bali, Indonesia • Ubud Rainforest',
        'room_type' => 'Jungle Valley Suite with Multi-Tier Pool Vista',
        'location' => 'Bresela, Payangan, Ubud, Bali',
        'badge' => '5★ Tropical Sanctuary',
        'badge_class' => 'bg-emerald-600 text-white',
        'price' => 18500,
        'original_price' => 24000,
        'price_unit' => '/ night',
        'rating' => 4.9,
        'reviews' => '1,920 reviews',
        'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80',
        'amenities' => [
            ['icon' => 'fa-solid fa-utensils', 'label' => 'Floating Breakfast'],
            ['icon' => 'fa-solid fa-water-ladder', 'label' => '3-Tier Valley Pool'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Serapung Spa'],
            ['icon' => 'fa-solid fa-spa', 'label' => 'Daily Yoga Session'],
        ],
        'perks' => 'Afternoon Herbal Tea Included • Free Date Reschedule',
        'tags' => 'bali ubud indonesia kayon rainforest luxury resort 5star breakfast pool spa wifi cancellation',
    ],
];

// Dynamically fetch and merge active Packages & Hotels from MySQL Database
if (function_exists('getDBConnection')) {
    $pdoSearch = getDBConnection();
} else {
    require_once __DIR__ . '/config/db.php';
    $pdoSearch = getDBConnection();
}

if ($pdoSearch) {
    try {
        // 1. Fetch Dynamic Database Packages
        $dbPackages = $pdoSearch->query("SELECT * FROM `packages` WHERE `status` = 'active' ORDER BY `id` DESC")->fetchAll();
        $dynamicSearchPackages = [];
        foreach ($dbPackages as $p) {
            $inclusions = [];
            if (!empty($p['inclusions'])) {
                $decInc = json_decode($p['inclusions'], true);
                if (is_array($decInc)) {
                    foreach ($decInc as $item) {
                        $itemStr = is_string($item) ? $item : ($item['label'] ?? '');
                        $icon = 'fa-solid fa-circle-check';
                        $lower = strtolower($itemStr);
                        if (str_contains($lower, 'hotel') || str_contains($lower, 'stay')) $icon = 'fa-solid fa-hotel';
                        elseif (str_contains($lower, 'breakfast') || str_contains($lower, 'meal') || str_contains($lower, 'dinner')) $icon = 'fa-solid fa-utensils';
                        elseif (str_contains($lower, 'cab') || str_contains($lower, 'car') || str_contains($lower, 'transfer')) $icon = 'fa-solid fa-car';
                        elseif (str_contains($lower, 'boat') || str_contains($lower, 'cruise') || str_contains($lower, 'houseboat')) $icon = 'fa-solid fa-sailboat';
                        elseif (str_contains($lower, 'spa')) $icon = 'fa-solid fa-spa';
                        elseif (str_contains($lower, 'sightseeing')) $icon = 'fa-solid fa-camera';
                        $inclusions[] = ['icon' => $icon, 'label' => mb_strimwidth($itemStr, 0, 18, '..')];
                        if (count($inclusions) >= 4) break;
                    }
                }
            }
            if (empty($inclusions)) {
                $inclusions = [
                    ['icon' => 'fa-solid fa-hotel', 'label' => '4★ Verified Hotel'],
                    ['icon' => 'fa-solid fa-utensils', 'label' => 'Breakfast Included'],
                    ['icon' => 'fa-solid fa-car', 'label' => 'Private AC Cab'],
                    ['icon' => 'fa-solid fa-camera', 'label' => 'Sightseeing Pass']
                ];
            }

            $img = !empty($p['featured_image']) ? $p['featured_image'] : 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80';
            $destStr = $p['location'] . (!empty($p['state_country']) ? ' • ' . $p['state_country'] : '');
            $tagsStr = strtolower($p['title'] . ' ' . $p['location'] . ' ' . $p['state_country'] . ' ' . $p['category'] . ' ' . $p['circuit_type'] . ' package tour cancellation cab');

            $dynamicSearchPackages[] = [
                'id' => 'PKG-DB-' . $p['id'],
                'type' => 'package',
                'sub_type' => ucfirst($p['category']) . ' Tour',
                'title' => $p['title'],
                'destination' => $destStr,
                'route' => $p['location'],
                'duration' => $p['duration_text'] ?: ($p['duration_nights'] . 'N / ' . $p['duration_days'] . 'D'),
                'badge' => $p['badge'] ?: 'Bestseller',
                'badge_class' => 'bg-brand-600 text-white',
                'price' => (float)$p['price'],
                'original_price' => (float)($p['original_price'] ?: ($p['price'] * 1.25)),
                'rating' => (float)($p['rating'] ?: 4.9),
                'reviews' => ($p['reviews_count'] ?: 250) . ' reviews',
                'image' => $img,
                'inclusions' => $inclusions,
                'perks' => '100% Customizable • Verified 4★ Partner Hotels • Instant Confirmation',
                'tags' => $tagsStr
            ];
        }

        // 2. Fetch Dynamic Database Hotels
        $dbHotels = $pdoSearch->query("SELECT * FROM `hotels` WHERE `status` = 'active' ORDER BY `id` DESC")->fetchAll();
        $dynamicSearchHotels = [];
        foreach ($dbHotels as $h) {
            $amenities = [];
            if (!empty($h['amenities'])) {
                $decAm = json_decode($h['amenities'], true);
                if (is_array($decAm)) {
                    foreach ($decAm as $da) {
                        $name = is_string($da) ? $da : ($da['name'] ?? '');
                        $icon = is_array($da) ? ($da['icon'] ?? 'fa-solid fa-circle-check') : 'fa-solid fa-circle-check';
                        $amenities[] = ['icon' => $icon, 'label' => mb_strimwidth($name, 0, 18, '..')];
                        if (count($amenities) >= 4) break;
                    }
                }
            }
            if (empty($amenities)) {
                $amenities = [
                    ['icon' => 'fa-solid fa-utensils', 'label' => 'Free Breakfast'],
                    ['icon' => 'fa-solid fa-wifi', 'label' => 'Free Wi-Fi'],
                    ['icon' => 'fa-solid fa-water-ladder', 'label' => 'Swimming Pool'],
                    ['icon' => 'fa-solid fa-spa', 'label' => 'Spa & Wellness']
                ];
            }

            $img = !empty($h['featured_image']) ? $h['featured_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
            $destStr = (!empty($h['city']) ? $h['city'] : '') . (!empty($h['country']) ? ' • ' . $h['country'] : '');
            $tagsStr = strtolower($h['name'] . ' ' . $h['city'] . ' ' . $h['state'] . ' ' . $h['country'] . ' hotel resort ' . $h['star_rating'] . 'star pool breakfast wifi');

            $dynamicSearchHotels[] = [
                'id' => 'HTL-DB-' . $h['id'],
                'type' => 'hotel',
                'sub_type' => ($h['star_rating'] ?: 4) . '-Star ' . ($h['property_type'] ?: 'Hotel'),
                'title' => $h['name'],
                'destination' => $destStr,
                'room_type' => 'Deluxe Room with Breakfast',
                'location' => $h['address'] ?: ($h['city'] . ', ' . $h['country']),
                'badge' => $h['badge'] ?: ($h['star_rating'] . '★ Verified'),
                'badge_class' => 'bg-amber-600 text-white',
                'price' => (float)$h['starting_price'],
                'original_price' => (float)($h['original_price'] ?: ($h['starting_price'] * 1.25)),
                'price_unit' => '/ night',
                'rating' => (float)($h['star_rating'] ?: 4.8),
                'reviews' => '150+ reviews',
                'image' => $img,
                'amenities' => $amenities,
                'perks' => $h['policies'] ?: 'Free Cancellation • Pay at Hotel • Instant Confirmation',
                'tags' => $tagsStr
            ];
        }

        // Merge dynamic database items at the top
        $searchItems = array_merge($dynamicSearchPackages, $dynamicSearchHotels, $searchItems);
    } catch (Exception $e) {}
}

// Calculate category counts
$countPackages = count(array_filter($searchItems, fn($i) => $i['type'] === 'package'));
$countFlights = count(array_filter($searchItems, fn($i) => $i['type'] === 'flight'));
$countHotels = count(array_filter($searchItems, fn($i) => $i['type'] === 'hotel'));
$countAll = count($searchItems);

require_once 'components/header.php';
require_once 'components/navbar.php';
?>

<!-- ==========================================
     TOP REFINEMENT SEARCH BANNER (100% Flat, No Shadows)
=========================================== -->
<section class="w-full bg-slate-900 text-white border-b border-slate-800 relative py-8 md:py-10 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto">
        
        <!-- Breadcrumb & Tagline -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center space-x-2 text-xs text-slate-400">
                <a href="index.php" class="hover:text-white transition flex items-center space-x-1">
                    <i class="fa-solid fa-house text-[11px]"></i>
                    <span>Home</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-600"></i>
                <span class="text-teal-400 font-semibold">Search Portal</span>
            </div>

            <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-400">
                <i class="fa-solid fa-shield-halved text-brand-400 text-xs"></i>
                <span>100% Flat Pricing &bull; Zero Hidden Markups &bull; Instant Confirmation</span>
            </div>
        </div>

        <!-- Universal Search Input Bar -->
        <div class="bg-white rounded-2xl sm:rounded-full p-2 border border-slate-700 flex flex-col sm:flex-row items-center gap-2">
            <div class="w-full flex-1 flex items-center px-4 py-2 sm:py-1">
                <i class="fa-solid fa-magnifying-glass text-brand-600 text-base mr-3 shrink-0"></i>
                <input type="text" 
                       id="liveSearchInput"
                       value="<?= htmlspecialchars($initialQuery) ?>"
                       placeholder="Search any destination, hotel, airline, package (e.g. Kashmir, Goa, IndiGo, Dubai)..." 
                       class="w-full text-slate-900 placeholder-slate-400 text-xs sm:text-sm font-semibold bg-transparent focus:outline-none"
                       autocomplete="off">
                <button type="button" id="clearSearchBtn" class="text-slate-400 hover:text-slate-600 px-2 <?= empty($initialQuery) ? 'hidden' : '' ?>" title="Clear input">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Instant Search Button -->
            <button type="button" id="searchActionBtn" class="w-full sm:w-auto px-8 py-3 rounded-xl sm:rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm uppercase tracking-wider transition active:scale-95 shrink-0 flex items-center justify-center space-x-2">
                <span>Filter Results</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>

        <!-- Trending Quick Keywords Pills -->
        <div class="flex flex-wrap items-center gap-2 mt-4 text-xs">
            <span class="text-slate-400 font-medium mr-1">Trending Searches:</span>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Kashmir">Kashmir</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Goa">Goa</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Dubai">Dubai</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Himachal">Himachal</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="IndiGo">IndiGo</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Bali">Bali</button>
            <button type="button" class="quick-term-pill px-3 py-1 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 transition" data-term="Kerala">Kerala</button>
        </div>

    </div>
</section>

<!-- ==========================================
     CATEGORY SWITCHER STRIP (Sticky & Minimal)
=========================================== -->
<div class="w-full bg-white border-b border-slate-200 sticky top-20 md:top-24 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 xl:px-12">
        <div class="flex items-center justify-between overflow-x-auto no-scrollbar py-3 gap-3">
            
            <!-- Category Navigation Tabs -->
            <div class="flex items-center space-x-2 shrink-0" id="searchCategoryTabs">
                <!-- All Services -->
                <button type="button" 
                        class="cat-tab-btn px-4 py-2 rounded-full text-xs font-bold transition flex items-center space-x-2 <?= ($initialType === 'all' || empty($initialType)) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>" 
                        data-type="all">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>All Listings</span>
                    <span class="rounded-full bg-white/25 px-2 py-0.5 text-[10px] cat-count-all"><?= $countAll ?></span>
                </button>

                <!-- Tour Packages -->
                <button type="button" 
                        class="cat-tab-btn px-4 py-2 rounded-full text-xs font-bold transition flex items-center space-x-2 <?= in_array($initialType, ['package', 'packages', 'domestic', 'international']) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>" 
                        data-type="package">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Tour Packages</span>
                    <span class="rounded-full bg-white/25 px-2 py-0.5 text-[10px] cat-count-package"><?= $countPackages ?></span>
                </button>

                <!-- Flights -->
                <button type="button" 
                        class="cat-tab-btn px-4 py-2 rounded-full text-xs font-bold transition flex items-center space-x-2 <?= ($initialType === 'flight' || $initialType === 'flights') ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>" 
                        data-type="flight">
                    <i class="fa-solid fa-plane-departure text-xs"></i>
                    <span>Flights</span>
                    <span class="rounded-full bg-white/25 px-2 py-0.5 text-[10px] cat-count-flight"><?= $countFlights ?></span>
                </button>

                <!-- Hotels & Resorts -->
                <button type="button" 
                        class="cat-tab-btn px-4 py-2 rounded-full text-xs font-bold transition flex items-center space-x-2 <?= ($initialType === 'hotel' || $initialType === 'hotels') ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>" 
                        data-type="hotel">
                    <i class="fa-solid fa-hotel text-xs"></i>
                    <span>Hotels & Resorts</span>
                    <span class="rounded-full bg-white/25 px-2 py-0.5 text-[10px] cat-count-hotel"><?= $countHotels ?></span>
                </button>
            </div>

            <!-- Sort By Select Box -->
            <div class="flex items-center space-x-2 shrink-0 ml-auto">
                <span class="text-xs text-slate-500 font-semibold hidden md:inline">Sort:</span>
                <div class="relative">
                    <select id="sortBySelect" class="appearance-none bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold py-2 pl-3 pr-8 rounded-full border border-slate-200 focus:outline-none focus:border-brand-500 cursor-pointer transition">
                        <option value="recommended">Best Recommended</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Top Rated (5★ first)</option>
                    </select>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>

                <!-- Mobile Filter Drawer Toggle Button -->
                <button type="button" id="mobileFilterToggleBtn" class="lg:hidden px-3.5 py-2 rounded-full bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold flex items-center space-x-1.5">
                    <i class="fa-solid fa-sliders text-xs"></i>
                    <span>Filters</span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ==========================================
     MAIN SEARCH RESULTS & FILTER LAYOUT
=========================================== -->
<main class="w-full py-8 md:py-10 px-4 sm:px-8 xl:px-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Top Header Status Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center space-x-2">
                    <span id="resultsTypeTitle">All Travel Listings</span>
                    <span class="text-slate-400 font-normal text-sm sm:text-base">(&bull; <span id="resultsCount"><?= $countAll ?></span> available)</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5" id="resultsSubtext">
                    Showing verified tour packages, non-stop flights, and 5-star handpicked stays
                </p>
            </div>

            <!-- Active Filter Chips Container -->
            <div id="activeFilterChips" class="flex flex-wrap items-center gap-1.5">
                <!-- Dynamically populated via JS -->
            </div>
        </div>

        <!-- 2-Column Responsive Grid -->
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- ==========================================
                 LEFT COLUMN: FILTER SIDEBAR (Clean, 100% Flat)
            =========================================== -->
            <aside id="filterSidebar" class="hidden lg:block w-full lg:w-72 shrink-0 space-y-5">
                
                <!-- Filter Box 1: Category Radios -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-list-check text-brand-600"></i>
                            <span>Travel Category</span>
                        </h3>
                        <button type="button" class="reset-filter-group text-[11px] text-brand-600 hover:underline font-semibold" data-group="category">Reset</button>
                    </div>

                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-50 transition">
                            <span class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700 group-hover:text-brand-700">
                                <input type="radio" name="sideCategory" value="all" class="text-brand-600 focus:ring-brand-500 accent-teal-600" <?= ($initialType === 'all' || empty($initialType)) ? 'checked' : '' ?>>
                                <span>All Categories</span>
                            </span>
                            <span class="text-[11px] font-mono text-slate-400"><?= $countAll ?></span>
                        </label>

                        <label class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-50 transition">
                            <span class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700 group-hover:text-brand-700">
                                <input type="radio" name="sideCategory" value="package" class="text-brand-600 focus:ring-brand-500 accent-teal-600" <?= in_array($initialType, ['package', 'packages', 'domestic', 'international']) ? 'checked' : '' ?>>
                                <span>Tour Packages</span>
                            </span>
                            <span class="text-[11px] font-mono text-slate-400"><?= $countPackages ?></span>
                        </label>

                        <label class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-50 transition">
                            <span class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700 group-hover:text-brand-700">
                                <input type="radio" name="sideCategory" value="flight" class="text-brand-600 focus:ring-brand-500 accent-teal-600" <?= ($initialType === 'flight' || $initialType === 'flights') ? 'checked' : '' ?>>
                                <span>Flights</span>
                            </span>
                            <span class="text-[11px] font-mono text-slate-400"><?= $countFlights ?></span>
                        </label>

                        <label class="flex items-center justify-between cursor-pointer group p-1.5 rounded-xl hover:bg-slate-50 transition">
                            <span class="flex items-center space-x-2.5 text-xs font-semibold text-slate-700 group-hover:text-brand-700">
                                <input type="radio" name="sideCategory" value="hotel" class="text-brand-600 focus:ring-brand-500 accent-teal-600" <?= ($initialType === 'hotel' || $initialType === 'hotels') ? 'checked' : '' ?>>
                                <span>Hotels & Stays</span>
                            </span>
                            <span class="text-[11px] font-mono text-slate-400"><?= $countHotels ?></span>
                        </label>
                    </div>
                </div>

                <!-- Filter Box 2: Price Budget Ranges -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-indian-rupee-sign text-brand-600"></i>
                            <span>Price Budget</span>
                        </h3>
                        <button type="button" class="reset-filter-group text-[11px] text-brand-600 hover:underline font-semibold" data-group="price">Reset</button>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="priceRange" value="all" checked class="accent-teal-600">
                            <span>Any Budget</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="priceRange" value="under-10k" class="accent-teal-600">
                            <span>Under ₹10,000</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="priceRange" value="10k-25k" class="accent-teal-600">
                            <span>₹10,000 – ₹25,000</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="priceRange" value="25k-50k" class="accent-teal-600">
                            <span>₹25,000 – ₹50,000</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="priceRange" value="above-50k" class="accent-teal-600">
                            <span>₹50,000 &amp; Above</span>
                        </label>
                    </div>
                </div>

                <!-- Filter Box 3: Guest Rating -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-star text-amber-500"></i>
                            <span>Guest Rating</span>
                        </h3>
                        <button type="button" class="reset-filter-group text-[11px] text-brand-600 hover:underline font-semibold" data-group="rating">Reset</button>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="ratingFilter" value="all" checked class="accent-teal-600">
                            <span>All Ratings</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="ratingFilter" value="4.8" class="accent-teal-600">
                            <span class="flex items-center space-x-1">
                                <span>4.8★ &amp; above</span>
                                <span class="text-[10px] text-emerald-600 font-bold">(Exceptional)</span>
                            </span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="ratingFilter" value="4.5" class="accent-teal-600">
                            <span class="flex items-center space-x-1">
                                <span>4.5★ &amp; above</span>
                                <span class="text-[10px] text-slate-400 font-medium">(Superb)</span>
                            </span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded-xl hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="radio" name="ratingFilter" value="4.0" class="accent-teal-600">
                            <span>4.0★ &amp; above</span>
                        </label>
                    </div>
                </div>

                <!-- Filter Box 4: Inclusions & Key Perks -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-brand-600"></i>
                            <span>Key Inclusions</span>
                        </h3>
                        <button type="button" class="reset-filter-group text-[11px] text-brand-600 hover:underline font-semibold" data-group="perks">Reset</button>
                    </div>

                    <div class="space-y-2.5">
                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="breakfast" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-utensils text-slate-400 text-xs"></i>
                                <span>Free Breakfast</span>
                            </span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="cancellation" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-clock-rotate-left text-slate-400 text-xs"></i>
                                <span>Free Cancellation</span>
                            </span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="nonstop" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-plane-circle-check text-slate-400 text-xs"></i>
                                <span>Non-Stop Flights</span>
                            </span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="pool" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-person-swimming text-slate-400 text-xs"></i>
                                <span>Swimming Pool / Beach</span>
                            </span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="sightseeing" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-camera text-slate-400 text-xs"></i>
                                <span>Sightseeing Included</span>
                            </span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer p-1 rounded-lg hover:bg-slate-50 text-xs font-semibold text-slate-700">
                            <input type="checkbox" value="wifi" class="perk-checkbox rounded text-brand-600 focus:ring-brand-500 accent-teal-600">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-wifi text-slate-400 text-xs"></i>
                                <span>Free High-Speed Wi-Fi</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Reset All Filters Button -->
                <button type="button" id="resetAllFiltersBtn" class="w-full py-2.5 rounded-full bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset All Filters</span>
                </button>

                <!-- Orion Trust Assurance Capsule -->
                <div class="p-4 rounded-2xl bg-teal-50/60 border border-brand-200/70 text-brand-900 space-y-2">
                    <div class="flex items-center space-x-2 text-xs font-bold text-brand-800">
                        <i class="fa-solid fa-badge-check text-brand-600"></i>
                        <span>Orion Price Promise</span>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Found a lower comparable fare elsewhere? We'll match it and credit ₹1,000 straight to your Orion travel wallet.
                    </p>
                </div>

            </aside>

            <!-- ==========================================
                 RIGHT COLUMN: RESULTS STREAM
            =========================================== -->
            <div class="flex-1 w-full space-y-4" id="resultsFeedContainer">

                <?php foreach ($searchItems as $item): ?>
                    
                    <?php if ($item['type'] === 'package'): ?>
                        <!-- ==========================================
                             TOUR PACKAGE CARD (Lightweight Minimal)
                        =========================================== -->
                        <article class="search-result-card bg-white rounded-2xl border border-slate-200 hover:border-brand-500 transition flex flex-col md:flex-row overflow-hidden"
                                 data-id="<?= htmlspecialchars($item['id']) ?>"
                                 data-type="package"
                                 data-price="<?= $item['price'] ?>"
                                 data-rating="<?= $item['rating'] ?>"
                                 data-tags="<?= htmlspecialchars($item['tags']) ?>"
                                 data-title="<?= htmlspecialchars($item['title']) ?>">
                            
                            <!-- Thumbnail with Duration Pill -->
                            <div class="w-full md:w-64 h-48 md:h-auto shrink-0 relative overflow-hidden bg-slate-100">
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold <?= $item['badge_class'] ?>">
                                    <?= htmlspecialchars($item['badge']) ?>
                                </span>

                                <span class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-900/80 backdrop-blur-sm text-white">
                                    <i class="fa-regular fa-clock text-[10px] mr-1"></i>
                                    <?= htmlspecialchars($item['duration']) ?>
                                </span>
                            </div>

                            <!-- Content Body -->
                            <div class="flex-1 p-5 flex flex-col justify-between">
                                <div>
                                    <!-- Destination Track & Rating -->
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="inline-flex items-center space-x-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full">
                                            <i class="fa-solid fa-map-pin text-[10px]"></i>
                                            <span><?= htmlspecialchars($item['sub_type']) ?></span>
                                        </span>

                                        <div class="flex items-center space-x-1 text-xs">
                                            <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-bold border border-amber-200 text-[11px] flex items-center space-x-1">
                                                <i class="fa-solid fa-star text-[10px] text-amber-500"></i>
                                                <span><?= $item['rating'] ?></span>
                                            </span>
                                            <span class="text-[11px] text-slate-400 hidden sm:inline">(<?= $item['reviews'] ?>)</span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug hover:text-brand-700 transition">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </h3>

                                    <!-- Route Circuit -->
                                    <p class="text-xs text-slate-500 mt-1 flex items-center space-x-1.5 font-medium">
                                        <i class="fa-solid fa-route text-slate-400 text-xs"></i>
                                        <span><?= htmlspecialchars($item['route']) ?></span>
                                    </p>

                                    <!-- Inclusions Pill Strip -->
                                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                                        <?php foreach ($item['inclusions'] as $inc): ?>
                                            <span class="inline-flex items-center space-x-1 text-[11px] font-semibold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full border border-slate-200/60">
                                                <i class="<?= $inc['icon'] ?> text-brand-600 text-[10px]"></i>
                                                <span><?= htmlspecialchars($inc['label']) ?></span>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Bottom Perks -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                    <span class="flex items-center space-x-1.5 text-emerald-600 font-semibold">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span><?= htmlspecialchars($item['perks']) ?></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right CTA & Price Strip -->
                            <div class="md:w-56 shrink-0 p-5 bg-slate-50/70 border-t md:border-t-0 md:border-l border-slate-200 flex md:flex-col justify-between items-center md:items-end text-left md:text-right">
                                <div>
                                    <span class="text-[11px] text-slate-400 block line-through">₹<?= number_format($item['original_price']) ?></span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">
                                        ₹<?= number_format($item['price']) ?>
                                    </div>
                                    <span class="text-[10px] text-slate-500 block mt-1">per person &bull; incl. taxes</span>
                                </div>

                                <div class="space-y-1.5 shrink-0 md:w-full text-right">
                                    <a href="package-details.php?id=<?= urlencode($item['id']) ?>" 
                                       class="w-full px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-1.5">
                                        <span>View Details</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                    <button type="button" 
                                            class="open-inquire-btn w-full px-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition flex items-center justify-center space-x-1"
                                            data-title="<?= htmlspecialchars($item['title']) ?>"
                                            data-category="Tour Package"
                                            data-price="₹<?= number_format($item['price']) ?>"
                                            data-duration="<?= htmlspecialchars($item['duration']) ?>">
                                        <i class="fa-solid fa-paper-plane text-[9px] text-brand-600"></i>
                                        <span>Quick Inquiry</span>
                                    </button>
                                </div>
                            </div>

                        </article>

                    <?php elseif ($item['type'] === 'flight'): ?>
                        <!-- ==========================================
                             FLIGHT CARD (Lightweight Minimal)
                        =========================================== -->
                        <article class="search-result-card bg-white rounded-2xl border border-slate-200 hover:border-brand-500 transition flex flex-col md:flex-row overflow-hidden"
                                 data-id="<?= htmlspecialchars($item['id']) ?>"
                                 data-type="flight"
                                 data-price="<?= $item['price'] ?>"
                                 data-rating="<?= $item['rating'] ?>"
                                 data-tags="<?= htmlspecialchars($item['tags']) ?>"
                                 data-title="<?= htmlspecialchars($item['title']) ?>">

                            <!-- Airline Identity Column -->
                            <div class="w-full md:w-48 p-5 shrink-0 bg-slate-50/50 border-b md:border-b-0 md:border-r border-slate-200 flex md:flex-col items-center justify-between md:justify-center text-center">
                                <div class="flex md:flex-col items-center space-x-3 md:space-x-0 md:space-y-2">
                                    <span class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-black text-sm">
                                        <i class="fa-solid fa-plane-departure text-sm"></i>
                                    </span>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($item['airline']) ?></h4>
                                        <span class="text-[11px] font-mono text-slate-500 block"><?= htmlspecialchars($item['airline_code']) ?></span>
                                    </div>
                                </div>

                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $item['badge_class'] ?> mt-0 md:mt-3">
                                    <?= htmlspecialchars($item['badge']) ?>
                                </span>
                            </div>

                            <!-- Flight Timeline & Details -->
                            <div class="flex-1 p-5 flex flex-col justify-between">
                                <div>
                                    <!-- Route & Times Strip -->
                                    <div class="flex items-center justify-between gap-4 py-2">
                                        <!-- Departure -->
                                        <div class="text-left">
                                            <span class="text-xl sm:text-2xl font-black text-slate-900 block"><?= htmlspecialchars($item['from_time']) ?></span>
                                            <span class="text-xs font-bold text-brand-700"><?= htmlspecialchars($item['from_code']) ?></span>
                                            <span class="text-[11px] text-slate-500 block"><?= htmlspecialchars($item['from_city']) ?></span>
                                        </div>

                                        <!-- Duration & Direct Bar -->
                                        <div class="flex-1 max-w-xs flex flex-col items-center px-4">
                                            <span class="text-[11px] font-bold text-slate-500 mb-1"><?= htmlspecialchars($item['duration']) ?></span>
                                            <div class="w-full flex items-center">
                                                <div class="h-0.5 bg-slate-300 flex-1"></div>
                                                <i class="fa-solid fa-plane text-brand-600 text-xs mx-2"></i>
                                                <div class="h-0.5 bg-slate-300 flex-1"></div>
                                            </div>
                                            <span class="text-[10px] font-semibold text-emerald-600 mt-1"><?= htmlspecialchars($item['stops']) ?></span>
                                        </div>

                                        <!-- Arrival -->
                                        <div class="text-right">
                                            <span class="text-xl sm:text-2xl font-black text-slate-900 block"><?= htmlspecialchars($item['to_time']) ?></span>
                                            <span class="text-xs font-bold text-brand-700"><?= htmlspecialchars($item['to_code']) ?></span>
                                            <span class="text-[11px] text-slate-500 block"><?= htmlspecialchars($item['to_city']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Flight Baggage & Amenities Strip -->
                                    <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-slate-100">
                                        <span class="inline-flex items-center space-x-1.5 text-[11px] font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">
                                            <i class="fa-solid fa-suitcase-rolling text-slate-500 text-[10px]"></i>
                                            <span><?= htmlspecialchars($item['baggage']) ?></span>
                                        </span>
                                        <span class="inline-flex items-center space-x-1.5 text-[11px] font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">
                                            <i class="fa-solid fa-couch text-slate-500 text-[10px]"></i>
                                            <span><?= htmlspecialchars($item['airline_class']) ?></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Perks Note -->
                                <div class="mt-3 flex items-center justify-between text-[11px] text-slate-500">
                                    <span class="text-emerald-600 font-semibold flex items-center space-x-1">
                                        <i class="fa-solid fa-shield-check text-[10px]"></i>
                                        <span><?= htmlspecialchars($item['perks']) ?></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right CTA & Price Strip -->
                            <div class="md:w-56 shrink-0 p-5 bg-slate-50/70 border-t md:border-t-0 md:border-l border-slate-200 flex md:flex-col justify-between items-center md:items-end text-left md:text-right">
                                <div>
                                    <span class="text-[11px] text-slate-400 block line-through">₹<?= number_format($item['original_price']) ?></span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">
                                        ₹<?= number_format($item['price']) ?>
                                    </div>
                                    <span class="text-[10px] text-slate-500 block mt-1">per traveler &bull; all fees incl.</span>
                                </div>

                                <div class="space-y-1.5 shrink-0 md:w-full text-right">
                                    <button type="button" 
                                            class="open-inquire-btn w-full px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-1.5"
                                            data-title="<?= htmlspecialchars($item['title']) ?>"
                                            data-category="Flight Ticket"
                                            data-price="₹<?= number_format($item['price']) ?>"
                                            data-duration="<?= htmlspecialchars($item['duration']) ?>">
                                        <span>Book Flight</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </button>
                                </div>
                            </div>

                        </article>

                    <?php elseif ($item['type'] === 'hotel'): ?>
                        <!-- ==========================================
                             HOTEL & RESORT CARD (Lightweight Minimal)
                        =========================================== -->
                        <article class="search-result-card bg-white rounded-2xl border border-slate-200 hover:border-brand-500 transition flex flex-col md:flex-row overflow-hidden"
                                 data-id="<?= htmlspecialchars($item['id']) ?>"
                                 data-type="hotel"
                                 data-price="<?= $item['price'] ?>"
                                 data-rating="<?= $item['rating'] ?>"
                                 data-tags="<?= htmlspecialchars($item['tags']) ?>"
                                 data-title="<?= htmlspecialchars($item['title']) ?>">

                            <!-- Resort Photo with Star Pill -->
                            <div class="w-full md:w-64 h-48 md:h-auto shrink-0 relative overflow-hidden bg-slate-100">
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">

                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold <?= $item['badge_class'] ?>">
                                    <?= htmlspecialchars($item['badge']) ?>
                                </span>
                            </div>

                            <!-- Resort Content Body -->
                            <div class="flex-1 p-5 flex flex-col justify-between">
                                <div>
                                    <!-- Location & Rating -->
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="inline-flex items-center space-x-1 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full">
                                            <i class="fa-solid fa-location-dot text-[10px]"></i>
                                            <span><?= htmlspecialchars($item['location']) ?></span>
                                        </span>

                                        <div class="flex items-center space-x-1 text-xs">
                                            <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-bold border border-amber-200 text-[11px] flex items-center space-x-1">
                                                <i class="fa-solid fa-star text-[10px] text-amber-500"></i>
                                                <span><?= $item['rating'] ?></span>
                                            </span>
                                            <span class="text-[11px] text-slate-400 hidden sm:inline">(<?= $item['reviews'] ?>)</span>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug hover:text-brand-700 transition">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </h3>

                                    <!-- Room Type -->
                                    <p class="text-xs text-slate-500 mt-1 flex items-center space-x-1.5 font-medium">
                                        <i class="fa-solid fa-bed text-slate-400 text-xs"></i>
                                        <span><?= htmlspecialchars($item['room_type']) ?></span>
                                    </p>

                                    <!-- Amenities Pills -->
                                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                                        <?php foreach ($item['amenities'] as $am): ?>
                                            <span class="inline-flex items-center space-x-1 text-[11px] font-semibold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full border border-slate-200/60">
                                                <i class="<?= $am['icon'] ?> text-brand-600 text-[10px]"></i>
                                                <span><?= htmlspecialchars($am['label']) ?></span>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Perks Note -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                    <span class="text-emerald-600 font-semibold flex items-center space-x-1">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span><?= htmlspecialchars($item['perks']) ?></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Right CTA & Price Strip -->
                            <div class="md:w-56 shrink-0 p-5 bg-slate-50/70 border-t md:border-t-0 md:border-l border-slate-200 flex md:flex-col justify-between items-center md:items-end text-left md:text-right">
                                <div>
                                    <span class="text-[11px] text-slate-400 block line-through">₹<?= number_format($item['original_price']) ?></span>
                                    <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">
                                        ₹<?= number_format($item['price']) ?>
                                    </div>
                                    <span class="text-[10px] text-slate-500 block mt-1"><?= $item['price_unit'] ?> &bull; plus taxes</span>
                                </div>

                                <div class="space-y-1.5 shrink-0 md:w-full text-right">
                                    <a href="hotel-details.php?id=<?= urlencode($item['id']) ?>" 
                                       class="w-full px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-1.5">
                                        <span>View Rooms</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                    <button type="button" 
                                            class="open-inquire-btn w-full px-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition flex items-center justify-center space-x-1"
                                            data-title="<?= htmlspecialchars($item['title']) ?>"
                                            data-category="Hotel & Resort"
                                            data-price="₹<?= number_format($item['price']) ?>"
                                            data-duration="<?= htmlspecialchars($item['room_type']) ?>">
                                        <i class="fa-solid fa-paper-plane text-[9px] text-brand-600"></i>
                                        <span>Quick Inquiry</span>
                                    </button>
                                </div>
                            </div>

                        </article>

                    <?php endif; ?>

                <?php endforeach; ?>

                <!-- ==========================================
                     EMPTY STATE (When no results match filters)
                =========================================== -->
                <div id="emptyResultsState" class="hidden bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center mx-auto text-2xl">
                        <i class="fa-solid fa-magnifying-glass-location"></i>
                    </div>

                    <div class="max-w-md mx-auto space-y-1">
                        <h3 class="text-lg font-black text-slate-900">No Travel Listings Found</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We couldn't find any packages, flights, or hotels matching your current keywords or filter criteria.
                        </p>
                    </div>

                    <div class="pt-2">
                        <button type="button" id="emptyStateResetBtn" class="px-6 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                            Reset All Filters &amp; Show Everything
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>

<!-- ==========================================
     QUICK INQUIRY / BOOKING MODAL (100% Flat)
=========================================== -->
<div id="quickInquireModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 space-y-5 animate-in fade-in zoom-in duration-200">
        
        <!-- Modal Close Button -->
        <button type="button" id="closeInquireModalBtn" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition focus:outline-none">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <!-- Header -->
        <div class="space-y-1.5 pr-8">
            <span id="modalCategoryBadge" class="inline-flex items-center space-x-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-3 py-0.5 rounded-full border border-brand-200">
                <i class="fa-solid fa-tag text-[10px]"></i>
                <span id="modalCategoryText">Tour Package</span>
            </span>
            <h3 id="modalTitle" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">
                Travel Package Inquiry
            </h3>
            <p id="modalDuration" class="text-xs text-slate-500 font-medium">
                Best Guaranteed Fare &bull; Fast-Track Concierge Booking
            </p>
        </div>

        <!-- Price Pill Strip -->
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-600">Starting Price</span>
            <span id="modalPrice" class="text-xl font-black text-brand-700 font-mono">₹17,999</span>
        </div>

        <!-- Booking & Inquiry Form -->
        <form id="inquiryForm" class="space-y-3.5" onsubmit="event.preventDefault(); submitInquiryForm();">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name</label>
                <div class="relative">
                    <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="inquireName" required placeholder="e.g. Rahul Sharma" class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="tel" id="inquirePhone" required placeholder="+91 98765 43210" class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Travel Date</label>
                    <div class="relative">
                        <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="date" id="inquireDate" class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium text-slate-700">
                    </div>
                </div>
            </div>

            <!-- Submit CTA Buttons -->
            <div class="pt-2 space-y-2">
                <button type="submit" class="w-full py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Confirm Booking Inquiry</span>
                </button>

                <a id="whatsappConciergeLink" href="https://wa.me/919876543210" target="_blank" class="w-full py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center space-x-2">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Chat Directly on WhatsApp</span>
                </a>
            </div>
        </form>

    </div>
</div>

<!-- ==========================================
     MOBILE FILTER DRAWER (Collapsible on Mobile)
=========================================== -->
<div id="mobileFilterDrawer" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex justify-end">
    <div class="w-full max-w-xs bg-white h-full overflow-y-auto p-6 space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h3 class="text-sm font-black text-slate-900 uppercase">Filters</h3>
            <button type="button" id="closeMobileFilterBtn" class="p-2 text-slate-500 hover:text-slate-900">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div id="mobileFilterContentContainer" class="space-y-4">
            <!-- Filter items mirrored from sidebar via JS or clone -->
            <p class="text-xs text-slate-500">Refine by price, stars, and inclusions.</p>
        </div>

        <button type="button" id="applyMobileFiltersBtn" class="w-full py-3 rounded-full bg-brand-600 text-white font-bold text-xs uppercase tracking-wider">
            Apply Filters
        </button>
    </div>
</div>

<!-- ==========================================
     CLIENT-SIDE REALTIME FILTERING ENGINE
=========================================== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const searchInput = document.getElementById('liveSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const searchActionBtn = document.getElementById('searchActionBtn');
    const categoryTabs = document.querySelectorAll('.cat-tab-btn');
    const sideCategoryRadios = document.querySelectorAll('input[name="sideCategory"]');
    const priceRadios = document.querySelectorAll('input[name="priceRange"]');
    const ratingRadios = document.querySelectorAll('input[name="ratingFilter"]');
    const perkCheckboxes = document.querySelectorAll('.perk-checkbox');
    const sortBySelect = document.getElementById('sortBySelect');
    const cards = document.querySelectorAll('.search-result-card');
    const emptyState = document.getElementById('emptyResultsState');
    const resultsCountEl = document.getElementById('resultsCount');
    const resultsTypeTitleEl = document.getElementById('resultsTypeTitle');
    const resultsSubtextEl = document.getElementById('resultsSubtext');
    const resetAllBtn = document.getElementById('resetAllFiltersBtn');
    const emptyStateResetBtn = document.getElementById('emptyStateResetBtn');
    const activeFilterChipsContainer = document.getElementById('activeFilterChips');

    // Quick trending pills
    document.querySelectorAll('.quick-term-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            const term = pill.getAttribute('data-term');
            if (searchInput) {
                searchInput.value = term;
                if (clearSearchBtn) clearSearchBtn.classList.remove('hidden');
                runFilters();
            }
        });
    });

    // Clear search button
    if (clearSearchBtn && searchInput) {
        clearSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            runFilters();
            searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            if (clearSearchBtn) {
                if (searchInput.value.trim().length > 0) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
            }
            runFilters();
        });
    }

    if (searchActionBtn) {
        searchActionBtn.addEventListener('click', runFilters);
    }

    // Top Category Navigation Tabs Click
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const type = tab.getAttribute('data-type');
            setCategory(type);
        });
    });

    // Sidebar Category Radio Change
    sideCategoryRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            setCategory(radio.value);
        });
    });

    function setCategory(type) {
        // Normalize
        if (type === 'packages' || type === 'domestic' || type === 'international') type = 'package';
        if (type === 'flights') type = 'flight';
        if (type === 'hotels') type = 'hotel';

        // Update tabs styling
        categoryTabs.forEach(t => {
            const tType = t.getAttribute('data-type');
            if (tType === type) {
                t.classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
                t.classList.add('bg-brand-600', 'text-white');
            } else {
                t.classList.remove('bg-brand-600', 'text-white');
                t.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
            }
        });

        // Update sidebar radio
        sideCategoryRadios.forEach(r => {
            r.checked = (r.value === type);
        });

        runFilters();
    }

    // Filter Listeners
    priceRadios.forEach(r => r.addEventListener('change', runFilters));
    ratingRadios.forEach(r => r.addEventListener('change', runFilters));
    perkCheckboxes.forEach(cb => cb.addEventListener('change', runFilters));
    if (sortBySelect) sortBySelect.addEventListener('change', runFilters);

    // Group Resets
    document.querySelectorAll('.reset-filter-group').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.getAttribute('data-group');
            if (group === 'category') {
                setCategory('all');
            } else if (group === 'price') {
                const defaultPrice = document.querySelector('input[name="priceRange"][value="all"]');
                if (defaultPrice) defaultPrice.checked = true;
                runFilters();
            } else if (group === 'rating') {
                const defaultRating = document.querySelector('input[name="ratingFilter"][value="all"]');
                if (defaultRating) defaultRating.checked = true;
                runFilters();
            } else if (group === 'perks') {
                perkCheckboxes.forEach(cb => cb.checked = false);
                runFilters();
            }
        });
    });

    // Reset All Buttons
    function resetAllFilters() {
        if (searchInput) searchInput.value = '';
        if (clearSearchBtn) clearSearchBtn.classList.add('hidden');
        setCategory('all');
        const defaultPrice = document.querySelector('input[name="priceRange"][value="all"]');
        if (defaultPrice) defaultPrice.checked = true;
        const defaultRating = document.querySelector('input[name="ratingFilter"][value="all"]');
        if (defaultRating) defaultRating.checked = true;
        perkCheckboxes.forEach(cb => cb.checked = false);
        if (sortBySelect) sortBySelect.value = 'recommended';
        runFilters();
    }

    if (resetAllBtn) resetAllBtn.addEventListener('click', resetAllFilters);
    if (emptyStateResetBtn) emptyStateResetBtn.addEventListener('click', resetAllFilters);

    // Master Filter & Sort Execution Engine
    function runFilters() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const activeCatRadio = document.querySelector('input[name="sideCategory"]:checked');
        const cat = activeCatRadio ? activeCatRadio.value : 'all';
        const activePriceRadio = document.querySelector('input[name="priceRange"]:checked');
        const priceRange = activePriceRadio ? activePriceRadio.value : 'all';
        const activeRatingRadio = document.querySelector('input[name="ratingFilter"]:checked');
        const minRating = activeRatingRadio ? parseFloat(activeRatingRadio.value) || 0 : 0;

        const checkedPerks = [];
        perkCheckboxes.forEach(cb => {
            if (cb.checked) checkedPerks.push(cb.value.toLowerCase());
        });

        let visibleCount = 0;
        const visibleCardsArray = [];

        cards.forEach(card => {
            const cardType = card.getAttribute('data-type');
            const cardPrice = parseFloat(card.getAttribute('data-price')) || 0;
            const cardRating = parseFloat(card.getAttribute('data-rating')) || 0;
            const cardTags = (card.getAttribute('data-tags') || '').toLowerCase();
            const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();

            // 1. Category Check
            let matchCat = (cat === 'all' || cardType === cat);

            // 2. Query Text Check
            let matchQuery = true;
            if (query.length > 0) {
                matchQuery = cardTitle.includes(query) || cardTags.includes(query);
            }

            // 3. Price Budget Check
            let matchPrice = true;
            if (priceRange === 'under-10k') {
                matchPrice = cardPrice < 10000;
            } else if (priceRange === '10k-25k') {
                matchPrice = cardPrice >= 10000 && cardPrice <= 25000;
            } else if (priceRange === '25k-50k') {
                matchPrice = cardPrice > 25000 && cardPrice <= 50000;
            } else if (priceRange === 'above-50k') {
                matchPrice = cardPrice > 50000;
            }

            // 4. Rating Check
            let matchRating = true;
            if (minRating > 0) {
                matchRating = cardRating >= minRating;
            }

            // 5. Inclusions Check
            let matchPerks = true;
            if (checkedPerks.length > 0) {
                for (let perk of checkedPerks) {
                    if (!cardTags.includes(perk)) {
                        matchPerks = false;
                        break;
                    }
                }
            }

            // Final Decision
            if (matchCat && matchQuery && matchPrice && matchRating && matchPerks) {
                card.style.display = '';
                visibleCount++;
                visibleCardsArray.push(card);
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting
        const sortVal = sortBySelect ? sortBySelect.value : 'recommended';
        if (sortVal !== 'recommended') {
            const container = document.getElementById('resultsFeedContainer');
            visibleCardsArray.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price')) || 0;
                const priceB = parseFloat(b.getAttribute('data-price')) || 0;
                const ratingA = parseFloat(a.getAttribute('data-rating')) || 0;
                const ratingB = parseFloat(b.getAttribute('data-rating')) || 0;

                if (sortVal === 'price-low') return priceA - priceB;
                if (sortVal === 'price-high') return priceB - priceA;
                if (sortVal === 'rating') return ratingB - ratingA;
                return 0;
            });

            visibleCardsArray.forEach(c => container.appendChild(c));
        }

        // Update Results Count & Header Text
        if (resultsCountEl) resultsCountEl.textContent = visibleCount;
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        // Update Title according to active category
        if (resultsTypeTitleEl) {
            if (cat === 'package') resultsTypeTitleEl.textContent = 'Tour Packages';
            else if (cat === 'flight') resultsTypeTitleEl.textContent = 'Flight Deals';
            else if (cat === 'hotel') resultsTypeTitleEl.textContent = 'Hotels & Resorts';
            else resultsTypeTitleEl.textContent = 'All Travel Listings';
        }

        // Update Subtext
        if (resultsSubtextEl) {
            if (query.length > 0) {
                resultsSubtextEl.innerHTML = `Showing filtered results for keyword <strong class="text-brand-700">"${escapeHtml(query)}"</strong>`;
            } else {
                resultsSubtextEl.textContent = 'Showing verified tour packages, non-stop flights, and 5-star handpicked stays';
            }
        }

        // Render Active Filter Chips
        renderActiveChips(query, cat, priceRange, minRating, checkedPerks);

        // Synchronize with Browser URL (without reloading)
        const newUrl = new URL(window.location);
        if (cat !== 'all') newUrl.searchParams.set('type', cat);
        else newUrl.searchParams.delete('type');

        if (query.length > 0) newUrl.searchParams.set('query', query);
        else newUrl.searchParams.delete('query');

        window.history.replaceState({}, '', newUrl);
    }

    function renderActiveChips(query, cat, priceRange, minRating, checkedPerks) {
        if (!activeFilterChipsContainer) return;
        activeFilterChipsContainer.innerHTML = '';

        if (query.length > 0) {
            createChip(`Query: ${query}`, () => {
                if (searchInput) searchInput.value = '';
                if (clearSearchBtn) clearSearchBtn.classList.add('hidden');
                runFilters();
            });
        }

        if (cat !== 'all') {
            const catLabels = { package: 'Tour Packages', flight: 'Flights', hotel: 'Hotels' };
            createChip(`Category: ${catLabels[cat] || cat}`, () => setCategory('all'));
        }

        if (priceRange !== 'all') {
            const priceLabels = {
                'under-10k': '< ₹10,000',
                '10k-25k': '₹10k–₹25k',
                '25k-50k': '₹25k–₹50k',
                'above-50k': '> ₹50,000'
            };
            createChip(`Budget: ${priceLabels[priceRange] || priceRange}`, () => {
                const defaultPrice = document.querySelector('input[name="priceRange"][value="all"]');
                if (defaultPrice) defaultPrice.checked = true;
                runFilters();
            });
        }

        if (minRating > 0) {
            createChip(`Rating: ${minRating}★+`, () => {
                const defaultRating = document.querySelector('input[name="ratingFilter"][value="all"]');
                if (defaultRating) defaultRating.checked = true;
                runFilters();
            });
        }

        checkedPerks.forEach(perk => {
            createChip(`Perk: ${perk}`, () => {
                const cb = document.querySelector(`.perk-checkbox[value="${perk}"]`);
                if (cb) cb.checked = false;
                runFilters();
            });
        });
    }

    function createChip(label, onRemove) {
        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-brand-50 text-brand-800 border border-brand-200';
        chip.innerHTML = `<span>${escapeHtml(label)}</span><i class="fa-solid fa-xmark text-[10px] cursor-pointer hover:text-brand-900 ml-1"></i>`;
        chip.querySelector('i').addEventListener('click', onRemove);
        activeFilterChipsContainer.appendChild(chip);
    }

    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' })[m]);
    }

    // ==========================================
    // Quick Inquiry Modal Trigger
    // ==========================================
    const inquireModal = document.getElementById('quickInquireModal');
    const closeInquireBtn = document.getElementById('closeInquireModalBtn');
    const modalCategoryText = document.getElementById('modalCategoryText');
    const modalTitle = document.getElementById('modalTitle');
    const modalDuration = document.getElementById('modalDuration');
    const modalPrice = document.getElementById('modalPrice');
    const whatsappLink = document.getElementById('whatsappConciergeLink');

    document.querySelectorAll('.open-inquire-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const title = btn.getAttribute('data-title') || 'Travel Itinerary';
            const cat = btn.getAttribute('data-category') || 'Package';
            const price = btn.getAttribute('data-price') || 'Contact for price';
            const duration = btn.getAttribute('data-duration') || 'Flexible departure';

            if (modalTitle) modalTitle.textContent = title;
            if (modalCategoryText) modalCategoryText.textContent = cat;
            if (modalPrice) modalPrice.textContent = price;
            if (modalDuration) modalDuration.textContent = `${duration} • Verified Partner Fare`;

            // Prepare WhatsApp direct link
            if (whatsappLink) {
                const message = encodeURIComponent(`Hi Orion Advent Concierge, I am interested in booking: "${title}" (${price}). Please share the full itinerary and booking availability.`);
                whatsappLink.href = `https://wa.me/919876543210?text=${message}`;
            }

            if (inquireModal) {
                inquireModal.classList.remove('hidden');
            }
        });
    });

    if (closeInquireBtn && inquireModal) {
        closeInquireBtn.addEventListener('click', () => {
            inquireModal.classList.add('hidden');
        });
    }

    if (inquireModal) {
        inquireModal.addEventListener('click', (e) => {
            if (e.target === inquireModal) {
                inquireModal.classList.add('hidden');
            }
        });
    }

    // Mobile Filter Drawer Toggle
    const mobileFilterToggleBtn = document.getElementById('mobileFilterToggleBtn');
    const mobileFilterDrawer = document.getElementById('mobileFilterDrawer');
    const closeMobileFilterBtn = document.getElementById('closeMobileFilterBtn');
    const applyMobileFiltersBtn = document.getElementById('applyMobileFiltersBtn');

    if (mobileFilterToggleBtn && mobileFilterDrawer) {
        mobileFilterToggleBtn.addEventListener('click', () => {
            mobileFilterDrawer.classList.remove('hidden');
        });
    }

    if (closeMobileFilterBtn && mobileFilterDrawer) {
        closeMobileFilterBtn.addEventListener('click', () => {
            mobileFilterDrawer.classList.add('hidden');
        });
    }

    if (applyMobileFiltersBtn && mobileFilterDrawer) {
        applyMobileFiltersBtn.addEventListener('click', () => {
            mobileFilterDrawer.classList.add('hidden');
            runFilters();
        });
    }

    // Initial Filter Execution on Page Load
    runFilters();
});

function submitInquiryForm() {
    const name = document.getElementById('inquireName')?.value || 'Guest';
    const phone = document.getElementById('inquirePhone')?.value || '';
    const date = document.getElementById('inquireDate')?.value || 'Flexible';
    const title = document.getElementById('modalTitle')?.textContent || 'Travel Service';

    alert(`Thank you ${name}! Your booking inquiry for "${title}" has been received. Our senior travel concierge will contact you at ${phone} within 15 minutes.`);
    
    const modal = document.getElementById('quickInquireModal');
    if (modal) modal.classList.add('hidden');
}
</script>

<?php
require_once 'components/footer.php';
?>
