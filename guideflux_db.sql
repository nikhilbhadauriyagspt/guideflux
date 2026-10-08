-- GuideFlux Database Dump
-- Generated on: 2026-10-08 04:21:28
-- Compatible with cPanel, Localhost & Cloud Servers

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','editor') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `admins` (1 rows)
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('1', 'Admin Manager', 'admin@guideflux.com', '$2y$10$KbcuH6/Wj3cJk/QFTVhaEu4zGEnoAfjfBmgtFb4TGD1ouhS0b.Zqa', 'superadmin', 'active', '2026-10-05 23:47:32', '2026-10-05 23:47:32');


-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `package_title` varchar(255) NOT NULL,
  `travel_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('confirmed','pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_code` (`booking_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `bookings` (4 rows)
INSERT INTO `bookings` (`id`, `booking_code`, `customer_name`, `customer_email`, `customer_phone`, `package_title`, `travel_date`, `amount`, `status`, `created_at`) VALUES
('1', 'GF-1001', 'Arjun Mehta', 'arjun.m@example.com', '+91 9876543210', 'Dubai Desert Safari & Burj Khalifa Experience', '2026-10-18', '1450.00', 'confirmed', '2026-10-05 23:47:32'),
('2', 'GF-1002', 'Sophia Martinez', 'sophia.m@gmail.com', '+1 555-0199', 'Bali Luxury Villa 7D/6N Tropical Getaway', '2026-11-02', '2100.00', 'pending', '2026-10-05 23:47:32'),
('3', 'GF-1003', 'David Wilson', 'david.w@corp.org', '+44 20 7946 0912', 'Swiss Alps Ski & Scenic Train Tour', '2026-12-15', '3850.00', 'confirmed', '2026-10-05 23:47:32'),
('4', 'GF-1004', 'Ananya Verma', 'ananya.v@yahoo.com', '+91 9123456780', 'Goa Beachside Sunset Resort & Watersports', '2026-10-25', '780.00', 'completed', '2026-10-05 23:47:32');


-- --------------------------------------------------------
-- Table structure for table `cruises`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cruises`;
CREATE TABLE `cruises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `cruise_line` varchar(100) NOT NULL DEFAULT 'Cordelia Cruises',
  `ship_name` varchar(100) NOT NULL DEFAULT 'The Empress',
  `departure_port` varchar(100) NOT NULL DEFAULT 'Mumbai',
  `destination_ports` varchar(255) NOT NULL DEFAULT 'Mumbai, High Seas, Goa',
  `duration_nights` int(11) NOT NULL DEFAULT 3,
  `duration_days` int(11) NOT NULL DEFAULT 4,
  `duration_text` varchar(50) DEFAULT '3 Nights / 4 Days',
  `starting_price` decimal(10,2) NOT NULL DEFAULT 19999.00,
  `original_price` decimal(10,2) DEFAULT 26999.00,
  `token_advance` decimal(10,2) DEFAULT 3000.00,
  `badge` varchar(50) DEFAULT 'Bestseller',
  `rating` decimal(2,1) DEFAULT 4.9,
  `reviews_count` int(11) DEFAULT 380,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` longtext DEFAULT NULL,
  `highlights` longtext DEFAULT NULL,
  `itinerary` longtext DEFAULT NULL,
  `cabins` longtext DEFAULT NULL,
  `inclusions` longtext DEFAULT NULL,
  `exclusions` longtext DEFAULT NULL,
  `policies` longtext DEFAULT NULL,
  `category` enum('domestic','international') DEFAULT 'domestic',
  `sailing_dates` varchar(255) DEFAULT 'Weekly Departures • Friday & Monday',
  `status` enum('active','draft') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `cruises` (3 rows)
INSERT INTO `cruises` (`id`, `title`, `slug`, `subtitle`, `cruise_line`, `ship_name`, `departure_port`, `destination_ports`, `duration_nights`, `duration_days`, `duration_text`, `starting_price`, `original_price`, `token_advance`, `badge`, `rating`, `reviews_count`, `featured_image`, `gallery`, `highlights`, `itinerary`, `cabins`, `inclusions`, `exclusions`, `policies`, `category`, `sailing_dates`, `status`, `created_at`, `updated_at`) VALUES
('1', 'Mumbai to Goa & Arabian High Seas Luxury Weekend Cruise', 'mumbai-to-goa-arabian-high-seas-cruise', 'Experience 3 nights of high-seas luxury, Broadway-style entertainment, cascading pools & oceanview dining aboard The Empress', 'Cordelia Cruises', 'The Empress', 'Mumbai', 'Mumbai, High Seas, Goa', '3', '4', '3 Nights / 4 Days', '19999.00', '26999.00', '3000.00', 'Bestseller', '4.9', '480', 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&w=900&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1548574505-5e239809ee19?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80\"]', '[{\"icon\":\"fa-solid fa-water-ladder\",\"title\":\"Top Deck Infinity Pools\",\"desc\":\"Dual swimming pools and whirlpool jacuzzis with ocean views\"},{\"icon\":\"fa-solid fa-masks-theater\",\"title\":\"Broadway Theatre & Burlesque\",\"desc\":\"Nightly live musicals, magic shows & standup comedy\"},{\"icon\":\"fa-solid fa-utensils\",\"title\":\"All-Inclusive Ocean Dining\",\"desc\":\"Lavish global buffets, Jain cuisine & Indian culinary stations\"},{\"icon\":\"fa-solid fa-dice\",\"title\":\"Casino & Nightclub Lounges\",\"desc\":\"Live DJs, rock bar, casino gaming tables & stargazing lounges\"}]', '[{\"day\":\"Day 1\",\"port\":\"Mumbai (Green Gate Pier)\",\"arrive\":\"02:00 PM Check-in\",\"depart\":\"06:30 PM Sailing\",\"title\":\"Embarkation at Mumbai Port & Sunset Welcome\",\"desc\":\"Board The Empress at Mumbai Port. Settle into your stateroom, join the safety drill, and enjoy the sunset sail-away party on the pool deck with live DJ beats. Lavish dinner buffet follows.\"},{\"day\":\"Day 2\",\"port\":\"At Sea (Arabian High Seas)\",\"arrive\":\"Cruising\",\"depart\":\"All Day\",\"title\":\"Full Day Luxury Cruising on High Seas\",\"desc\":\"Wake up to endless 360° turquoise horizons. Enjoy rock climbing, pool games, luxury spa treatments, afternoon tea, and grand gala Broadway musical at Marquee Theatre.\"},{\"day\":\"Day 3\",\"port\":\"Mormugao Port (Goa)\",\"arrive\":\"07:30 AM\",\"depart\":\"05:00 PM\",\"title\":\"Port of Call: Goa Excursion & Beach Vibes\",\"desc\":\"Dock at Mormugao Port. Disembark for South Goa beach clubs, heritage church visits, or local spice farms. Return on board before sunset for the Bollywood themed deck party.\"},{\"day\":\"Day 4\",\"port\":\"Mumbai Return\",\"arrive\":\"08:00 AM\",\"depart\":\"Disembark\",\"title\":\"Morning Arrival & Fond Memories\",\"desc\":\"Enjoy breakfast as the Mumbai skyline comes into view. Complete debarkation formalities with cherished sea voyage memories.\"}]', '[{\"name\":\"Interior Stateroom\",\"type\":\"Standard Cozy\",\"price\":19999,\"original_price\":24999,\"capacity\":\"2-3 Guests\",\"size\":\"135 sq.ft\",\"perks\":\"Queen\\/Twin Beds, Ensuite Bathroom, 24\\/7 Room Service, Interactive TV\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Ocean View Stateroom\",\"type\":\"Window Panoramic\",\"price\":24999,\"original_price\":31999,\"capacity\":\"2-4 Guests\",\"size\":\"160 sq.ft\",\"perks\":\"Picture Window Ocean Views, Sofa Bed, Mini Bar, Priority Dining\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Mini Suite with Private Balcony\",\"type\":\"Private Verandah\",\"price\":34999,\"original_price\":44999,\"capacity\":\"2-4 Guests\",\"size\":\"240 sq.ft\",\"perks\":\"Private Sea Balcony, Jacuzzi Bath Tub, Welcome Champagne, Butler Service\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Chairman\'s Presidential Suite\",\"type\":\"Ultra Luxury\",\"price\":59999,\"original_price\":79999,\"capacity\":\"2-5 Guests\",\"size\":\"450 sq.ft\",\"perks\":\"Expansive Sun Deck, Dedicated 24\\/7 Concierge, Free Premium Spirits, VIP Lounge Access\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=600&q=80\"}]', '[\"3 Nights accommodation in selected luxury cabin category\",\"All standard buffet meals (Breakfast, Lunch, Evening Snacks & Dinner) across 5 specialty restaurants\",\"Access to swimming pools, fitness centre, rock climbing wall & sports court\",\"Complimentary entry to nightly Broadway theatre shows and live musical entertainment\",\"Port handling charges, fuel surcharge & mandatory baggage transfer\"]', '[\"Casino gaming chips and casino bar privileges\",\"Alcoholic and canned packaged beverages (Beverage packages available)\",\"Spa, wellness treatments and salon services\",\"Optional Shore Excursions at Mormugao (Goa)\",\"18% Government GST\"]', '100% refund on cancellations requested 30 days prior to sailing. 50% refund between 15-29 days.', 'domestic', 'Every Friday & Monday departures', 'active', '2026-10-08 07:24:25', '2026-10-08 07:24:25'),
('2', 'Chennai to Sri Lanka (Hambantota & Trincomalee) International Cruise', 'chennai-to-sri-lanka-international-cruise', 'Sail across the Bay of Bengal to the exotic shores of Sri Lanka with wildlife safaris & beach adventures', 'Cordelia Cruises', 'The Empress', 'Chennai', 'Chennai, Hambantota, Trincomalee', '4', '5', '4 Nights / 5 Days', '28999.00', '36000.00', '4000.00', 'International Pass', '4.9', '310', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1548574505-5e239809ee19?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80\"]', '[{\"icon\":\"fa-solid fa-globe\",\"title\":\"International Island Hopping\",\"desc\":\"Visit Trincomalee, Hambantota and Jaffna with easy visa assistance\"},{\"icon\":\"fa-solid fa-umbrella-beach\",\"title\":\"Pristine Sri Lankan Beaches\",\"desc\":\"Golden sands, whale watching ports and colonial fort tours\"},{\"icon\":\"fa-solid fa-utensils\",\"title\":\"Authentic Coastal & Chettinad Dining\",\"desc\":\"Master chefs curating South Indian, Sri Lankan & international dishes\"},{\"icon\":\"fa-solid fa-spa\",\"title\":\"Full Sea View Ayurvedic Spa\",\"desc\":\"Rejuvenating ocean massages and sauna therapies at sea\"}]', '[{\"day\":\"Day 1\",\"port\":\"Chennai Port\",\"arrive\":\"01:00 PM\",\"depart\":\"05:00 PM\",\"title\":\"Embarkation at Chennai & High Seas Gala\",\"desc\":\"Check in at Chennai International Cruise Terminal. Board the ship and enjoy welcome cocktails. Sail into the Bay of Bengal.\"},{\"day\":\"Day 2\",\"port\":\"At Sea (Bay of Bengal)\",\"arrive\":\"Cruising\",\"depart\":\"All Day\",\"title\":\"High Seas Fun, Casino & Pool Deck Parties\",\"desc\":\"Unwind by the jacuzzi, watch 3D movies at sea, take Latin dance lessons, and relish the grand captain dinner.\"},{\"day\":\"Day 3\",\"port\":\"Hambantota (Sri Lanka)\",\"arrive\":\"08:00 AM\",\"depart\":\"06:00 PM\",\"title\":\"Port of Call: Yala National Park Safari\",\"desc\":\"Disembark for wildlife safaris in search of leopards and wild elephants, or visit local botanical gardens.\"},{\"day\":\"Day 4\",\"port\":\"Trincomalee (Sri Lanka)\",\"arrive\":\"08:00 AM\",\"depart\":\"05:00 PM\",\"title\":\"Port of Call: Koneswaram Temple & Pigeon Island\",\"desc\":\"Explore the dramatic cliff-top temple and turquoise snorkeling lagoons.\"},{\"day\":\"Day 5\",\"port\":\"Chennai Return\",\"arrive\":\"09:00 AM\",\"depart\":\"Disembark\",\"title\":\"Return to Chennai & Disembarkation\",\"desc\":\"Conclude your international sea journey after a scenic morning breakfast.\"}]', '[{\"name\":\"Interior Stateroom\",\"type\":\"Standard\",\"price\":28999,\"original_price\":36000,\"capacity\":\"2-3 Guests\",\"size\":\"140 sq.ft\",\"perks\":\"Air Conditioned, LED TV, Daily Housekeeping, Ensuite Shower\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Ocean View Stateroom\",\"type\":\"Panoramic Window\",\"price\":34999,\"original_price\":44000,\"capacity\":\"2-4 Guests\",\"size\":\"170 sq.ft\",\"perks\":\"Bay of Bengal Horizon Views, Tea\\/Coffee Maker, Soft Bathrobes\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Private Balcony Suite\",\"type\":\"Balcony Luxury\",\"price\":49999,\"original_price\":64000,\"capacity\":\"2-4 Guests\",\"size\":\"260 sq.ft\",\"perks\":\"Private Ocean Terrace, Premium Toiletries, Priority Boarding\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80\"}]', '[\"3 Nights accommodation in selected luxury cabin category\",\"All standard buffet meals (Breakfast, Lunch, Evening Snacks & Dinner) across 5 specialty restaurants\",\"Access to swimming pools, fitness centre, rock climbing wall & sports court\",\"Complimentary entry to nightly Broadway theatre shows and live musical entertainment\",\"Port handling charges, fuel surcharge & mandatory baggage transfer\"]', '[\"Casino gaming chips and casino bar privileges\",\"Alcoholic and canned packaged beverages (Beverage packages available)\",\"Spa, wellness treatments and salon services\",\"Optional Shore Excursions at Mormugao (Goa)\",\"18% Government GST\"]', 'Valid Passport required with minimum 6 months validity. Sri Lanka ETA visa assistance included.', 'international', 'Monthly Departures • Monday Sailing', 'active', '2026-10-08 07:24:25', '2026-10-08 07:24:25'),
('3', 'Singapore to Malaysia (Penang) & Thailand (Phuket) Royal Caribbean Mega Cruise', 'singapore-malaysia-thailand-royal-caribbean-cruise', 'Sail on Asia\'s largest quantum-class mega ship Spectrum of the Seas with robotic bars & surf simulators', 'Royal Caribbean', 'Spectrum of the Seas', 'Singapore', 'Singapore, Penang, Phuket', '4', '5', '4 Nights / 5 Days', '38999.00', '49000.00', '5000.00', 'Mega Ship', '5.0', '520', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1548574505-5e239809ee19?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80\"]', '[{\"icon\":\"fa-solid fa-ship\",\"title\":\"World-Class Mega Ship Amenities\",\"desc\":\"FlowRider surf simulator, RipCord by iFLY skydiving & bumper cars\"},{\"icon\":\"fa-solid fa-water\",\"title\":\"North Star Observation Capsule\",\"desc\":\"Ascend 300 feet above sea level in glass observation pod\"},{\"icon\":\"fa-solid fa-utensils\",\"title\":\"16 Specialty Restaurants & Bars\",\"desc\":\"Bionic robotic bar, Jamie Italian & Wonderland culinary journeys\"},{\"icon\":\"fa-solid fa-music\",\"title\":\"Two70 Multimedia Theater\",\"desc\":\"Immersive robotic screen visual spectacles and acrobatics\"}]', '[{\"day\":\"Day 1\",\"port\":\"Singapore (Marina Bay Cruise Centre)\",\"arrive\":\"12:00 PM\",\"depart\":\"04:30 PM\",\"title\":\"Depart Singapore Marina Bay\",\"desc\":\"Board Spectrum of the Seas. Explore SeaPlex sports arena and enjoy Broadway-scale theater.\"},{\"day\":\"Day 2\",\"port\":\"Penang (George Town, Malaysia)\",\"arrive\":\"02:30 PM\",\"depart\":\"09:00 PM\",\"title\":\"Port of Call: Penang Street Food & Heritage\",\"desc\":\"Dock in George Town. Taste Michelin street food, explore heritage temples and night markets.\"},{\"day\":\"Day 3\",\"port\":\"Phuket (Thailand)\",\"arrive\":\"08:00 AM\",\"depart\":\"08:00 PM\",\"title\":\"Port of Call: Phuket Islands & Emerald Waters\",\"desc\":\"Full day in Phuket for Phi Phi speedboat trips or Patong beach excursions.\"},{\"day\":\"Day 4\",\"port\":\"At Sea (Straits of Malacca)\",\"arrive\":\"Cruising\",\"depart\":\"All Day\",\"title\":\"Full Day Sea Voyage Thrills\",\"desc\":\"Enjoy FlowRider surfing, laser tag tournaments, luxury duty-free shopping and farewell gala.\"},{\"day\":\"Day 5\",\"port\":\"Singapore Return\",\"arrive\":\"07:00 AM\",\"depart\":\"Disembark\",\"title\":\"Arrival at Marina Bay Singapore\",\"desc\":\"Morning disembarkation in Singapore. Seamless luggage retrieval and city transfers.\"}]', '[{\"name\":\"Interior Stateroom with Virtual Balcony\",\"type\":\"High-Tech Cabin\",\"price\":38999,\"original_price\":49000,\"capacity\":\"2-4 Guests\",\"size\":\"166 sq.ft\",\"perks\":\"Real-time 80-inch HD screen projecting ocean views, Smart lighting\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Ocean View Stateroom\",\"type\":\"Panoramic Window\",\"price\":46999,\"original_price\":58000,\"capacity\":\"2-4 Guests\",\"size\":\"182 sq.ft\",\"perks\":\"Large Picture Window, Sitting Area with Sofa, Luxury Bedding\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Ocean Balcony Stateroom\",\"type\":\"Private Verandah\",\"price\":58999,\"original_price\":75000,\"capacity\":\"2-4 Guests\",\"size\":\"216 sq.ft\",\"perks\":\"55 sq.ft Private Ocean Verandah with lounge chairs, Evening canapes\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80\"},{\"name\":\"Ultimate Family Suite\",\"type\":\"Two-Story Penthouse\",\"price\":119999,\"original_price\":150000,\"capacity\":\"4-8 Guests\",\"size\":\"1134 sq.ft\",\"perks\":\"In-suite slide, Private 3D cinema, Air hockey, Royal Genie personal butler\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=600&q=80\"}]', '[\"3 Nights accommodation in selected luxury cabin category\",\"All standard buffet meals (Breakfast, Lunch, Evening Snacks & Dinner) across 5 specialty restaurants\",\"Access to swimming pools, fitness centre, rock climbing wall & sports court\",\"Complimentary entry to nightly Broadway theatre shows and live musical entertainment\",\"Port handling charges, fuel surcharge & mandatory baggage transfer\"]', '[\"Casino gaming chips and casino bar privileges\",\"Alcoholic and canned packaged beverages (Beverage packages available)\",\"Spa, wellness treatments and salon services\",\"Optional Shore Excursions at Mormugao (Goa)\",\"18% Government GST\"]', 'Passport valid for at least 6 months required. Singapore multiple-entry visa required.', 'international', 'Year-round weekly sailings', 'active', '2026-10-08 07:24:25', '2026-10-08 07:24:25');


-- --------------------------------------------------------
-- Table structure for table `hotel_images`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `hotel_images`;
CREATE TABLE `hotel_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(150) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `hotel_rooms`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `hotel_rooms`;
CREATE TABLE `hotel_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `room_name` varchar(150) NOT NULL,
  `room_type` varchar(100) DEFAULT 'Deluxe Room',
  `price_per_night` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `max_adults` int(11) DEFAULT 2,
  `max_children` int(11) DEFAULT 1,
  `bed_type` varchar(100) DEFAULT '1 King Bed',
  `room_size` varchar(50) DEFAULT '350 sq.ft',
  `meal_plan` varchar(100) DEFAULT 'Free Breakfast',
  `amenities` text DEFAULT NULL,
  `status` enum('available','sold_out') DEFAULT 'available',
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  CONSTRAINT `hotel_rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Table structure for table `hotels`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `star_rating` tinyint(4) DEFAULT 4,
  `property_type` varchar(100) DEFAULT 'Luxury Resort',
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `address` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `starting_price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT 0,
  `amenities` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `policies` text DEFAULT NULL,
  `checkin_time` varchar(20) DEFAULT '02:00 PM',
  `checkout_time` varchar(20) DEFAULT '11:00 AM',
  `badge` varchar(50) DEFAULT 'Popular',
  `status` enum('active','draft') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `hotels` (6 rows)
INSERT INTO `hotels` (`id`, `name`, `slug`, `star_rating`, `property_type`, `city`, `state`, `country`, `address`, `latitude`, `longitude`, `featured_image`, `starting_price`, `original_price`, `discount_percent`, `amenities`, `description`, `policies`, `checkin_time`, `checkout_time`, `badge`, `status`, `created_at`, `updated_at`) VALUES
('1', 'Taj Exotica Resort & Spa', 'taj-exotica-resort-and-spa-goa', '5', '5Γÿà Beachfront Resort', 'Goa', 'Goa', 'India', 'Benaulim Beach, South Goa, Goa 403716', '15.25890000', '73.92140000', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80', '16500.00', '22000.00', '25', '[{\"name\":\"Private Beach Access\",\"icon\":\"fa-solid fa-umbrella-beach\"},{\"name\":\"Free Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Jiva Luxury Spa\",\"icon\":\"fa-solid fa-spa\"},{\"name\":\"Infinity Pool\",\"icon\":\"fa-solid fa-water-ladder\"},{\"name\":\"Free High-Speed Wi-Fi\",\"icon\":\"fa-solid fa-wifi\"}]', 'Embrace the languid and laid-back life of South Goa at Taj Exotica Resort & Spa. Spread across 56 acres of lush greenery on Benaulim beach.', 'Free Cancellation up to 48 hours before check-in. Kids under 6 stay free.', '02:00 PM', '11:00 AM', '5Γÿà Luxury Beachfront', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('2', 'The Himalayan Heritage Castle & Spa', 'the-himalayan-castle-manali', '5', '5Γÿà Castle Resort', 'Manali', 'Himachal Pradesh', 'India', 'Hadimba Road, Manali, Himachal Pradesh 175131', '32.24750000', '77.18920000', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80', '9800.00', '13500.00', '27', '[{\"name\":\"Snow Mountain View\",\"icon\":\"fa-solid fa-mountain\"},{\"name\":\"Heated Swimming Pool\",\"icon\":\"fa-solid fa-water-ladder\"},{\"name\":\"Free Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Fireplace Lounge\",\"icon\":\"fa-solid fa-fire\"},{\"name\":\"Free Wi-Fi\",\"icon\":\"fa-solid fa-wifi\"}]', 'Victorian Gothic style luxury retreat overlooking snow-clad peaks and pine forests in Old Manali.', 'Free Cancellation up to 72 hours before check-in.', '01:00 PM', '11:00 AM', '5Γÿà Victorian Castle', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('3', 'Rambagh Palace Grand Heritage', 'rambagh-palace-jaipur', '5', '5Γÿà Royal Heritage Palace', 'Jaipur', 'Rajasthan', 'India', 'Bhawani Singh Road, Jaipur, Rajasthan 302005', '26.89780000', '75.80780000', 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=800&q=80', '22999.00', '30000.00', '23', '[{\"name\":\"Royal Butler Service\",\"icon\":\"fa-solid fa-crown\"},{\"name\":\"Peacock Courtyard\",\"icon\":\"fa-solid fa-feather\"},{\"name\":\"Jiva Grand Spa\",\"icon\":\"fa-solid fa-spa\"},{\"name\":\"Free Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Vintage Car Tour\",\"icon\":\"fa-solid fa-car-side\"}]', 'Experience the finest palace hospitality in India. Formerly the residence of the Maharaja of Jaipur.', 'Free cancellation before 7 days of arrival.', '02:00 PM', '12:00 PM', '5Γÿà Royal Palace', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('4', 'Kumarakom Lake Resort', 'kumarakom-lake-resort-kerala', '5', '5Γÿà Backwater Sanctuary', 'Kumarakom', 'Kerala', 'India', 'Vembanad Lake, Kumarakom, Kottayam, Kerala 686563', '9.61760000', '76.43000000', 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80', '14500.00', '19000.00', '24', '[{\"name\":\"Meandering Pool Villas\",\"icon\":\"fa-solid fa-water-ladder\"},{\"name\":\"Sunset Lake Cruise\",\"icon\":\"fa-solid fa-sailboat\"},{\"name\":\"Ayurmana Heritage Spa\",\"icon\":\"fa-solid fa-spa\"},{\"name\":\"Free Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Private Houseboat Jetty\",\"icon\":\"fa-solid fa-ship\"}]', 'Nestled on the serene banks of Vembanad Lake with traditional 16th-century heritage villas and a 250m meandering pool.', 'Free cancellation up to 5 days before check-in.', '02:00 PM', '11:00 AM', '5Γÿà Backwater Sanctuary', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('5', 'Atlantis The Palm Island Resort', 'atlantis-the-palm-dubai', '5', '5Γÿà Ultra Luxury Resort', 'Dubai', 'Dubai', 'United Arab Emirates', 'Crescent Rd, The Palm Jumeirah, Dubai, UAE', '25.13040000', '55.11720000', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80', '24999.00', '31000.00', '19', '[{\"name\":\"Aquaventure Waterpark Pass\",\"icon\":\"fa-solid fa-water\"},{\"name\":\"The Lost Chambers Aquarium\",\"icon\":\"fa-solid fa-fish\"},{\"name\":\"Private Golden Beach\",\"icon\":\"fa-solid fa-umbrella-beach\"},{\"name\":\"Free Luxury Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Michelin-Star Dining\",\"icon\":\"fa-solid fa-utensils\"}]', 'Iconic 5-star destination resort located on the crown of the world-famous Palm Island in Dubai.', 'Free cancellation up to 48 hours before check-in.', '03:00 PM', '12:00 PM', '5Γÿà Iconic Palm Island', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('6', 'The Oberoi Udaivilas Lake Palace', 'the-oberoi-udaivilas-udaipur', '5', '5Γÿà Grand Heritage Palace', 'Udaipur', 'Rajasthan', 'India', 'Haridas Ji Ki Magri, Lake Pichola, Udaipur, Rajasthan 313001', '24.57600000', '73.67300000', 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80', '28999.00', '36000.00', '20', '[{\"name\":\"Moat Semi-Private Pool\",\"icon\":\"fa-solid fa-water-ladder\"},{\"name\":\"Private Boat Arrival\",\"icon\":\"fa-solid fa-sailboat\"},{\"name\":\"Royal Peacock Dome\",\"icon\":\"fa-solid fa-crown\"},{\"name\":\"Free Grand Breakfast\",\"icon\":\"fa-solid fa-mug-saucer\"},{\"name\":\"Oberoi Holistic Spa\",\"icon\":\"fa-solid fa-spa\"}]', 'Located on the banks of Lake Pichola, Oberoi Udaivilas stands on the 200-year-old hunting grounds of the Maharana of Mewar.', 'Free cancellation before 7 days of arrival.', '02:00 PM', '12:00 PM', '5Γÿà World #1 Luxury Resort', 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58');


-- --------------------------------------------------------
-- Table structure for table `notifications`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta_data` longtext DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------
-- Table structure for table `packages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `location` varchar(150) NOT NULL,
  `state_country` varchar(150) DEFAULT NULL,
  `circuit_type` varchar(100) DEFAULT 'Holiday Circuit',
  `duration` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `token_advance` decimal(10,2) DEFAULT 2500.00,
  `badge` varchar(50) DEFAULT 'Bestseller',
  `rating` decimal(2,1) DEFAULT 4.9,
  `reviews_count` int(11) DEFAULT 450,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` longtext DEFAULT NULL,
  `highlights` longtext DEFAULT NULL,
  `itinerary` longtext DEFAULT NULL,
  `inclusions` longtext DEFAULT NULL,
  `exclusions` longtext DEFAULT NULL,
  `hotel_ids` longtext DEFAULT NULL,
  `custom_stays` longtext DEFAULT NULL,
  `policies` longtext DEFAULT NULL,
  `category` enum('domestic','international','adventure','luxury') DEFAULT 'international',
  `duration_nights` int(11) DEFAULT 4,
  `duration_days` int(11) DEFAULT 5,
  `duration_text` varchar(50) DEFAULT '4 Nights / 5 Days',
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','draft') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `packages` (5 rows)
INSERT INTO `packages` (`id`, `title`, `slug`, `subtitle`, `location`, `state_country`, `circuit_type`, `duration`, `price`, `original_price`, `token_advance`, `badge`, `rating`, `reviews_count`, `featured_image`, `gallery`, `highlights`, `itinerary`, `inclusions`, `exclusions`, `hotel_ids`, `custom_stays`, `policies`, `category`, `duration_nights`, `duration_days`, `duration_text`, `image`, `status`, `created_at`, `updated_at`) VALUES
('1', 'Kashmir Paradise & Dal Lake Luxury Houseboat', 'kashmir-paradise-dal-houseboat', 'Shikara rides, snow peaks of Gulmarg gondola & cedarwood houseboat stay', 'Srinagar, Gulmarg, Pahalgam', 'Kashmir, India', 'Scenic Valley & Lake Circuit', NULL, '14999.00', '19500.00', '2500.00', 'Bestseller', '4.9', '480', 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80', NULL, '[{\"icon\":\"fa-solid fa-sailboat\",\"title\":\"Dal Lake Shikara Cruise\",\"desc\":\"Sunset romantic boat ride with Kashmiri Kehwa\"},{\"icon\":\"fa-solid fa-cable-car\",\"title\":\"Gulmarg Gondola Phase 2\",\"desc\":\"Pre-booked guaranteed passes to Apharwat peak 14,000ft\"},{\"icon\":\"fa-solid fa-mountain\",\"title\":\"Betaab Valley & Aru Valley\",\"desc\":\"Pahalgam lush pine forests and pony rides\"}]', '[{\"day\":\"Day 1\",\"title\":\"Arrival Srinagar & Sunset Shikara Ride\",\"desc\":\"Meet our tour executive at Srinagar Airport. Transfer to luxury Dal Lake houseboat, freshen up, and enjoy a calming sunset Shikara ride across the floating flower markets.\",\"meals\":\"Dinner Included\",\"stay\":\"Cedar Houseboat Dal Lake\"},{\"day\":\"Day 2\",\"title\":\"Srinagar to Gulmarg Meadows & Gondola\",\"desc\":\"Drive through apple orchards to Gulmarg. Board the famous Gulmarg Gondola to reach snowy slopes of Apharwat peak.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Alpine Resort Gulmarg\"},{\"day\":\"Day 3\",\"title\":\"Gulmarg to Pahalgam Valley of Shepherds\",\"desc\":\"Scenic transfer along the Lidder River to Pahalgam. Visit saffron fields and explore Betaab Valley.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Pine View Hotel Pahalgam\"},{\"day\":\"Day 4\",\"title\":\"Pahalgam Local Sightseeing & Baisaran Valley\",\"desc\":\"Pony trek up to mini Switzerland (Baisaran) and relax by the crystal clear trout streams.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Pine View Hotel Pahalgam\"},{\"day\":\"Day 5\",\"title\":\"Pahalgam to Srinagar Mughal Gardens\",\"desc\":\"Return to Srinagar city. Visit world-heritage Mughal gardens Nishat Bagh and Shalimar Bagh followed by souvenir shopping.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"4Γÿà Srinagar City Hotel\"},{\"day\":\"Day 6\",\"title\":\"Airport Drop & Fond Memories\",\"desc\":\"Enjoy breakfast overlooking Shankaracharya hill. Transfer to Srinagar Airport with unforgettable memories.\",\"meals\":\"Breakfast Included\",\"stay\":\"Tour Concludes\"}]', '[\"5 Nights Luxury Accommodation (1N Houseboat + 4N Hotels)\",\"Daily Buffet Breakfast & Chef-Curated Kashmiri Dinners\",\"Private Dedicated AC Sedan for entire tour & airport transfers\",\"1-Hour Sunset Shikara Ride on Dal Lake Srinagar\",\"All Tolls, Parking, Fuel, Driver Allowances & State Permits\"]', '[\"Airfare to and from Srinagar Airport (SXR)\",\"Gulmarg Gondola cable car ticket Phase 2 (Optional Add-on)\",\"Personal pony rides, snow clothing rentals, or camera fees\",\"5% GST on total booking invoice\"]', '[\"6\"]', 'Heritage Cedarwood Houseboat (Dal Lake) + 4Γÿà Pine View Resort (Pahalgam)', '100% Refund if cancelled 15 days before travel date. 50% refund within 7-14 days.', 'domestic', '5', '6', '5 Nights / 6 Days', NULL, 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('2', 'Kerala Backwaters, Tea Gardens & Alleppey Houseboat', 'kerala-backwaters-tea-gardens-alleppey', 'Munnar misty tea gardens, spice plantations & private backwater cruising', 'Munnar, Thekkady, Alleppey, Cochin', 'Kerala, India', 'God\'s Own Country Backwater Trail', NULL, '12499.00', '16500.00', '2000.00', 'Honeymoon Fav', '4.9', '395', 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=800&q=80', NULL, '[{\"icon\":\"fa-solid fa-leaf\",\"title\":\"Munnar Tea Estates & Waterfalls\",\"desc\":\"Cheeyappara waterfalls and photo point misty hills\"},{\"icon\":\"fa-solid fa-ship\",\"title\":\"Alleppey Private Houseboat Cruise\",\"desc\":\"Exclusive stay with private on-board master chef\"},{\"icon\":\"fa-solid fa-spa\",\"title\":\"Ayurvedic Spice Plantations\",\"desc\":\"Guided aromatic garden tour and optional massage\"}]', '[{\"day\":\"Day 1\",\"title\":\"Cochin Arrival & Scenic Drive to Munnar\",\"desc\":\"Pickup from Cochin airport. Enjoy scenic drive through rubber plantations and Cheeyappara waterfalls.\",\"meals\":\"Dinner Included\",\"stay\":\"Hilltop Resort Munnar\"},{\"day\":\"Day 2\",\"title\":\"Munnar Tea Museum & Mattupetty Dam\",\"desc\":\"Visit Tata Tea Museum, Echo Point, and take speed boat ride at Mattupetty dam reservoir.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Hilltop Resort Munnar\"},{\"day\":\"Day 3\",\"title\":\"Munnar to Thekkady Spice Sanctuary\",\"desc\":\"Drive through cardamom hills to Thekkady. Experience Periyar Lake boat ride and martial arts show.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Spice Village Thekkady\"},{\"day\":\"Day 4\",\"title\":\"Thekkady to Alleppey Private Houseboat\",\"desc\":\"Board your traditional thatched houseboat at 12 PM. Cruise along quiet palm canals while enjoying freshly cooked Kerala Karimeen fish.\",\"meals\":\"All Meals Included\",\"stay\":\"Private Alleppey Houseboat\"},{\"day\":\"Day 5\",\"title\":\"Fort Kochi Sightseeing & Departure\",\"desc\":\"Check-out from houseboat after breakfast. Visit Chinese Fishing Nets in Fort Kochi before airport drop.\",\"meals\":\"Breakfast Included\",\"stay\":\"Departure\"}]', '[\"4 Nights Stays (2N Munnar + 1N Thekkady + 1N Alleppey Houseboat)\",\"All Meals on Houseboat (Lunch, Evening Tea\\/Snacks, Dinner, Breakfast)\",\"Private AC Chauffeur Vehicle for airport to airport transfers\",\"Spice Plantation entry tickets and Kathakali cultural show tickets\"]', '[\"Flight or Train tickets to Cochin (COK)\",\"Periyar National park boat safari tickets (direct counter)\",\"Any personal Ayurvedic massages or shopping expenses\"]', '[\"4\"]', 'Kumarakom Lake Resort + Private Air-Conditioned Deluxe Houseboat Alleppey', 'Free cancellation up to 10 days before departure.', 'domestic', '4', '5', '4 Nights / 5 Days', NULL, 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('3', 'Goa Beachfront Resort, Yacht Cruise & Watersports', 'goa-beachfront-resort-yacht-cruise', '4Γÿà Beachfront resort, Mandovi river dinner cruise & Grand Island scuba diving', 'Calangute, Baga, Candolim, South Goa', 'Goa, India', 'Coastal Sunshine Circuit', NULL, '8999.00', '12500.00', '1500.00', 'Weekend Deal', '4.8', '520', 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80', NULL, '[{\"icon\":\"fa-solid fa-umbrella-beach\",\"title\":\"Beachside Luxury Stay\",\"desc\":\"Direct access to silver sands with complimentary pool bar access\"},{\"icon\":\"fa-solid fa-music\",\"title\":\"Mandovi River DJ Cruise\",\"desc\":\"2-Hour lively evening river cruise with Goan folk dance & buffet\"},{\"icon\":\"fa-solid fa-person-swimming\",\"title\":\"Grand Island Scuba & Dolphin Safari\",\"desc\":\"Underwater video recording and equipment included\"}]', '[{\"day\":\"Day 1\",\"title\":\"Arrival Goa & Beachside Leisure\",\"desc\":\"Pickup from Goa airport. Check in to your resort and unwind at the private beach.\",\"meals\":\"Welcome Drink\",\"stay\":\"Goa Beachfront Resort\"},{\"day\":\"Day 2\",\"title\":\"North Goa Forts & Sunset DJ Cruise\",\"desc\":\"Visit Aguada Fort, Chapora Fort, and enjoy Mandovi river party cruise in the evening.\",\"meals\":\"Breakfast & Dinner\",\"stay\":\"Goa Beachfront Resort\"},{\"day\":\"Day 3\",\"title\":\"Grand Island Scuba Diving & Watersports\",\"desc\":\"Speedboat ride to Grand Island with dolphin sighting, snorkeling, scuba dive, and beach BBQ.\",\"meals\":\"Breakfast & Island Lunch\",\"stay\":\"Goa Beachfront Resort\"},{\"day\":\"Day 4\",\"title\":\"Old Goa Churches & Return Airport Drop\",\"desc\":\"Visit Basilica of Bom Jesus before convenient airport drop.\",\"meals\":\"Breakfast Included\",\"stay\":\"Tour Concludes\"}]', '[\"3 Nights 4Γÿà Beachfront Resort Accommodation\",\"Daily Breakfast & Welcome Cocktail Drinks\",\"Airport \\/ Railway Station Transfers by AC Cab\",\"Mandovi River Sunset Cruise Tickets for all guests\"]', '[\"Flight tickets to Goa (GOI \\/ GOX)\",\"Alcohol and motorized watersports extras\"]', '[\"1\"]', 'Taj Exotica Resort & Spa Goa / 4Γÿà Premium Beachside Resort', 'Free cancellation up to 48 hours prior to arrival.', 'domestic', '3', '4', '3 Nights / 4 Days', NULL, 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('4', 'Dubai Future City, Burj Khalifa & Desert Safari Experience', 'dubai-burj-khalifa-desert-safari', 'Burj Khalifa 124th floor, 4x4 red dune desert safari with BBQ, Marina yacht dinner', 'Dubai, Downtown, Marina, Palm Jumeirah', 'Dubai, UAE', 'Futuristic Metropolis & Desert', NULL, '38999.00', '49500.00', '5000.00', 'Visa on Arrival', '4.9', '610', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80', NULL, '[{\"icon\":\"fa-solid fa-building\",\"title\":\"Burj Khalifa 124th & 125th Floor\",\"desc\":\"Fast-track non-prime elevator tickets with panoramic views\"},{\"icon\":\"fa-solid fa-car-side\",\"title\":\"Premium 4x4 Red Dune Safari\",\"desc\":\"Dune bashing, camel ride, fire show, belly dance & BBQ dinner\"},{\"icon\":\"fa-solid fa-ship\",\"title\":\"Dubai Marina Luxury Yacht Cruise\",\"desc\":\"5-Star international buffet dinner cruising past illuminated towers\"}]', '[{\"day\":\"Day 1\",\"title\":\"Arrival Dubai & Marina Dhow Cruise Dinner\",\"desc\":\"Welcome at Dubai International Airport. Transfer to luxury hotel. Evening Marina dinner cruise.\",\"meals\":\"Dinner Included\",\"stay\":\"5Γÿà Dubai Marina Hotel\"},{\"day\":\"Day 2\",\"title\":\"Dubai Half-Day City Tour & Burj Khalifa\",\"desc\":\"Explore Dubai Frame, Burj Al Arab photo stop, Dubai Mall and ascend Burj Khalifa 124th floor.\",\"meals\":\"Breakfast Included\",\"stay\":\"5Γÿà Dubai Marina Hotel\"},{\"day\":\"Day 3\",\"title\":\"Desert Safari with Quad Biking & Belly Dance\",\"desc\":\"Afternoon pickup for 4x4 dune bashing in Lahbab red dunes followed by desert camp buffet BBQ.\",\"meals\":\"Breakfast & BBQ Dinner\",\"stay\":\"5Γÿà Dubai Marina Hotel\"},{\"day\":\"Day 4\",\"title\":\"Abu Dhabi City Tour & Sheikh Zayed Grand Mosque\",\"desc\":\"Full-day excursion to Abu Dhabi, visit magnificent white marble Grand Mosque and Ferrari World photo stop.\",\"meals\":\"Breakfast Included\",\"stay\":\"5Γÿà Dubai Marina Hotel\"},{\"day\":\"Day 5\",\"title\":\"Aquaventure Waterpark & Palm Jumeirah Monorail\",\"desc\":\"Day of thrill at Atlantis Aquaventure Waterpark and lost chambers aquarium.\",\"meals\":\"Breakfast Included\",\"stay\":\"5Γÿà Dubai Marina Hotel\"},{\"day\":\"Day 6\",\"title\":\"Gold Souk Shopping & Airport Transfer\",\"desc\":\"Morning duty-free shopping at Deira Gold Souk followed by flight departure.\",\"meals\":\"Breakfast Included\",\"stay\":\"Tour Concludes\"}]', '[\"5 Nights 4Γÿà\\/5Γÿà Hotel Stays in Dubai City\",\"Daily Lavish Buffet Breakfasts & 2 Special Dinners\",\"Dubai Tourist Visa (30 Days Single Entry) + Travel Insurance\",\"Burj Khalifa 124th floor + Dubai Mall Aquarium passes\",\"Private Luxury Airport Pick & Drop\"]', '[\"International Flights to Dubai (DXB\\/DWC)\",\"Tourism Dirham Fee payable directly at hotel check-in\",\"Optional Museum of the Future tickets\"]', '[\"5\"]', 'Atlantis The Palm Island Resort / 5Γÿà Luxury Downtown Dubai Hotel', 'Free cancellation up to 14 days prior to travel date.', 'international', '5', '6', '5 Nights / 6 Days', NULL, 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58'),
('5', 'Bali Tropical Jungle Villa, Ubud Swings & Nusa Penida', 'bali-tropical-jungle-villa-nusa-penida', 'Private pool villa, giant Ubud swings, Tanah Lot temple & Nusa Penida island cruise', 'Ubud, Seminyak, Kuta, Nusa Penida', 'Bali, Indonesia', 'Tropical Island & Cultural Sanctuary', NULL, '32499.00', '42000.00', '4000.00', 'Visa Free Entry', '4.9', '440', 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=80', NULL, '[{\"icon\":\"fa-solid fa-person-swimming\",\"title\":\"Private Pool Villa Stay\",\"desc\":\"Luxury floating breakfast included with floral pool setup\"},{\"icon\":\"fa-solid fa-camera\",\"title\":\"Nusa Penida West Island Tour\",\"desc\":\"Fast speedboat tickets to Kelingking T-Rex cliff and Broken beach\"},{\"icon\":\"fa-solid fa-tree\",\"title\":\"Aloha Ubud Jungle Swings\",\"desc\":\"Adrenaline jungle swing pass overlooking emerald rice terraces\"}]', '[{\"day\":\"Day 1\",\"title\":\"Arrival Denpasar & Transfer to Ubud Jungle Villa\",\"desc\":\"Warm Balinese welcome at DPS airport. Check into your romantic private pool villa.\",\"meals\":\"Welcome Drink\",\"stay\":\"Ubud Private Pool Villa\"},{\"day\":\"Day 2\",\"title\":\"Tegalalang Rice Terraces & Ubud Jungle Swing\",\"desc\":\"Snap iconic photos on the giant swings and visit coffee plantation for Luwak tasting.\",\"meals\":\"Floating Breakfast\",\"stay\":\"Ubud Private Pool Villa\"},{\"day\":\"Day 3\",\"title\":\"Kintamani Volcano & Tegenungan Waterfall\",\"desc\":\"Witness active Mount Batur volcanic caldera and swim beneath Tegenungan waterfall.\",\"meals\":\"Breakfast & Buffet Lunch\",\"stay\":\"Ubud Private Pool Villa\"},{\"day\":\"Day 4\",\"title\":\"Transfer to Seminyak & Tanah Lot Sunset\",\"desc\":\"Relocate to beachside Seminyak. Witness breathtaking sunset at Tanah Lot ocean temple.\",\"meals\":\"Breakfast Included\",\"stay\":\"Seminyak Beach Resort\"},{\"day\":\"Day 5\",\"title\":\"Full-Day Nusa Penida Island Tour\",\"desc\":\"Speedboat to Nusa Penida. Visit Kelingking Beach, Angel\'s Billabong, and Crystal Bay.\",\"meals\":\"Breakfast & Local Lunch\",\"stay\":\"Seminyak Beach Resort\"},{\"day\":\"Day 6\",\"title\":\"Uluwatu Cliff Temple & Kecak Fire Dance\",\"desc\":\"Perched on high ocean cliffs, watch the hypnotic sunset Kecak fire dance performance.\",\"meals\":\"Breakfast & Jimbaran Seafood\",\"stay\":\"Seminyak Beach Resort\"},{\"day\":\"Day 7\",\"title\":\"Souvenir Shopping & Airport Departure\",\"desc\":\"Final shopping at Krishna Oleh-Oleh before airport drop.\",\"meals\":\"Breakfast Included\",\"stay\":\"Tour Concludes\"}]', '[\"6 Nights Stays (3N Ubud Private Pool Villa + 3N Seminyak Resort)\",\"Daily Floating Breakfasts & Welcome Flower Garlands\",\"Fast Speedboat transfers to Nusa Penida Island\",\"Private Chauffeur Car with English speaking guide\"]', '[\"International Flights to Denpasar (DPS)\",\"Indonesia Tourist Visa on Arrival fee ($35 USD paid directly)\"]', '[]', 'Ubud Private Pool Jungle Villa (3N) + Seminyak Beachfront Resort (3N)', 'Free cancellation up to 10 days before flight date.', 'international', '6', '7', '6 Nights / 7 Days', NULL, 'active', '2026-10-06 02:00:58', '2026-10-06 02:00:58');


-- --------------------------------------------------------
-- Table structure for table `settings`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `settings` (32 rows)
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
('1', 'site_name', 'GuideFlux', '2026-10-05 23:48:09'),
('2', 'site_tagline', 'Your Passport to Adventure', '2026-10-05 23:48:09'),
('3', 'site_phone', '+91 98765 43210', '2026-10-05 23:48:09'),
('4', 'site_email', 'support@guideflux.com', '2026-10-05 23:48:09'),
('5', 'site_whatsapp', '919876543210', '2026-10-05 23:48:09'),
('6', 'site_address', 'DLF Cyber City, Tower B, Gurugram, Haryana - 122002', '2026-10-05 23:48:09'),
('7', 'site_logo', 'assets/images/logo/logo.webp', '2026-10-05 23:48:09'),
('8', 'mail_mode', 'testing', '2026-10-05 23:48:09'),
('9', 'testing_otp', '123456', '2026-10-05 23:48:09'),
('10', 'smtp_host', 'mail.guideflux.com', '2026-10-05 23:48:09'),
('11', 'smtp_port', '465', '2026-10-05 23:48:09'),
('12', 'smtp_user', 'support@guideflux.com', '2026-10-05 23:48:09'),
('13', 'smtp_pass', '', '2026-10-05 23:48:09'),
('14', 'smtp_encryption', 'ssl', '2026-10-05 23:48:09'),
('15', 'smtp_from_name', 'GuideFlux Travel', '2026-10-05 23:48:09'),
('16', 'smtp_from_email', 'support@guideflux.com', '2026-10-05 23:48:09'),
('17', 'social_facebook', 'https://facebook.com', '2026-10-05 23:48:09'),
('18', 'social_instagram', 'https://instagram.com', '2026-10-05 23:48:09'),
('19', 'social_twitter', 'https://twitter.com', '2026-10-05 23:48:09'),
('20', 'social_youtube', 'https://youtube.com', '2026-10-05 23:48:09'),
('29', 'google_login_active', '1', '2026-10-06 00:15:25'),
('30', 'google_client_id', 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com', '2026-10-06 00:15:25'),
('31', 'google_client_secret', 'YOUR_GOOGLE_CLIENT_SECRET', '2026-10-06 00:15:25'),
('34', 'flight_api_provider', 'ignav', '2026-10-06 09:23:38'),
('35', 'rapidapi_key', 'dbbdb3e54amsh72d9f1c34c301a3p15b04ajsn3ce837a3c5e7', '2026-10-06 08:35:51'),
('36', 'rapidapi_host', 'flight-fare-search.p.rapidapi.com', '2026-10-06 08:35:51'),
('38', 'ignav_api_key', 'ignav_fO-UFojh4eaCGFqHMzg-hbHj_FSKEtBo', '2026-10-06 09:23:38'),
('39', 'usd_to_inr_rate', '98', '2026-10-06 09:34:57'),
('43', 'flight_markup_type', 'percentage', '2026-10-06 09:34:57'),
('44', 'flight_commission_domestic', '3', '2026-10-06 09:34:57'),
('45', 'flight_commission_international', '10', '2026-10-06 09:34:57'),
('46', 'flight_convenience_fee', '249', '2026-10-06 09:34:57');


-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(50) DEFAULT 'email',
  `oauth_uid` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users` (5 rows)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `avatar`, `oauth_provider`, `oauth_uid`, `is_verified`, `otp_code`, `otp_expires_at`, `status`, `created_at`, `last_login`) VALUES
('1', 'Rohan Verma', 'rohan.v@example.com', '+91 9876500111', '$2y$10$3.VlpGyAxLAlpJOlMQMd/.IcsHEWR7uDPR994LNvEbVuPBRRut2ji', NULL, 'email', NULL, '1', NULL, NULL, 'active', '2026-09-30 23:48:09', '2026-10-05 21:48:09'),
('2', 'Priya Patel', 'priya.p@example.com', '+91 9811223344', '$2y$10$3.VlpGyAxLAlpJOlMQMd/.IcsHEWR7uDPR994LNvEbVuPBRRut2ji', NULL, 'email', NULL, '1', NULL, NULL, 'active', '2026-09-23 23:48:09', '2026-10-04 23:48:09'),
('3', 'Amitabh Singh', 'amitabh.s@example.com', '+91 9988776655', '$2y$10$3.VlpGyAxLAlpJOlMQMd/.IcsHEWR7uDPR994LNvEbVuPBRRut2ji', NULL, 'email', NULL, '0', NULL, NULL, 'active', '2026-10-05 22:48:09', NULL),
('4', 'Nikhil singh', 'admin@guideflux.com', '6676767677', '$2y$10$Q957nm4trPU0jaJgm8v/zOcDW3MAmn7wAjFQDFDA/AhCSdTJ8.CZS', NULL, 'email', NULL, '1', NULL, NULL, 'active', '2026-10-05 23:55:20', '2026-10-05 23:55:28'),
('5', 'Nikhil Singh', 'nikhilbhadauriya.gspt@gmail.com', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIkJAuR0S2iKdkUjPueFqZmu2xSX1YoUf_Yvtv65mK5V-bZeCw=s96-c', 'google', '112081495439939830062', '1', NULL, NULL, 'active', '2026-10-06 07:15:13', '2026-10-06 07:15:13');


SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
