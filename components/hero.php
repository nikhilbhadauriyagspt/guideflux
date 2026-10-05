<?php
// Query active packages & hotels from DB for Hero Search Autocomplete
if (function_exists('getDBConnection')) {
    $pdoHero = getDBConnection();
} else {
    require_once __DIR__ . '/../config/db.php';
    $pdoHero = getDBConnection();
}

$heroDomPackages = [];
$heroIntlPackages = [];
$heroDbHotels = [];

if ($pdoHero) {
    try {
        $heroDomPackages = $pdoHero->query("SELECT id, title, location, badge, circuit_type FROM `packages` WHERE `category` = 'domestic' AND `status` = 'active' ORDER BY `id` DESC LIMIT 8")->fetchAll();
        $heroIntlPackages = $pdoHero->query("SELECT id, title, location, state_country, badge, circuit_type FROM `packages` WHERE `category` = 'international' AND `status` = 'active' ORDER BY `id` DESC LIMIT 8")->fetchAll();
        $heroDbHotels = $pdoHero->query("SELECT id, name, city, state, country, star_rating, property_type, badge FROM `hotels` WHERE `status` = 'active' ORDER BY `id` DESC LIMIT 8")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!-- ==========================================
     HERO SECTION COMPONENT (GuideFlux)
     - Top Bar: Left Tabs + Right Top Search Button
     - Light, Airy, Ultra-Clean 4-Column Search Card
     - Real-Time Live Autocomplete & Vector Icons
=========================================== -->
<section class="relative pt-16 pb-24 md:pt-20 md:pb-32 px-4 sm:px-6 lg:px-8 xl:px-12">

    <!-- 1. Ambient Background Slider -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none select-none">
        <div class="hero-bg-slide absolute inset-0 bg-cover bg-center bg-fixed transition-opacity duration-1000 opacity-100"
            style="background-image: url('assets/images/banner/banner-05.webp'); background-attachment: fixed; background-size: cover; background-position: center;">
        </div>
        <div class="hero-bg-slide absolute inset-0 bg-cover bg-center bg-fixed transition-opacity duration-1000 opacity-0"
            style="background-image: url('assets/images/banner/banner-07.webp'); background-attachment: fixed; background-size: cover; background-position: center;">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#071f25]/60 via-black/40 to-[#06242c]/70"></div>
    </div>

    <!-- 2. Bottom Wave Divider -->
    <div class="absolute bottom-0 left-0 w-full z-10 pointer-events-none leading-none select-none overflow-hidden">
        <img src="assets/images/banner/banner-bg-07.webp" alt="Wave Divider" class="w-full h-auto min-h-[35px] max-h-[85px] object-cover object-top translate-y-1">
    </div>

    <!-- 3. Heading Block -->
    <div class="max-w-4xl mx-auto text-center space-y-3 mb-12 sm:mb-14 relative z-20">
        <div class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md text-teal-300 border border-white/20">
            <i class="fa-solid fa-compass text-teal-300"></i>
            <span>Verified Travel Partner</span>
            <span class="text-teal-400/50">&bull;</span>
            <span class="text-amber-300 font-bold">100% Best Rate Guarantee</span>
        </div>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
            <span class="text-white">Discover Exceptional </span><br class="hidden sm:inline">
            <span id="dynamicHeroWord" class="inline-block bg-gradient-to-r from-teal-300 via-emerald-300 to-amber-300 bg-clip-text text-transparent transition-all duration-300">Holiday Packages</span>
            <span class="text-white"> & Tours</span>
        </h1>
    </div>

    <!-- 4. Clean Lightweight Search Engine Container -->
    <div class="max-w-6xl mx-auto relative z-30 pt-6 sm:pt-7">

        <!-- MAIN SEARCH CARD (Ultra-Clean, Flat, Airy 4-Column Layout with Floating Top Bar) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-lg p-2.5 sm:p-3 pt-7 sm:pt-8 text-left relative">

            <!-- FLOATING TOP BAR: Half-Outside / Half-Inside (-top-5 sm:-top-6) -->
            <div class="absolute -top-5 sm:-top-6 left-3 sm:left-6 right-3 sm:right-6 flex items-center justify-between z-40 pointer-events-auto">
                
                <!-- Left: Clean Pill Tabs (Overlapping top card border, Zero Shadow, Zero Border) -->
                <div id="heroTabs" class="inline-flex items-center p-1 bg-white rounded-2xl sm:rounded-full gap-1 overflow-x-auto scrollbar-none">
                    
                    <!-- 1. Domestic Packages (Active) -->
                    <button type="button" data-target="panel-domestic" class="tab-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-full text-xs sm:text-sm font-bold bg-brand-600 text-white transition-all whitespace-nowrap">
                        <i class="fa-solid fa-map-location-dot text-xs sm:text-sm"></i>
                        <span>Domestic Packages</span>
                    </button>

                    <!-- 2. International Packages -->
                    <button type="button" data-target="panel-international" class="tab-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-full text-xs sm:text-sm font-semibold text-slate-700 hover:text-brand-700 hover:bg-slate-100 transition-all whitespace-nowrap">
                        <i class="fa-solid fa-globe text-xs sm:text-sm text-brand-600"></i>
                        <span>International Tours</span>
                    </button>

                    <!-- 3. Flights -->
                    <button type="button" data-target="panel-flights" class="tab-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-full text-xs sm:text-sm font-semibold text-slate-700 hover:text-brand-700 hover:bg-slate-100 transition-all whitespace-nowrap">
                        <i class="fa-solid fa-plane-departure text-xs sm:text-sm text-brand-600"></i>
                        <span>Flights</span>
                    </button>

                    <!-- 4. Hotels -->
                    <button type="button" data-target="panel-hotels" class="tab-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-full text-xs sm:text-sm font-semibold text-slate-700 hover:text-brand-700 hover:bg-slate-100 transition-all whitespace-nowrap">
                        <i class="fa-solid fa-hotel text-xs sm:text-sm text-brand-600"></i>
                        <span>Hotels</span>
                    </button>
                </div>

                <!-- Right: Floating Search Action Button (Zero Shadow) -->
                <div class="hidden sm:block shrink-0">
                    <button type="button" id="topSearchBtn" onclick="document.querySelector('.tab-panel:not(.hidden) form')?.submit()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm tracking-wide transition-all cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span id="topSearchBtnText">Search Domestic Packages</span>
                    </button>
                </div>
            </div>

            <!-- ==========================================
                 PANEL 1: DOMESTIC PACKAGES
            =========================================== -->
            <div id="panel-domestic" class="tab-panel">
                <form action="search.php" method="GET">
                    <input type="hidden" name="type" value="domestic">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">

                        <!-- Column 1: Destination Live Typeahead -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors">
                            <label for="domesticDestInput" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Destination
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="destination"
                                       id="domesticDestInput"
                                       autocomplete="off"
                                       placeholder="Where do you want to go?" 
                                       value=""
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <!-- Live Autocomplete Suggestion Dropdown -->
                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span>Suggested Destinations</span>
                                    <span class="text-teal-600 font-bold">Select to Apply</span>
                                </div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroDomPackages)): ?>
                                        <?php foreach ($heroDomPackages as $dp): ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="<?= htmlspecialchars($dp['title']) ?>" data-sub="<?= htmlspecialchars($dp['location']) ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-map-location-dot text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($dp['title']) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($dp['location']) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 shrink-0"><?= htmlspecialchars($dp['badge'] ?: 'Tour') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Kashmir Paradise" data-sub="Srinagar, Gulmarg, Pahalgam">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-mountain-sun text-xs"></i></div>
                                            <div class="truncate">
                                                <span class="block text-xs font-bold text-slate-800">Kashmir Paradise</span>
                                                <span class="block text-[10px] text-slate-400">Srinagar, Gulmarg, Pahalgam</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 shrink-0">Trending</span>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Goa Beachside Escape" data-sub="Calangute, Baga, South Goa">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-umbrella-beach text-xs"></i></div>
                                            <div class="truncate">
                                                <span class="block text-xs font-bold text-slate-800">Goa Beachside Escape</span>
                                                <span class="block text-[10px] text-slate-400">Calangute, Baga, South Goa</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 shrink-0">Popular</span>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Kerala Backwaters" data-sub="Munnar, Alleppey Houseboats">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-water text-xs"></i></div>
                                            <div class="truncate">
                                                <span class="block text-xs font-bold text-slate-800">Kerala Backwaters</span>
                                                <span class="block text-[10px] text-slate-400">Munnar, Alleppey Houseboats</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 shrink-0">Backwaters</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Holiday Theme (Replaced heart icon with wand/magic sparkles) -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Theme / Style
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="theme"
                                       placeholder="Select holiday theme" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Select Experience Theme</div>
                                <div class="space-y-1 max-h-60 overflow-y-auto">
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Honeymoon & Romantic" data-sub="Candlelight & scenic views">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center"><i class="fa-solid fa-champagne-glasses text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Honeymoon & Romantic</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Family Vacation" data-sub="Sightseeing & kid friendly">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><i class="fa-solid fa-people-roof text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Family Vacation</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Adventure & Trekking" data-sub="River rafting & safari">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-person-hiking text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Adventure & Trekking</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Pilgrimage & Spiritual" data-sub="Temples, darshan & heritage">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fa-solid fa-om text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Pilgrimage & Spiritual</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Travel Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travel Date
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="date"
                                       placeholder="Choose travel date" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Calendar Date</span>
                                    <span class="text-teal-600 font-bold text-[11px]">Instant Confirmation</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Quick Shortcuts:</div>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" class="quick-date-btn text-left p-2 border border-slate-200 bg-slate-50 hover:border-brand-500 transition" data-date="This Weekend" data-sub="Saturday Departure">
                                        <span class="block text-xs font-bold text-slate-800">This Weekend</span>
                                        <span class="block text-[10px] text-slate-400">Quick 2-3 days</span>
                                    </button>
                                    <button type="button" class="quick-date-btn text-left p-2 border border-slate-200 bg-slate-50 hover:border-brand-500 transition" data-date="Next Month" data-sub="Flexible Dates">
                                        <span class="block text-xs font-bold text-slate-800">Next Month</span>
                                        <span class="block text-[10px] text-slate-400">Summer break</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Column 4: Guests & Duration -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travelers & Stay
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="guests"
                                       placeholder="Guests & duration" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="mb-3">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Trip Duration (Nights / Days)</div>
                                    <div class="grid grid-cols-4 gap-1.5 text-center text-xs">
                                        <button type="button" data-duration="3N / 4D" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">3N/4D</button>
                                        <button type="button" data-duration="5N / 6D" class="duration-chip active py-1.5 font-bold bg-brand-600 text-white transition">5N/6D</button>
                                        <button type="button" data-duration="7N / 8D" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">7N/8D</button>
                                        <button type="button" data-duration="9N / 10D+" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">9N+</button>
                                    </div>
                                </div>

                                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="domesticAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="domesticAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="domesticAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Rooms</span><span class="block text-[10px] text-slate-400">Hotel rooms</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="domesticRoomsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="domesticRoomsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="domesticRoomsCount" data-max="5" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" class="dropdown-apply-btn w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Search CTA on Mobile -->
                    <div class="sm:hidden pt-2">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search Domestic Packages</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ==========================================
                 PANEL 2: INTERNATIONAL PACKAGES
            =========================================== -->
            <div id="panel-international" class="tab-panel hidden">
                <form action="search.php" method="GET">
                    <input type="hidden" name="type" value="international">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">
                        
                        <!-- Destination -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Destination
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-globe text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="destination"
                                       autocomplete="off"
                                       placeholder="Where in the world?" 
                                       value=""
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Global Destinations</div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroIntlPackages)): ?>
                                        <?php foreach ($heroIntlPackages as $ip): ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="<?= htmlspecialchars($ip['title']) ?>" data-sub="<?= htmlspecialchars($ip['state_country'] ?: $ip['location']) ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-globe text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($ip['title']) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($ip['state_country'] ?: $ip['location']) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 shrink-0"><?= htmlspecialchars($ip['badge'] ?: 'Top Tour') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Dubai, UAE" data-sub="Burj Khalifa, Desert Safari, Marina">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-city text-xs"></i></div>
                                            <div>
                                                <span class="block text-xs font-bold text-slate-800">Dubai, UAE</span>
                                                <span class="block text-[10px] text-slate-400">Burj Khalifa, Desert Safari</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5">Top Seller</span>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Bali, Indonesia" data-sub="Ubud, Seminyak, Islands">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-umbrella-beach text-xs"></i></div>
                                            <div>
                                                <span class="block text-xs font-bold text-slate-800">Bali, Indonesia</span>
                                                <span class="block text-[10px] text-slate-400">Ubud, Seminyak, Islands</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5">Honeymoon</span>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Maldives Luxury" data-sub="Overwater Villa, Male">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-water text-xs"></i></div>
                                            <div>
                                                <span class="block text-xs font-bold text-slate-800">Maldives Luxury</span>
                                                <span class="block text-[10px] text-slate-400">Overwater Villa, Male</span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5">Luxury</span>
                                    </div>
                            </div>
                        </div>

                        <!-- Theme (Replaced heart icon with wand sparkles) -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Theme / Style
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="theme"
                                       placeholder="Select holiday theme" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Select Experience</div>
                                <div class="space-y-1 max-h-60 overflow-y-auto">
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Honeymoon Special" data-sub="Overwater villas & romantic cruises">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-champagne-glasses text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Honeymoon Special</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Family & Leisure" data-sub="Universal studios & attractions">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-people-roof text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Family & Leisure</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Luxury Getaway" data-sub="5-Star luxury & private transfers">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-crown text-xs"></i></div>
                                            <span class="text-xs font-bold text-slate-800">Luxury Getaway</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travel Date
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="date"
                                       placeholder="Choose travel date" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Calendar Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                </div>
                            </div>
                        </div>

                        <!-- Guests -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travelers & Stay
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="guests"
                                       placeholder="Guests & stay" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="intlAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="intlAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="intlAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" class="dropdown-apply-btn w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:hidden pt-2">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search International Tours</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ==========================================
                 PANEL 3: FLIGHTS (With Interactive Airport Swap Button)
            =========================================== -->
            <div id="panel-flights" class="tab-panel hidden">
                <form action="search.php" method="GET">
                    <input type="hidden" name="type" value="flights">

                    <!-- Flight Trip Type Toggles -->
                    <div class="flex items-center gap-5 pb-2.5 px-1 text-xs font-bold text-slate-700">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="flightType" value="oneway" checked class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>One Way</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="flightType" value="roundtrip" class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>Round Trip</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="flightType" value="multicity" class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>Multi-City</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100 relative">
                        
                        <!-- From Airport -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">From Airport</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="from" 
                                       id="flightFromInput"
                                       autocomplete="off"
                                       placeholder="Origin city or airport..." 
                                       value="" 
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Select Origin Airport</div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Delhi (DEL) - Indira Gandhi Intl" data-sub="DEL · New Delhi, India">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded">DEL</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">New Delhi</span><span class="block text-[10px] text-slate-400">Indira Gandhi Intl</span></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Mumbai (BOM) - Chhatrapati Shivaji" data-sub="BOM · Mumbai, India">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded">BOM</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">Mumbai</span><span class="block text-[10px] text-slate-400">Chhatrapati Shivaji Intl</span></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Bengaluru (BLR) - Kempegowda Intl" data-sub="BLR · Bangalore, India">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded">BLR</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">Bengaluru</span><span class="block text-[10px] text-slate-400">Kempegowda Intl</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Direction Swap Button (Center Floating Between From & To) -->
                        <div class="hidden lg:flex absolute left-[25%] -translate-x-1/2 top-1/2 -translate-y-1/2 z-20">
                            <button type="button" id="flightSwapBtn" title="Swap Origin & Destination" class="w-7 h-7 rounded-full bg-white border border-slate-200 hover:border-brand-500 shadow-sm hover:shadow text-slate-500 hover:text-brand-600 flex items-center justify-center text-xs transition cursor-pointer active:rotate-180">
                                <i class="fa-solid fa-right-left"></i>
                            </button>
                        </div>

                        <!-- To Airport -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">To Airport</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plane-arrival text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="to" 
                                       id="flightToInput"
                                       autocomplete="off"
                                       placeholder="Destination airport..." 
                                       value="" 
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Select Destination Airport</div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Dubai (DXB) - International" data-sub="DXB · Dubai, UAE">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-amber-50 text-amber-800 px-1.5 py-0.5 rounded">DXB</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">Dubai</span><span class="block text-[10px] text-slate-400">Dubai International</span></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Goa (GOI) - Dabolim / Mopa" data-sub="GOX · Goa, India">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded">GOI</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">Goa</span><span class="block text-[10px] text-slate-400">Dabolim / Manohar Intl</span></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Singapore (SIN) - Changi Airport" data-sub="SIN · Singapore">
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-xs bg-teal-50 text-teal-800 px-1.5 py-0.5 rounded">SIN</span>
                                            <div class="truncate"><span class="block text-xs font-bold text-slate-800">Singapore</span><span class="block text-[10px] text-slate-400">Changi International</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Departure Date (or Departure + Return when Roundtrip) -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label id="flightDateLabel" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Departure Date</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="flight_date" 
                                       id="flightDateInput"
                                       placeholder="Choose flight date..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Flight Date</span>
                                </div>
                                <div class="mb-3 space-y-2">
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Departure</label>
                                        <input type="date" id="flightDepDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                    </div>
                                    <div id="flightReturnDateBlock" class="hidden">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Return Date</label>
                                        <input type="date" id="flightRetDate" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Class & Travelers -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Cabin & Travelers</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="flight_class" 
                                       id="flightClassInput"
                                       placeholder="Cabin & travelers..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Cabin Class</div>
                                <div class="grid grid-cols-2 gap-1.5 text-xs mb-3">
                                    <button type="button" data-cabin="Economy" class="flight-cabin-chip active py-1.5 px-2 font-bold bg-brand-600 text-white transition text-center">Economy</button>
                                    <button type="button" data-cabin="Premium Economy" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center">Premium</button>
                                    <button type="button" data-cabin="Business" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center">Business</button>
                                    <button type="button" data-cabin="First Class" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center">First Class</button>
                                </div>
                                <div class="space-y-2 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="flightAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="flightAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="flightAdultsCount" data-max="9" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" id="flightApplyBtn" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- ==========================================
                 PANEL 4: HOTELS (Fully Interactive Check-In, Check-Out, Rooms & Guests)
            =========================================== -->
            <div id="panel-hotels" class="tab-panel hidden">
                <form action="search.php" method="GET">
                    <input type="hidden" name="type" value="hotels">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">
                        
                        <!-- Hotel City / Resort -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">City / Location</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-hotel text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="city" 
                                       id="hotelCityInput"
                                       autocomplete="off"
                                       placeholder="Where do you want to stay?" 
                                       value="" 
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">Popular Hotel Cities</div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroDbHotels)): ?>
                                        <?php foreach ($heroDbHotels as $hh): ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="<?= htmlspecialchars($hh['city'] . ', ' . ($hh['country'] ?: 'India')) ?>" data-sub="<?= htmlspecialchars($hh['name']) ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-hotel text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($hh['name']) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($hh['city'] . ', ' . ($hh['country'] ?: 'India')) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 shrink-0"><?= htmlspecialchars($hh['star_rating']) ?>★</span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Goa, India" data-sub="North & South Goa Beach Resorts">
                                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-umbrella-beach text-amber-600 text-xs shrink-0"></i><div><span class="block text-xs font-bold text-slate-800">Goa</span><span class="block text-[10px] text-slate-400">Resorts & beach villas</span></div></div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Jaipur, Rajasthan" data-sub="Heritage Palaces & Luxury Havelis">
                                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-landmark text-rose-600 text-xs shrink-0"></i><div><span class="block text-xs font-bold text-slate-800">Jaipur</span><span class="block text-[10px] text-slate-400">Heritage palaces & stays</span></div></div>
                                    </div>
                                    <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition" data-val="Dubai, UAE" data-sub="Downtown & Palm Jumeirah 5-Star">
                                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-city text-teal-600 text-xs shrink-0"></i><div><span class="block text-xs font-bold text-slate-800">Dubai</span><span class="block text-[10px] text-slate-400">Palm Jumeirah & Downtown</span></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Check-In Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Check-In</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="checkin" 
                                       id="hotelCheckinInput"
                                       placeholder="Select check-in..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Check-In Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" id="hotelCheckinDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                </div>
                            </div>
                        </div>

                        <!-- Check-Out Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Check-Out</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="checkout" 
                                       id="hotelCheckoutInput"
                                       placeholder="Select check-out..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Check-Out Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" id="hotelCheckoutDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold" min="2026-04-05">
                                </div>
                            </div>
                        </div>

                        <!-- Guests & Rooms -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Guests & Rooms</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="hotel_guests" 
                                       id="hotelGuestsInput"
                                       placeholder="Rooms & guests..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Rooms</span><span class="block text-[10px] text-slate-400">Total rooms</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="hotelRoomsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="hotelRoomsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="hotelRoomsCount" data-max="5" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="hotelAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">-</button>
                                            <span id="hotelAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="hotelAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" id="hotelApplyBtn" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <!-- Quick Trending Shortcuts -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-5 text-xs text-slate-200 relative z-20">
            <span class="font-bold text-amber-300 flex items-center gap-1">
                <i class="fa-solid fa-fire text-amber-400"></i> Top Searches:
            </span>
            <a href="search.php?destination=Kashmir" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-mountain-sun text-[11px] text-teal-300"></i>
                <span>Kashmir (From Rs. 17,999)</span>
            </a>
            <a href="search.php?destination=Dubai" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-city text-[11px] text-teal-300"></i>
                <span>Dubai Luxury (25% Off)</span>
            </a>
            <a href="search.php?destination=Bali" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-umbrella-beach text-[11px] text-teal-300"></i>
                <span>Bali Island Bliss</span>
            </a>
        </div>

    </div>

</section>