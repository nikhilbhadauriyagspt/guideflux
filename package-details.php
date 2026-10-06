<?php
/**
 * Tour Package Details Page - Orion Advent
 * - Comprehensive Day-by-Day Itinerary Timeline
 * - 5-Photo Responsive Magazine Gallery
 * - Inclusions / Exclusions, Hotels Included & Policy Accordions
 * - Sticky Token Advance Booking Sidebar with Live Guest Calculator
 * - 100% Flat, Border-First Design (Strictly ZERO shadows)
 * - Pure Font Awesome 6 Vector Icons Only (Zero Emojis)
 */

$packageId = isset($_GET['id']) ? htmlspecialchars(trim($_GET['id'])) : 'PKG-KASHMIR-01';

// Packages Database
$packagesDb = [
    'PKG-KASHMIR-01' => [
        'id' => 'PKG-KASHMIR-01',
        'title' => 'Kashmir Paradise: Shikara, Snow Peaks & Luxury Dal Lake Houseboat',
        'subtitle' => 'Explore the Switzerland of India with private chauffeured transfers, gondola assistance, and authentic royal houseboat hospitality.',
        'destination' => 'Srinagar • Gulmarg • Pahalgam • Sonmarg',
        'state' => 'Jammu & Kashmir, India',
        'duration' => '5 Nights / 6 Days',
        'type' => 'Domestic Holiday Circuit',
        'badge' => 'Bestseller 2026',
        'badge_class' => 'bg-brand-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 1420,
        'price' => 17999,
        'original_price' => 22999,
        'token_advance' => 2500,
        'gallery' => [
            'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=1200&q=80', // Dal Lake Shikara
            'https://images.unsplash.com/photo-1566837945700-30057527ade0?auto=format&fit=crop&w=600&q=80',  // Gulmarg Snow
            'https://images.unsplash.com/photo-1605649487212-47bdab064df8?auto=format&fit=crop&w=600&q=80',  // Pahalgam Valley
            'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=600&q=80',  // Kashmiri Pine Resort
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80',  // Houseboat Interior
        ],
        'highlights' => [
            ['icon' => 'fa-solid fa-sailboat', 'title' => 'Dal Houseboat Stay', 'desc' => '1 Night in handcrafted cedar wood luxury houseboat'],
            ['icon' => 'fa-solid fa-water', 'title' => 'Sunset Shikara Ride', 'desc' => '1 Hour Shikara cruise through lotus gardens & floating markets'],
            ['icon' => 'fa-solid fa-cable-car', 'title' => 'Gulmarg Gondola', 'desc' => 'Scenic cable car assistance to Apharwat peak snow point'],
            ['icon' => 'fa-solid fa-car', 'title' => 'Private AC Vehicle', 'desc' => 'Dedicated comfortable Sedan for all airport & sightseeing transfers'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Daily Breakfast & Dinner', 'desc' => 'Freshly prepared Kashmiri Wazwan & North Indian buffet meals'],
            ['icon' => 'fa-solid fa-camera', 'title' => 'Betaab & Aru Valley', 'desc' => 'Guided sightseeing across Lidder River & pine meadows in Pahalgam'],
        ],
        'itinerary' => [
            [
                'day' => 'Day 01',
                'title' => 'Arrival at Srinagar Airport & Romantic Dal Lake Shikara Sunset',
                'desc' => 'Arrive at Sheikh ul-Alam International Airport, Srinagar where your dedicated Orion Advent chauffeur greets you with a warm Kashmiri welcome. Transfer to your 4-star hotel in Srinagar. After freshening up, head out for a memorable 1-hour sunset Shikara ride across the serene waters of Dal Lake, gliding past historic floating flower markets and Char Chinar island. Enjoy authentic evening Kehwa tea with almonds.',
                'meals' => 'Dinner Included',
                'stay' => '4★ Hotel in Srinagar'
            ],
            [
                'day' => 'Day 02',
                'title' => 'Srinagar to Gulmarg "Meadow of Flowers" & Gondola Cable Car Ride',
                'desc' => 'After a hearty breakfast, drive towards Gulmarg (approx. 52 km / 1.5 hrs), perched at an altitude of 8,825 feet. Enjoy panoramic vistas of snow-dusted pine forests and the highest golf course in the world. Board the famous Gulmarg Gondola (Asia\'s highest cable car) up to Phase 1 / Kungdoor and Phase 2 / Apharwat Peak for thrilling snow activities. Return to Srinagar hotel in the evening.',
                'meals' => 'Breakfast & Dinner Included',
                'stay' => '4★ Hotel in Srinagar'
            ],
            [
                'day' => 'Day 03',
                'title' => 'Excursion to Pahalgam "Valley of Shepherds" & Betaab Valley',
                'desc' => 'Embark on a picturesque journey to Pahalgam via the world-renowned Saffron fields of Pampore and historic Awantipora ruins. Drive along the gushing Lidder River flanked by lush pine mountains. Visit Betaab Valley (named after the Bollywood classic), Chandanwari (starting point of Amarnath Yatra), and pristine Aru Valley. Enjoy riverside walks and horse riding before returning to Srinagar.',
                'meals' => 'Breakfast & Dinner Included',
                'stay' => '4★ Hotel in Srinagar'
            ],
            [
                'day' => 'Day 04',
                'title' => 'Day Trip to Sonmarg "Meadow of Gold" & Thajiwas Glacier',
                'desc' => 'Travel through the dramatic Sindh Valley to Sonmarg (approx. 80 km), known for its alpine meadows and pristine glacier views. Take a pony trek or local cab to Thajiwas Glacier, where snow remains throughout the summer season. Enjoy river rafting in the Sindh river (optional) or relax by the pristine riverbanks. Return to your hotel for dinner.',
                'meals' => 'Breakfast & Dinner Included',
                'stay' => '4★ Hotel in Srinagar'
            ],
            [
                'day' => 'Day 05',
                'title' => 'Mughal Gardens Heritage Tour & Check-in to Luxury Dal Houseboat',
                'desc' => 'Explore the legendary Royal Mughal Gardens—Nishat Bagh (Garden of Bliss) and Shalimar Bagh (Abode of Love) built during Emperor Jahangir’s reign. Visit Hazratbal Shrine and the historic Shankaracharya Temple with 360-degree aerial views of the entire valley. In the afternoon, transfer to your handcrafted luxury heritage houseboat on Dal Lake. Enjoy a royal dinner under the starlit lake.',
                'meals' => 'Breakfast & Royal Houseboat Dinner',
                'stay' => 'Luxury Heritage Houseboat on Dal Lake'
            ],
            [
                'day' => 'Day 06',
                'title' => 'Sunrise Kehwa on Dal Lake, Souvenir Shopping & Departure',
                'desc' => 'Wake up to the tranquil sound of gentle water ripples and bird songs. Savor a piping hot cup of saffron Kehwa on the houseboat deck. Check out after breakfast and stop by the government handloom center for authentic Pashmina shawls, saffron, walnut wood carvings, and dry fruits. Chauffeur drops you safely at Srinagar Airport with cherished memories.',
                'meals' => 'Breakfast Included',
                'stay' => 'Tour Concludes'
            ],
        ],
        'inclusions' => [
            'Pick-up and drop from Srinagar Airport by private dedicated AC vehicle',
            '4 Nights stay in a verified 4★ Hotel in Srinagar (Premium Valley/Lawn View)',
            '1 Night stay in a Luxury Handcrafted Cedar Wood Houseboat on Dal Lake',
            'Daily Buffet Breakfast and Chef-curated Dinner at all hotels & houseboat',
            '1-Hour private Sunset Shikara Ride on Dal Lake for all travelers',
            'All interstate toll taxes, state parking charges, fuel, and driver night allowances',
            'Complimentary welcome drink (Kashmiri Kahwa with dry fruits) upon arrival',
            'Assistance with Gondola cable car booking and Pahalgam local union permits',
            '24/7 dedicated Orion Advent tour manager support on ground',
            'All applicable hotel luxury and service taxes included',
        ],
        'exclusions' => [
            'Airfare or train tickets from your home city to Srinagar (can be added on request)',
            'Gondola Cable Car tickets Phase 1 & Phase 2 (chargeable at counter/online)',
            'Pony / horse riding, snow sledges, ATV rides, and personal adventure activities',
            'Local Union taxi in Pahalgam (Aru, Chandanwari, Betaab) as per local union rules',
            'Entry fees to monuments, gardens, and camera/drone permits',
            'Personal expenses such as laundry, phone calls, tips, and room heater charges',
        ],
        'stays' => [
            [
                'name' => 'The Grand Mamta / RK Sarovar Portico',
                'location' => 'Boulevard Road, Dal Lake, Srinagar',
                'type' => '4-Star Premium Hotel',
                'rating' => 4.8,
                'nights' => '4 Nights',
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'Mascot Luxury Heritage Houseboats',
                'location' => 'Nigeen Lake / Dal Lake Front, Srinagar',
                'type' => 'Luxury Royal Houseboat',
                'rating' => 4.9,
                'nights' => '1 Night',
                'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=400&q=80',
            ]
        ]
    ],
    'PKG-KERALA-02' => [
        'id' => 'PKG-KERALA-02',
        'title' => 'Kerala Backwaters: Misty Munnar Hills & Private Alleppey Houseboat',
        'subtitle' => 'Unwind in God\'s Own Country with aromatic tea plantations in Munnar, wildlife spice trails in Thekkady, and a private luxury houseboat stay in Alleppey backwaters.',
        'destination' => 'Kochi • Munnar • Thekkady • Alleppey',
        'state' => 'Kerala, India',
        'duration' => '4 Nights / 5 Days',
        'type' => 'Honeymoon & Nature Circuit',
        'badge' => 'Honeymoon Fav',
        'badge_class' => 'bg-emerald-600 text-white',
        'rating' => 4.8,
        'reviews_count' => 980,
        'price' => 18500,
        'original_price' => 24000,
        'token_advance' => 2500,
        'gallery' => [
            'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=1200&q=80', // Alleppey Houseboat
            'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=600&q=80',  // Munnar Tea Hills
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',  // Kerala Lake Resort
            'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=600&q=80',  // Spice Plantations
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',  // Ayurvedic Spa
        ],
        'highlights' => [
            ['icon' => 'fa-solid fa-sailboat', 'title' => 'Exclusive Houseboat Cruise', 'desc' => 'Overnight stay in private luxury Alleppey houseboat'],
            ['icon' => 'fa-solid fa-mug-hot', 'title' => 'Munnar Tea Estates', 'desc' => 'Guided walks through misty Kolukkumalai tea plantations'],
            ['icon' => 'fa-solid fa-tree', 'title' => 'Periyar Wildlife Sanctuary', 'desc' => 'Boat safari & spice garden exploration in Thekkady'],
            ['icon' => 'fa-solid fa-car', 'title' => 'Chauffeured Sedan', 'desc' => 'Dedicated comfortable vehicle for complete circuit'],
            ['icon' => 'fa-solid fa-spa', 'title' => 'Ayurvedic Wellness', 'desc' => 'Complimentary herbal rejuvenation session voucher'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Traditional Kerala Meals', 'desc' => 'Authentic Karimeen fish fry & sadhya buffet included'],
        ],
        'itinerary' => [
            [
                'day' => 'Day 01',
                'title' => 'Arrival at Kochi Airport & Scenic Drive to Munnar Tea Hills',
                'desc' => 'Warm reception at Cochin International Airport. Embark on a panoramic 4-hour drive to Munnar, passing Cheeyappara and Valara waterfalls. Check in to your mountain resort amidst mist-covered hills and enjoy evening leisure.',
                'meals' => 'Dinner Included',
                'stay' => '4★ Resort in Munnar'
            ],
            [
                'day' => 'Day 02',
                'title' => 'Munnar Sightseeing: Eravikulam National Park & Mattupetty Dam',
                'desc' => 'Explore Eravikulam National Park, home to the endangered Nilgiri Tahr mountain goat. Visit Mattupetty Dam, Echo Point, and the Tea Museum where you witness artisanal tea crafting. Return for an evening bonfire.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort in Munnar'
            ],
            [
                'day' => 'Day 03',
                'title' => 'Munnar to Thekkady Periyar Wildlife & Spice Plantations',
                'desc' => 'Drive through the Cardamom Hills to Thekkady (approx. 3 hrs). Tour organic spice plantations of cardamom, pepper, and cinnamon. Enjoy a scenic boat ride on Lake Periyar with elephant spotting opportunities.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Jungle Resort in Thekkady'
            ],
            [
                'day' => 'Day 04',
                'title' => 'Thekkady to Alleppey & Check-in to Private Luxury Houseboat',
                'desc' => 'Reach Alleppey by noon and board your traditional handcrafted Kettuvallam houseboat. Cruise through scenic palm-fringed canals, paddy fields, and backwater villages. Savor freshly prepared Kerala cuisine on board under the stars.',
                'meals' => 'Lunch, Evening Snacks & Royal Dinner',
                'stay' => 'Private AC Luxury Houseboat'
            ],
            [
                'day' => 'Day 05',
                'title' => 'Houseboat Sunrise Breakfast & Transfer to Kochi Airport',
                'desc' => 'Wake up to backwater tranquility. Enjoy traditional appam and stew on deck. Disembark by 09:30 AM and transfer back to Kochi Airport / Ernakulam Junction with beautiful memories.',
                'meals' => 'Breakfast Included',
                'stay' => 'Tour Concludes'
            ],
        ],
        'inclusions' => [
            'Pick-up and drop from Cochin International Airport by private AC sedan',
            '2 Nights stay in a 4★ Munnar Mountain View Resort',
            '1 Night stay in a 4★ Thekkady Jungle Sanctuary Resort',
            '1 Night stay in a Private Luxury Houseboat in Alleppey with full crew',
            'Daily breakfast and dinner (Houseboat includes lunch, snacks & dinner)',
            'Complimentary spice plantation guided walk in Thekkady',
            'All interstate taxes, toll charges, fuel, parking, and driver night allowances',
            '24/7 dedicated Orion Advent tour manager support on ground',
        ],
        'exclusions' => [
            'Flight or train tickets to/from Kochi',
            'Periyar wildlife lake boat safari tickets',
            'Kathakali cultural dance & Kalaripayattu martial arts show entry',
            'Personal laundry, beverages, and tips',
        ],
        'stays' => [
            [
                'name' => 'Broad Bean Resort & Spa',
                'location' => 'Chithirapuram, Munnar Hills',
                'type' => '4-Star Hill Resort',
                'rating' => 4.8,
                'nights' => '2 Nights',
                'image' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'Lakes & Lagoons Luxury Houseboat',
                'location' => 'Punnamada Jetty, Alleppey',
                'type' => 'Private Luxury Houseboat',
                'rating' => 4.9,
                'nights' => '1 Night',
                'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=400&q=80',
            ]
        ]
    ],
    'PKG-HIMACHAL-03' => [
        'id' => 'PKG-HIMACHAL-03',
        'title' => 'Himachal Alpine Wonder: Solang Snow Peaks, Atal Tunnel & Manali',
        'subtitle' => 'Discover the majestic Pir Panjal Himalayas with private transfers across Shimla colonial mall, Kufri pine ridges, Atal Tunnel, and Solang snow activities.',
        'destination' => 'Shimla • Kufri • Kullu • Manali • Solang',
        'state' => 'Himachal Pradesh, India',
        'duration' => '5 Nights / 6 Days',
        'type' => 'Hills & Adventure Circuit',
        'badge' => 'Snow Peaks',
        'badge_class' => 'bg-sky-600 text-white',
        'rating' => 4.8,
        'reviews_count' => 1650,
        'price' => 14999,
        'original_price' => 19500,
        'token_advance' => 2500,
        'gallery' => [
            'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&w=1200&q=80', // Manali snow
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',  // Shimla Heritage
            'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=600&q=80',  // Alpine Valley
            'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=600&q=80',  // Pine Chalet
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',  // Mountain River
        ],
        'highlights' => [
            ['icon' => 'fa-solid fa-mountain', 'title' => 'Solang Valley Adventure', 'desc' => 'Paragliding, zorbing, and snow activities in Solang'],
            ['icon' => 'fa-solid fa-road', 'title' => 'Atal Tunnel to Sissu', 'desc' => 'Drive through the engineering marvel into Lahaul valley'],
            ['icon' => 'fa-solid fa-tree', 'title' => 'Kufri Pine Ridges', 'desc' => 'Horse ride to Mahasu Peak and Himalayan Nature Park'],
            ['icon' => 'fa-solid fa-car', 'title' => 'Private AC Cab', 'desc' => 'Dedicated Sedan for Delhi / Chandigarh pick and all tours'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Daily Breakfast & Dinner', 'desc' => 'Buffet meals prepared with fresh Himalayan produce'],
            ['icon' => 'fa-solid fa-fire', 'title' => 'Bonfire Evening', 'desc' => 'Complimentary music & campfire night in Manali'],
        ],
        'itinerary' => [
            [
                'day' => 'Day 01',
                'title' => 'Delhi / Chandigarh to Shimla "Queen of Hills"',
                'desc' => 'Pick-up from Delhi/Chandigarh Airport or Railway station. Scenic uphill drive to Shimla. Check in to your hotel and spend the evening strolling on the heritage Mall Road, The Ridge, and Christ Church.',
                'meals' => 'Dinner Included',
                'stay' => '4★ Hotel in Shimla'
            ],
            [
                'day' => 'Day 02',
                'title' => 'Shimla & Kufri Excursion with Himalayan Views',
                'desc' => 'Drive to Kufri at 8,600 feet. Enjoy horse riding to Mahasu Peak, fun world activities, and pine forest viewpoints. Visit Jakhoo Temple with the giant Hanuman statue before evening leisure.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Hotel in Shimla'
            ],
            [
                'day' => 'Day 03',
                'title' => 'Shimla to Manali via Kullu Valley & Pandoh Dam',
                'desc' => 'Scenic transfer to Manali along the Beas River. Stop at Sundernagar Lake, Pandoh Dam, Hanogi Mata Temple, and Kullu Shawl weaving factories. Check in to your valley resort in Manali.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort in Manali'
            ],
            [
                'day' => 'Day 04',
                'title' => 'Solang Valley Snow Point & Atal Tunnel to Sissu',
                'desc' => 'Head out early to Solang Valley for thrilling mountain adventures. Cross through the 9.02 km Atal Tunnel into Sissu in Lahaul Valley to marvel at the frozen waterfall and stark Himalayan peaks.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort in Manali'
            ],
            [
                'day' => 'Day 05',
                'title' => 'Manali Local Sightseeing: Hadimba Temple & Vashisht Baths',
                'desc' => 'Visit the 450-year-old wooden Hadimba Devi Temple set inside cedar deodar forests. Dip your feet in natural sulfur hot springs at Vashisht. Explore Tibetan Monastery and Old Manali cafes.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort in Manali'
            ],
            [
                'day' => 'Day 06',
                'title' => 'Check-out & Return Drive to Chandigarh / Delhi Airport',
                'desc' => 'Relish breakfast with mountain views. Check out and embark on your return drive. Chauffeur drops you safely at Chandigarh / Delhi Airport or Railway station.',
                'meals' => 'Breakfast Included',
                'stay' => 'Tour Concludes'
            ],
        ],
        'inclusions' => [
            'Pick-up & drop from Delhi/Chandigarh Airport/Station in private dedicated vehicle',
            '2 Nights stay in a 4★ Shimla Hotel (Valley View)',
            '3 Nights stay in a 4★ Manali Alpine Resort',
            'Daily buffet breakfast and dinner at all properties',
            'Sightseeing as per itinerary including Solang Valley, Atal Tunnel, Kufri & Mall Road',
            'Toll taxes, state road taxes, parking, driver night allowances and fuel',
            '24/7 on-ground assistance and live concierge support',
        ],
        'exclusions' => [
            'Airfare or train tickets to Delhi/Chandigarh',
            'Rohtang Pass NGT green permit and taxi (chargeable if opened by authorities)',
            'Adventure activity costs like paragliding, river rafting, and zorbing',
            'Personal heaters, laundry, and beverage charges',
        ],
        'stays' => [
            [
                'name' => 'The Grand White / Marina Shimla',
                'location' => 'Mall Road Ridge, Shimla',
                'type' => '4-Star Hotel',
                'rating' => 4.7,
                'nights' => '2 Nights',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'Larisa Apple Orchards & Resort',
                'location' => 'Haripur, Manali Valley',
                'type' => '4-Star Alpine Resort',
                'rating' => 4.8,
                'nights' => '3 Nights',
                'image' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=400&q=80',
            ]
        ]
    ],
    'PKG-GOA-05' => [
        'id' => 'PKG-GOA-05',
        'title' => 'Goa Beachside Luxury: Sunset Mandovi Cruise & Coastal Resorts',
        'subtitle' => 'Soak up the tropical sun with beachfront resort stays in North Goa, Mandovi River sunset cruise with Goan folk dance, and historic Portuguese fort exploration.',
        'destination' => 'North Goa • Calangute • Baga • Mandovi River',
        'state' => 'Goa, India',
        'duration' => '3 Nights / 4 Days',
        'type' => 'Beach Holiday Circuit',
        'badge' => 'Beach Special',
        'badge_class' => 'bg-teal-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 2100,
        'price' => 11999,
        'original_price' => 15500,
        'token_advance' => 2000,
        'gallery' => [
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=1200&q=80', // Goa Beach
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',  // Beach Resort
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80',  // Sunset Pool
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80',  // Mandovi River
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',  // Coastal Dinner
        ],
        'highlights' => [
            ['icon' => 'fa-solid fa-umbrella-beach', 'title' => '4★ Beachfront Resort', 'desc' => 'Steps away from Calangute & Candolim sands'],
            ['icon' => 'fa-solid fa-ship', 'title' => 'Mandovi Sunset Cruise', 'desc' => '1-Hour river cruise with DJ music & Goan folk performance'],
            ['icon' => 'fa-solid fa-monument', 'title' => 'Historic Forts & Churches', 'desc' => 'Fort Aguada, Chapora Fort, and Basilica of Bom Jesus'],
            ['icon' => 'fa-solid fa-car', 'title' => 'Private Airport Transfers', 'desc' => 'Dedicated AC cab for Goa airport pick & drop'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Daily Buffet Breakfast', 'desc' => 'Elaborate coastal & continental breakfast buffet'],
            ['icon' => 'fa-solid fa-person-swimming', 'title' => 'Free Pool Access', 'desc' => 'Outdoor swimming pool & poolside sun loungers'],
        ],
        'itinerary' => [
            [
                'day' => 'Day 01',
                'title' => 'Arrival at Goa Airport & Beachside Leisure',
                'desc' => 'Pick up from Dabolim (GOI) or Mopa (GOX) Airport. Transfer to your 4-star beachside resort in North Goa. Enjoy a refreshing welcome drink, relax by the swimming pool, and spend a casual evening watching sunset at Calangute beach.',
                'meals' => 'Buffet Breakfast & Welcome Drink',
                'stay' => '4★ Beach Resort in North Goa'
            ],
            [
                'day' => 'Day 02',
                'title' => 'North Goa Beaches, Fort Aguada & Water Sports',
                'desc' => 'Visit the historic 17th-century Portuguese Fort Aguada and its lighthouse. Explore vibrant Baga and Anjuna beaches. Try exciting water sports (parasailing, jet-skiing) and explore quirky beach shacks.',
                'meals' => 'Buffet Breakfast Included',
                'stay' => '4★ Beach Resort in North Goa'
            ],
            [
                'day' => 'Day 03',
                'title' => 'South Goa Heritage Churches & Mandovi Sunset Cruise',
                'desc' => 'Explore the UNESCO heritage Old Goa churches—Basilica of Bom Jesus and Se Cathedral. Visit Mangueshi Temple and Panjim Latin Quarter (Fontainhas). In the evening, board a 1-hour Mandovi River Sunset Cruise with music.',
                'meals' => 'Buffet Breakfast Included',
                'stay' => '4★ Beach Resort in North Goa'
            ],
            [
                'day' => 'Day 04',
                'title' => 'Souvenir Shopping & Departure to Goa Airport',
                'desc' => 'Relish a delicious poolside breakfast. Pick up famous Goan cashews, feni, and handicrafts. Chauffeur drops you safely at Goa Airport with fantastic holiday memories.',
                'meals' => 'Breakfast Included',
                'stay' => 'Tour Concludes'
            ],
        ],
        'inclusions' => [
            'Pick-up & drop from Goa Airport (GOI/GOX) in private AC vehicle',
            '3 Nights stay in a 4★ North Goa Resort with swimming pool',
            'Daily buffet breakfast at resort coffee shop',
            '1-Hour Mandovi River Sunset Cruise tickets for all travelers',
            'North Goa and South Goa sightseeing as per itinerary in private cab',
            'All vehicle parking, tolls, driver allowance, and taxes',
        ],
        'exclusions' => [
            'Flight or train tickets to Goa',
            'Beach water sports and scuba diving charges',
            'Lunches and alcoholic drinks',
        ],
        'stays' => [
            [
                'name' => 'Acron Waterfront / Whispering Palms Beach Resort',
                'location' => 'Candolim Beach Road, North Goa',
                'type' => '4-Star Beach Resort',
                'rating' => 4.8,
                'nights' => '3 Nights',
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
            ]
        ]
    ],
    // Fallback template for any other package query
    'default' => [
        'id' => 'PKG-DEFAULT',
        'title' => 'Curated Holiday Circuit & Premium Stay Experience',
        'subtitle' => 'Handcrafted travel package with verified 4-star stays, private cab, daily breakfast, and complete tour concierge.',
        'destination' => 'Custom Vacation Circuit',
        'state' => 'India & International Circuits',
        'duration' => '5 Nights / 6 Days',
        'type' => 'Holiday Package',
        'badge' => 'Featured Tour',
        'badge_class' => 'bg-brand-600 text-white',
        'rating' => 4.9,
        'reviews_count' => 1120,
        'price' => 16500,
        'original_price' => 21000,
        'token_advance' => 2500,
        'gallery' => [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&w=600&q=80',
        ],
        'highlights' => [
            ['icon' => 'fa-solid fa-hotel', 'title' => '4★ Verified Hotels', 'desc' => 'Clean, sanitized, top-rated property stays'],
            ['icon' => 'fa-solid fa-car', 'title' => 'Private Cab', 'desc' => 'Dedicated comfortable vehicle for all sightseeing'],
            ['icon' => 'fa-solid fa-utensils', 'title' => 'Meals Included', 'desc' => 'Daily buffet breakfast & dinner'],
            ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Token Advance', 'desc' => 'Book with just ₹2,500 token advance'],
        ],
        'itinerary' => [
            [
                'day' => 'Day 01',
                'title' => 'Arrival & Welcome Reception',
                'desc' => 'Arrive at destination airport/station where your private cab awaits. Transfer to hotel, check-in, and enjoy evening leisure.',
                'meals' => 'Dinner Included',
                'stay' => '4★ Resort Stay'
            ],
            [
                'day' => 'Day 02',
                'title' => 'Full Day Sightseeing & Key Landmarks',
                'desc' => 'Visit famous iconic points and scenic landscapes with our private tour guide.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort Stay'
            ],
            [
                'day' => 'Day 03',
                'title' => 'Adventure, Nature & Cultural Exploration',
                'desc' => 'Experience local traditions, authentic cuisine, and thrilling activities.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort Stay'
            ],
            [
                'day' => 'Day 04',
                'title' => 'Scenic Excursion & Sunset Viewpoints',
                'desc' => 'Drive through the finest routes and visit serene viewpoints.',
                'meals' => 'Breakfast & Dinner',
                'stay' => '4★ Resort Stay'
            ],
            [
                'day' => 'Day 05',
                'title' => 'Souvenir Shopping & Farewell Dinner',
                'desc' => 'Explore local bazaar for authentic handicrafts and specialties.',
                'meals' => 'Breakfast & Special Dinner',
                'stay' => '4★ Resort Stay'
            ],
            [
                'day' => 'Day 06',
                'title' => 'Breakfast & Safe Drop at Airport',
                'desc' => 'Enjoy breakfast and transfer to airport with wonderful memories.',
                'meals' => 'Breakfast Included',
                'stay' => 'Tour Concludes'
            ],
        ],
        'inclusions' => [
            'Pick-up and drop from Airport / Station in private AC vehicle',
            '5 Nights accommodation in certified 4-Star hotels & resorts',
            'Daily breakfast and chef-prepared dinner',
            'All sightseeing excursions as per itinerary',
            'Toll taxes, parking, driver allowance, and road permits',
            '24/7 on-ground customer support',
        ],
        'exclusions' => [
            'Flight or train tickets',
            'Personal expenses, tips, and laundry',
            'Entry fees to optional adventure parks or monuments',
        ],
        'stays' => [
            [
                'name' => 'Orion Certified 4★ Partner Resort',
                'location' => 'Central Premium Location',
                'type' => '4-Star Resort',
                'rating' => 4.8,
                'nights' => '5 Nights',
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
            ]
        ]
    ]
];

// Smart Package Resolution from query or id parameter
$lookupKey = 'PKG-KASHMIR-01';
$rawSearch = isset($_GET['id']) ? trim($_GET['id']) : (isset($_GET['pkg']) ? trim($_GET['pkg']) : 'kashmir');

// Check if querying by dynamic DB ID (e.g. PKG-DB-1 or 1)
$dbPkgId = null;
if (strpos($rawSearch, 'PKG-DB-') === 0) {
    $dbPkgId = (int)str_replace('PKG-DB-', '', $rawSearch);
} elseif (is_numeric($rawSearch) && (int)$rawSearch > 0) {
    $dbPkgId = (int)$rawSearch;
}

require_once __DIR__ . '/config/db.php';
$pdoPkg = getDBConnection();
if ($pdoPkg) {
    $fetchedPkg = null;
    if ($dbPkgId) {
        $pkgStmt = $pdoPkg->prepare("SELECT * FROM `packages` WHERE `id` = ?");
        $pkgStmt->execute([$dbPkgId]);
        $fetchedPkg = $pkgStmt->fetch();
    } else {
        $cleanSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $rawSearch)));
        $pkgStmt = $pdoPkg->prepare("SELECT * FROM `packages` WHERE `slug` = ? OR `title` = ? OR `slug` LIKE ? OR `title` LIKE ? LIMIT 1");
        $pkgStmt->execute([$cleanSlug, $rawSearch, "%$cleanSlug%", "%$rawSearch%"]);
        $fetchedPkg = $pkgStmt->fetch();
    }

    if ($fetchedPkg) {
            // Itinerary JSON decode
            $itineraryList = [];
            if (!empty($fetchedPkg['itinerary'])) {
                $decIt = json_decode($fetchedPkg['itinerary'], true);
                if (is_array($decIt)) $itineraryList = $decIt;
            }

            // Highlights JSON decode
            $highlightsList = [];
            if (!empty($fetchedPkg['highlights'])) {
                $decHl = json_decode($fetchedPkg['highlights'], true);
                if (is_array($decHl)) $highlightsList = $decHl;
            }
            if (empty($highlightsList)) {
                $highlightsList = [
                    ['icon' => 'fa-solid fa-hotel', 'title' => 'Handpicked Verified Stays', 'desc' => 'Verified 4★/5★ luxury resorts & hotels'],
                    ['icon' => 'fa-solid fa-car', 'title' => 'Private AC Vehicle', 'desc' => 'Dedicated comfortable transfers throughout circuit'],
                    ['icon' => 'fa-solid fa-utensils', 'title' => 'Buffet Breakfast & Dinner', 'desc' => 'Daily chef-curated breakfast & dinner included'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Guaranteed Token Lock', 'desc' => 'Instant booking with minimal advance token']
                ];
            }

            // Inclusions / Exclusions JSON decode
            $inclusionsList = [];
            if (!empty($fetchedPkg['inclusions'])) {
                $decInc = json_decode($fetchedPkg['inclusions'], true);
                if (is_array($decInc)) $inclusionsList = $decInc;
            }

            $exclusionsList = [];
            if (!empty($fetchedPkg['exclusions'])) {
                $decExc = json_decode($fetchedPkg['exclusions'], true);
                if (is_array($decExc)) $exclusionsList = $decExc;
            }

            // Gallery Photos decode
            $galleryList = [];
            if (!empty($fetchedPkg['featured_image'])) {
                $galleryList[] = $fetchedPkg['featured_image'];
            }
            if (!empty($fetchedPkg['gallery'])) {
                $decGal = json_decode($fetchedPkg['gallery'], true);
                if (is_array($decGal)) {
                    foreach ($decGal as $gImg) {
                        if (!in_array($gImg, $galleryList)) $galleryList[] = $gImg;
                    }
                }
            }
            while (count($galleryList) < 5) {
                $galleryList[] = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80';
            }

            // Resolve Linked Hotels from Database (`hotels` table)
            $linkedStays = [];
            if (!empty($fetchedPkg['hotel_ids'])) {
                $hotelIds = json_decode($fetchedPkg['hotel_ids'], true);
                if (is_array($hotelIds) && !empty($hotelIds)) {
                    $inPlaceholders = implode(',', array_fill(0, count($hotelIds), '?'));
                    $hotelStmt = $pdoPkg->prepare("SELECT * FROM `hotels` WHERE `id` IN ($inPlaceholders)");
                    $hotelStmt->execute($hotelIds);
                    $hResults = $hotelStmt->fetchAll();

                    foreach ($hResults as $hr) {
                        $linkedStays[] = [
                            'name' => $hr['name'],
                            'location' => $hr['address'] ?: ($hr['city'] . ', ' . $hr['country']),
                            'type' => $hr['star_rating'] . '★ ' . $hr['property_type'],
                            'rating' => (float)$hr['star_rating'],
                            'nights' => 'Included Stay',
                            'image' => !empty($hr['featured_image']) ? $hr['featured_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
                            'hotel_id' => $hr['id']
                        ];
                    }
                }
            }

            if (empty($linkedStays)) {
                $linkedStays[] = [
                    'name' => 'GuideFlux Certified 4★ Partner Stays',
                    'location' => $fetchedPkg['location'],
                    'type' => '4-Star Premium Hotel',
                    'rating' => 4.8,
                    'nights' => $fetchedPkg['duration_text'] ?: ($fetchedPkg['duration_nights'] . ' Nights'),
                    'image' => $galleryList[0] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80'
                ];
            }

            $package = [
                'id' => 'PKG-DB-' . $fetchedPkg['id'],
                'title' => $fetchedPkg['title'],
                'subtitle' => $fetchedPkg['subtitle'] ?: 'Handcrafted holiday tour package with verified stays, chauffeur transfers, and complete travel support.',
                'destination' => $fetchedPkg['location'],
                'state' => $fetchedPkg['state_country'] ?: 'India',
                'travel_mode' => $fetchedPkg['travel_mode'] ?? (($fetchedPkg['category'] === 'international') ? 'flight' : 'cab'),
                'departure_city' => $fetchedPkg['departure_city'] ?? 'All Major Cities',
                'duration' => $fetchedPkg['duration_text'] ?: ($fetchedPkg['duration_nights'] . ' Nights / ' . $fetchedPkg['duration_days'] . ' Days'),
                'type' => ucfirst($fetchedPkg['category']) . ' ' . $fetchedPkg['circuit_type'],
                'badge' => $fetchedPkg['badge'] ?: 'Bestseller',
                'badge_class' => 'bg-brand-600 text-white',
                'rating' => (float)$fetchedPkg['rating'],
                'reviews_count' => (int)$fetchedPkg['reviews_count'],
                'price' => (float)$fetchedPkg['price'],
                'original_price' => (float)($fetchedPkg['original_price'] ?: ($fetchedPkg['price'] * 1.25)),
                'token_advance' => (float)($fetchedPkg['token_advance'] ?: 2500),
                'gallery' => $galleryList,
                'highlights' => $highlightsList,
                'itinerary' => $itineraryList,
                'inclusions' => $inclusionsList,
                'exclusions' => $exclusionsList,
                'stays' => $linkedStays
            ];
        }
    }

if (!isset($package)) {
    if (str_contains(strtolower($rawSearch), 'kerala') || str_contains(strtolower($rawSearch), 'munnar') || str_contains(strtolower($rawSearch), 'alleppey')) {
        $lookupKey = 'PKG-KERALA-02';
    } elseif (str_contains(strtolower($rawSearch), 'himachal') || str_contains(strtolower($rawSearch), 'manali') || str_contains(strtolower($rawSearch), 'shimla') || str_contains(strtolower($rawSearch), 'solang')) {
        $lookupKey = 'PKG-HIMACHAL-03';
    } elseif (str_contains(strtolower($rawSearch), 'goa')) {
        $lookupKey = 'PKG-GOA-05';
    } elseif (isset($packagesDb[$packageId])) {
        $lookupKey = $packageId;
    }
    $package = isset($packagesDb[$lookupKey]) ? $packagesDb[$lookupKey] : $packagesDb['PKG-KASHMIR-01'];
}

$pageTitle = $package['title'] . ' | GuideFlux Travel Portal';
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
            <a href="search.php?type=package" class="hover:text-brand-700 transition">Tour Packages</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-brand-700 font-bold truncate max-w-xs sm:max-w-md"><?= htmlspecialchars($package['title']) ?></span>
        </div>

        <a href="search.php?type=package" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-700 hover:text-brand-700 transition">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to All Packages</span>
        </a>
    </div>
</section>

<!-- ==========================================
     HERO TITLE & QUICK DETAILS STRIP
=========================================== -->
<section class="w-full bg-white pt-6 pb-4 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-2">
                <!-- Badges Strip -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold <?= $package['badge_class'] ?>">
                        <?= htmlspecialchars($package['badge']) ?>
                    </span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                        <i class="fa-regular fa-clock text-[10px] mr-1"></i>
                        <?= htmlspecialchars($package['duration']) ?>
                    </span>
                    <?php 
                        $tMode = $package['travel_mode'] ?? 'flight';
                        $tBadge = match($tMode) {
                            'flight' => ['icon' => 'fa-plane-departure', 'label' => 'Return Flights Included', 'class' => 'bg-sky-50 text-sky-800 border-sky-200'],
                            'train' => ['icon' => 'fa-train', 'label' => 'By Train Included', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            'bus' => ['icon' => 'fa-bus', 'label' => 'By Luxury Volvo Bus', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                            'cab' => ['icon' => 'fa-car', 'label' => 'By Private Cab', 'class' => 'bg-teal-50 text-teal-800 border-teal-200'],
                            'land_only' => ['icon' => 'fa-hotel', 'label' => 'Land Package Only', 'class' => 'bg-indigo-50 text-indigo-800 border-indigo-200'],
                            default => ['icon' => 'fa-route', 'label' => 'Transfers Included', 'class' => 'bg-slate-100 text-slate-700 border-slate-200']
                        };
                    ?>
                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold border <?= $tBadge['class'] ?>">
                        <i class="fa-solid <?= $tBadge['icon'] ?> text-[10px] mr-1"></i>
                        <?= htmlspecialchars($tBadge['label']) ?>
                    </span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        <i class="fa-solid fa-location-dot text-[10px] text-brand-600 mr-1"></i>
                        <?= htmlspecialchars($package['state']) ?>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center space-x-1">
                        <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
                        <span><?= $package['rating'] ?></span>
                        <span class="text-slate-400 font-normal">(<?= number_format($package['reviews_count']) ?> reviews)</span>
                    </span>
                </div>

                <!-- Main Package Heading -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    <?= htmlspecialchars($package['title']) ?>
                </h1>

                <!-- Route Highlights -->
                <p class="text-xs sm:text-sm text-slate-500 flex items-center space-x-2 font-medium">
                    <i class="fa-solid fa-route text-brand-600"></i>
                    <span>Route: <strong class="text-slate-700"><?= htmlspecialchars($package['destination']) ?></strong></span>
                </p>
            </div>

            <!-- Price Pill on Mobile / Tablet -->
            <div class="flex items-center space-x-4 shrink-0 lg:text-right">
                <div>
                    <span class="text-xs text-slate-400 block line-through">₹<?= number_format($package['original_price']) ?></span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        ₹<?= number_format($package['price']) ?>
                    </div>
                    <span class="text-[11px] text-slate-500 block">per person &bull; twin sharing</span>
                </div>
                <a href="#bookingSidebar" class="lg:hidden px-5 py-2.5 rounded-full bg-brand-600 text-white font-bold text-xs uppercase tracking-wider">
                    Book Now
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ==========================================
     MAGAZINE PHOTO GALLERY (1 Big Left + 4 Right)
=========================================== -->
<section class="w-full bg-white pb-8 px-4 sm:px-8 xl:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 rounded-3xl overflow-hidden border border-slate-200 bg-slate-100 h-[260px] sm:h-[340px] md:h-[480px]">
            
            <!-- Large Main Photo (Cols 1 & 2) -->
            <div class="md:col-span-2 h-full relative overflow-hidden group">
                <img src="<?= htmlspecialchars($package['gallery'][0]) ?>" 
                     alt="<?= htmlspecialchars($package['title']) ?>" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-4 left-4 px-3 py-1 rounded-full text-xs font-bold bg-slate-900/80 backdrop-blur-md text-white border border-white/20">
                    <i class="fa-solid fa-camera mr-1 text-teal-300"></i> Dal Lake Shikara Experience
                </span>
            </div>

            <!-- Right 4 Photos (Cols 3 & 4) -->
            <div class="hidden md:grid md:col-span-2 grid-cols-2 gap-3 h-full">
                <?php for ($i = 1; $i < 5; $i++): ?>
                    <div class="h-[233px] relative overflow-hidden group">
                        <img src="<?= htmlspecialchars($package['gallery'][$i]) ?>" 
                             alt="Tour highlight <?= $i ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                <?php endfor; ?>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     IN-PAGE TAB NAVIGATION STRIP (Smooth Scroll)
=========================================== -->
<div class="w-full bg-white border-y border-slate-200 sticky top-20 md:top-24 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 xl:px-12">
        <nav class="flex items-center space-x-6 overflow-x-auto no-scrollbar py-3 text-xs font-bold text-slate-600">
            <a href="#overviewSec" class="hover:text-brand-600 transition shrink-0">Overview</a>
            <a href="#highlightsSec" class="hover:text-brand-600 transition shrink-0">Key Highlights</a>
            <a href="#itinerarySec" class="hover:text-brand-600 transition shrink-0">Day-Wise Itinerary</a>
            <a href="#inclusionsSec" class="hover:text-brand-600 transition shrink-0">Inclusions &amp; Exclusions</a>
            <a href="#staysSec" class="hover:text-brand-600 transition shrink-0">Stays &amp; Hotels</a>
            <a href="#policySec" class="hover:text-brand-600 transition shrink-0">Cancellation Policy</a>
        </nav>
    </div>
</div>

<!-- ==========================================
     MAIN TWO-COLUMN DETAILS & BOOKING SECTION
=========================================== -->
<main class="w-full py-10 px-4 sm:px-8 xl:px-12 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- ==========================================
                 LEFT COLUMN: EXTENSIVE CONTENT
            =========================================== -->
            <div class="flex-1 w-full space-y-8">

                <!-- 1. OVERVIEW & EXPERIENCE DESCRIPTION -->
                <div id="overviewSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-4">
                    <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                        <i class="fa-solid fa-sparkles"></i>
                        <span>Tour Overview</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Experience the Magic of Paradise on Earth
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        <?= htmlspecialchars($package['subtitle']) ?> From serene morning Kehwa tea overlooking the misty waters of Dal Lake to ascending into the alpine snowfields of Apharwat Peak via the world's second-highest operating cable car in Gulmarg, this itinerary has been meticulously curated for families, honeymoon couples, and passionate explorers.
                    </p>

                    <!-- Quick Guarantee Pills -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                            <i class="fa-solid fa-shield-check text-brand-600 text-sm"></i>
                            <h4 class="text-xs font-bold text-slate-900">100% Verified</h4>
                            <p class="text-[10px] text-slate-500">Government Certified</p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                            <i class="fa-solid fa-bolt text-brand-600 text-sm"></i>
                            <h4 class="text-xs font-bold text-slate-900">Token Advance</h4>
                            <p class="text-[10px] text-slate-500">Lock for ₹<?= number_format($package['token_advance']) ?></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                            <i class="fa-solid fa-user-tie text-brand-600 text-sm"></i>
                            <h4 class="text-xs font-bold text-slate-900">Private Cab</h4>
                            <p class="text-[10px] text-slate-500">Dedicated Chauffeur</p>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-center space-y-1">
                            <i class="fa-solid fa-headset text-brand-600 text-sm"></i>
                            <h4 class="text-xs font-bold text-slate-900">24/7 Concierge</h4>
                            <p class="text-[10px] text-slate-500">Live Support On-Ground</p>
                        </div>
                    </div>
                </div>

                <!-- 2. KEY HIGHLIGHTS GRID -->
                <div id="highlightsSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-5">
                    <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                        <i class="fa-solid fa-star"></i>
                        <span>Package Highlights</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        What Makes This Circuit Extraordinary
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($package['highlights'] as $hl): ?>
                            <div class="flex items-start space-x-3.5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-500 transition">
                                <span class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center shrink-0">
                                    <i class="<?= $hl['icon'] ?> text-sm"></i>
                                </span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900"><?= htmlspecialchars($hl['title']) ?></h4>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed"><?= htmlspecialchars($hl['desc']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3. DAY-BY-DAY ITINERARY TIMELINE -->
                <div id="itinerarySec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Day-Wise Plan</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                                Detailed Travel Schedule
                            </h2>
                        </div>
                        <span class="rounded-full bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1">
                            <?= count($package['itinerary']) ?> Days Detailed
                        </span>
                    </div>

                    <!-- Timeline List -->
                    <div class="space-y-4 relative before:absolute before:inset-0 before:left-5 before:w-0.5 before:bg-slate-200">
                        <?php foreach ($package['itinerary'] as $idx => $day): ?>
                            <div class="relative flex items-start space-x-4 group">
                                <!-- Day Badge Circle -->
                                <span class="w-10 h-10 rounded-full bg-brand-600 text-white font-black text-xs flex items-center justify-center shrink-0 z-10 border-4 border-white">
                                    <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?>
                                </span>

                                <!-- Content Card -->
                                <div class="flex-1 bg-slate-50 hover:bg-white rounded-2xl border border-slate-200 p-5 transition space-y-3">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-200">
                                            <?= htmlspecialchars($day['day']) ?>
                                        </span>
                                        <span class="text-[11px] font-semibold text-slate-500 flex items-center space-x-1">
                                            <i class="fa-solid fa-hotel text-[10px] text-slate-400"></i>
                                            <span><?= htmlspecialchars($day['stay']) ?></span>
                                        </span>
                                    </div>

                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                                        <?= htmlspecialchars($day['title']) ?>
                                    </h3>

                                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                        <?= htmlspecialchars($day['desc']) ?>
                                    </p>

                                    <div class="pt-2 border-t border-slate-200/60 flex items-center space-x-2 text-[11px] text-emerald-700 font-semibold">
                                        <i class="fa-solid fa-utensils text-[10px]"></i>
                                        <span><?= htmlspecialchars($day['meals']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 4. INCLUSIONS & EXCLUSIONS -->
                <div id="inclusionsSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span>Package Policy</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Inclusions &amp; Exclusions
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- What's Included -->
                        <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200 space-y-3">
                            <h3 class="text-sm font-bold text-emerald-900 flex items-center space-x-2">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                <span>What's Included in This Package</span>
                            </h3>

                            <ul class="space-y-2.5">
                                <?php foreach ($package['inclusions'] as $inc): ?>
                                    <li class="flex items-start space-x-2.5 text-xs text-slate-700 leading-snug">
                                        <i class="fa-solid fa-check text-emerald-600 text-xs shrink-0 mt-0.5"></i>
                                        <span><?= htmlspecialchars($inc) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- What's NOT Included -->
                        <div class="p-5 rounded-2xl bg-rose-50/40 border border-rose-200 space-y-3">
                            <h3 class="text-sm font-bold text-rose-900 flex items-center space-x-2">
                                <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                                <span>What's Excluded (Chargeable)</span>
                            </h3>

                            <ul class="space-y-2.5">
                                <?php foreach ($package['exclusions'] as $exc): ?>
                                    <li class="flex items-start space-x-2.5 text-xs text-slate-700 leading-snug">
                                        <i class="fa-solid fa-xmark text-rose-500 text-xs shrink-0 mt-0.5"></i>
                                        <span><?= htmlspecialchars($exc) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 5. STAYS & ACCOMMODATIONS INCLUDED -->
                <div id="staysSec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-5">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-hotel"></i>
                            <span>Handpicked Accommodations</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Where You Will Stay
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($package['stays'] as $stay): ?>
                            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col justify-between">
                                <div class="h-44 relative overflow-hidden">
                                    <img src="<?= htmlspecialchars($stay['image']) ?>" alt="<?= htmlspecialchars($stay['name']) ?>" class="w-full h-full object-cover">
                                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-900/80 text-white">
                                        <?= htmlspecialchars($stay['nights']) ?>
                                    </span>
                                </div>
                                <div class="p-4 space-y-1.5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="font-bold text-brand-700"><?= htmlspecialchars($stay['type']) ?></span>
                                            <span class="text-amber-600 font-bold"><i class="fa-solid fa-star text-[10px]"></i> <?= $stay['rating'] ?></span>
                                        </div>
                                        <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($stay['name']) ?></h4>
                                        <p class="text-xs text-slate-500 flex items-center space-x-1 mt-0.5">
                                            <i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i>
                                            <span><?= htmlspecialchars($stay['location']) ?></span>
                                        </p>
                                    </div>
                                    <div class="pt-2 text-[11px] text-slate-500 flex items-center space-x-2">
                                        <span class="px-2 py-0.5 rounded bg-slate-200/70 font-semibold">Free Wi-Fi</span>
                                        <span class="px-2 py-0.5 rounded bg-slate-200/70 font-semibold">Central Heating</span>
                                        <span class="px-2 py-0.5 rounded bg-slate-200/70 font-semibold">Breakfast</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 6. CANCELLATION & RESCHEDULE POLICY -->
                <div id="policySec" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 text-xs font-bold text-brand-600 uppercase tracking-wider">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Booking Peace of Mind</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Cancellation &amp; Date Change Policy
                        </h2>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <span class="font-semibold text-slate-800">15+ Days Before Travel</span>
                            <span class="font-bold text-emerald-700">100% Token Refund or Free Date Change</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <span class="font-semibold text-slate-800">7 to 14 Days Before Travel</span>
                            <span class="font-bold text-amber-700">80% Refund or ₹1,000 Reschedule Fee</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <span class="font-semibold text-slate-800">Under 7 Days Before Travel</span>
                            <span class="font-bold text-slate-700">50% Refund (Hotel Non-Refundable Cutoff)</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ==========================================
                 RIGHT COLUMN: STICKY BOOKING CARD
            =========================================== -->
            <aside id="bookingSidebar" class="w-full lg:w-96 shrink-0 lg:sticky lg:top-36 space-y-5">
                
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 space-y-5">
                    
                    <!-- Pricing Header -->
                    <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 block line-through">₹<?= number_format($package['original_price']) ?></span>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                ₹<?= number_format($package['price']) ?>
                            </div>
                            <span class="text-[11px] text-slate-500">per person (twin sharing)</span>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Save 22% Today
                        </span>
                    </div>

                    <!-- Token Advance Highlight Box -->
                    <div class="p-3.5 rounded-2xl bg-teal-50/70 border border-brand-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-brand-800 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-brand-600"></i>
                            <span>Lock Dates with Token Advance</span>
                        </span>
                        <p class="text-xs text-slate-600 leading-snug">
                            Pay only <strong class="text-brand-900">₹<?= number_format($package['token_advance']) ?> per person</strong> today to secure hotels & cab. Pay the balance on arrival!
                        </p>
                    </div>

                    <!-- Booking Form Calculator -->
                    <form id="packageBookingForm" class="space-y-4" onsubmit="event.preventDefault(); submitBooking();">
                        <!-- Travel Date -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Departure Date</label>
                            <div class="relative">
                                <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="date" 
                                       id="bookTravelDate"
                                       required
                                       value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-semibold text-slate-800">
                            </div>
                        </div>

                        <!-- Adults Counter -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-slate-700">Number of Adults</label>
                                <span class="text-[11px] text-slate-400">12+ years</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-200">
                                <button type="button" id="adultMinusBtn" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span id="adultCountDisplay" class="font-black text-slate-900 text-sm">2</span>
                                <button type="button" id="adultPlusBtn" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Traveler Contact -->
                        <div class="space-y-3 pt-2 border-t border-slate-100">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name</label>
                                <input type="text" id="leadName" required placeholder="e.g. Vikrant Gupta" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">WhatsApp / Phone Number</label>
                                <input type="tel" id="leadPhone" required placeholder="+91 98765 43210" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 font-medium">
                            </div>
                        </div>

                        <!-- Price Breakdown Summary -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5 text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>₹<?= number_format($package['price']) ?> &times; <span id="adultSummaryCount">2</span> Adults</span>
                                <span class="font-mono font-bold text-slate-900" id="totalPriceDisplay">₹<?= number_format($package['price'] * 2) ?></span>
                            </div>
                            <div class="flex items-center justify-between text-slate-500 text-[11px]">
                                <span>Taxes &amp; Service Fees</span>
                                <span class="font-bold text-emerald-600">Included (Free)</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200 flex items-center justify-between font-bold text-slate-900">
                                <span>Token Advance to Pay Now:</span>
                                <span class="text-brand-700 font-mono text-sm" id="tokenAdvanceDisplay">₹<?= number_format($package['token_advance'] * 2) ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-1">
                            <button type="submit" class="w-full py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-lock text-xs"></i>
                                <span>Book with Token Advance</span>
                            </button>

                            <a href="https://wa.me/919876543210?text=<?= urlencode('Hi Orion Advent, I want to book ' . $package['title'] . ' for ' . $package['price'] . ' per person. Please share itinerary.') ?>" 
                               target="_blank" 
                               class="w-full py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center space-x-2">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Chat with Kashmir Expert</span>
                            </a>
                        </div>
                    </form>

                    <!-- Trust Accreditations -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Instant Voucher</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <i class="fa-solid fa-rotate-left text-brand-600"></i>
                            <span>Free Reschedule</span>
                        </span>
                    </div>

                </div>

                <!-- 24/7 Helpline Card -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200 space-y-2 text-center">
                    <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center mx-auto text-sm">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <h4 class="text-xs font-bold text-slate-900">Need Customization or Group Discount?</h4>
                    <p class="text-[11px] text-slate-500">Call our senior travel concierge available 24/7</p>
                    <a href="tel:+919876543210" class="inline-block text-xs font-black text-brand-700 hover:underline">
                        +91 98765 43210
                    </a>
                </div>

            </aside>

        </div>
    </div>
</main>

<!-- Interactive Price Calculator JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    let adults = 2;
    const basePrice = <?= $package['price'] ?>;
    const tokenPerPerson = <?= $package['token_advance'] ?>;

    const adultCountDisplay = document.getElementById('adultCountDisplay');
    const adultSummaryCount = document.getElementById('adultSummaryCount');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    const tokenAdvanceDisplay = document.getElementById('tokenAdvanceDisplay');
    const minusBtn = document.getElementById('adultMinusBtn');
    const plusBtn = document.getElementById('adultPlusBtn');

    function updateCalculator() {
        if (adultCountDisplay) adultCountDisplay.textContent = adults;
        if (adultSummaryCount) adultSummaryCount.textContent = adults;
        if (totalPriceDisplay) totalPriceDisplay.textContent = '₹' + (basePrice * adults).toLocaleString('en-IN');
        if (tokenAdvanceDisplay) tokenAdvanceDisplay.textContent = '₹' + (tokenPerPerson * adults).toLocaleString('en-IN');
    }

    if (plusBtn) {
        plusBtn.addEventListener('click', () => {
            if (adults < 12) {
                adults++;
                updateCalculator();
            }
        });
    }

    if (minusBtn) {
        minusBtn.addEventListener('click', () => {
            if (adults > 1) {
                adults--;
                updateCalculator();
            }
        });
    }
});

function submitBooking() {
    const name = document.getElementById('leadName')?.value || 'Guest';
    const phone = document.getElementById('leadPhone')?.value || '';
    const date = document.getElementById('bookTravelDate')?.value || '';
    const count = document.getElementById('adultCountDisplay')?.textContent || '2';
    const packageTitle = <?= json_encode($package['title']) ?>;
    const packageId = <?= json_encode($package['id'] ?? '') ?>;
    const tokenRaw = document.getElementById('tokenAdvanceDisplay')?.textContent.replace(/[^0-9]/g, '') || 0;
    const totalRaw = document.getElementById('totalPriceDisplay')?.textContent.replace(/[^0-9]/g, '') || 0;

    const btn = document.querySelector('#bookingSidebar form button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Securing Reservation...</span>';
    }

    const formData = new FormData();
    formData.append('package_id', packageId);
    formData.append('lead_name', name);
    formData.append('lead_phone', phone);
    formData.append('package_title', packageTitle);
    formData.append('travel_date', date);
    formData.append('adults_count', count);
    formData.append('token_amount', tokenRaw);
    formData.append('total_amount', totalRaw);

    fetch('api/book-package.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-lock text-xs"></i> <span>Book with Token Advance</span>';
        }
        if (data.login_required) {
            showLoginModalForBooking(packageTitle);
            return;
        }
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else if (data.success) {
            showPackageSuccessModal(data.booking_code, packageTitle, name, date, count, tokenRaw);
        } else {
            alert(data.message || 'Booking failed.');
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-lock text-xs"></i> <span>Book with Token Advance</span>';
        }
        alert('Booking placed successfully! Concierge will call your phone shortly.');
    });
}

function showLoginModalForBooking(title) {
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
                    <h3 class="text-xl font-black text-slate-900 font-space">Sign In to Lock Package</h3>
                    <p class="text-xs text-slate-500">Please sign in to your GuideFlux account to secure your seats and receive instant travel vouchers.</p>
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

function showPackageSuccessModal(code, title, name, date, count, token) {
    const modalHtml = `
        <div id="pkgModalOverlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full p-6 text-center space-y-4 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="space-y-1">
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                        Booking Code: ${code}
                    </span>
                    <h3 class="text-xl font-black text-slate-900">Tour Package Locked!</h3>
                    <p class="text-xs text-slate-500">Thank you <strong>${name}</strong>, your vacation seats have been locked successfully.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Holiday Circuit:</span>
                        <strong class="text-slate-900 truncate max-w-[200px]">${title}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Departure Date:</span>
                        <strong class="text-slate-800">${date}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Travelers:</span>
                        <strong class="text-slate-800">${count} Adult(s)</strong>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-1.5 font-bold text-slate-900">
                        <span>Advance Token Paid:</span>
                        <span class="text-emerald-700 font-black">₹${parseInt(token).toLocaleString('en-IN')}</span>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('pkgModalOverlay').remove(); window.location.href='index.php';" class="w-full py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                    Done &bull; Back to Home
                </button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}
</script>

<!-- Mobile Sticky Floating Bottom Booking Bar (Hidden on desktop, flat border, zero shadows) -->
<div class="lg:hidden fixed bottom-[60px] left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-3 flex items-center justify-between">
    <div>
        <span class="text-[10px] text-slate-400 block line-through">₹<?= number_format($package['original_price']) ?></span>
        <div class="flex items-baseline space-x-1">
            <span class="text-base font-black text-slate-900 leading-none">₹<?= number_format($package['price']) ?></span>
            <span class="text-[10px] text-slate-500 font-medium">/ person</span>
        </div>
    </div>
    <a href="#bookingSidebar" class="px-4 py-2 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition active:scale-95 flex items-center space-x-1.5 shrink-0">
        <span>Lock for ₹<?= number_format($package['token_advance']) ?></span>
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
</div>

<?php
require_once 'components/footer.php';
?>
