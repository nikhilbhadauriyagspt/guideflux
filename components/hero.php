<?php
// Query active packages & hotels from DB for Hero Search Autocomplete
if (function_exists('getDBConnection')) {
    $pdoHero = getDBConnection();
} else {
    require_once __DIR__ . '/../config/db.php';
    $pdoHero = getDBConnection();
}

require_once __DIR__ . '/../includes/flights_service.php';

$heroDomPackages = [];
$heroIntlPackages = [];
$heroDbHotels = [];

if ($pdoHero) {
    try {
        $heroDomPackages = $pdoHero->query("SELECT id, title, location, badge, circuit_type FROM `packages` WHERE `category` = 'domestic' AND `status` = 'active' ORDER BY `id` DESC LIMIT 10")->fetchAll();
        $heroIntlPackages = $pdoHero->query("SELECT id, title, location, state_country, badge, circuit_type FROM `packages` WHERE `category` = 'international' AND `status` = 'active' ORDER BY `id` DESC LIMIT 10")->fetchAll();
        $heroDbHotels = $pdoHero->query("SELECT id, name, city, state, country, star_rating, property_type, badge FROM `hotels` WHERE `status` = 'active' ORDER BY `id` DESC LIMIT 10")->fetchAll();
    } catch (Exception $e) {}
}

$heroAirports = getFlightAirportsDirectory();
?>
<!-- ==========================================
     HERO SECTION COMPONENT (GuideFlux)
     - Responsive Tab Bar + Adaptive Search CTA
     - 100% Dynamic Database Driven Form Engine
     - Fully Working Forms Across All 4 Tabs
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
    <div class="max-w-4xl mx-auto text-center space-y-3 mb-10 sm:mb-12 relative z-20">
        <div class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md text-teal-300 border border-white/20">
            <i class="fa-solid fa-compass text-teal-300"></i>
            <span>Verified Travel Partner</span>
            <span class="text-teal-400/50">&bull;</span>
            <span class="text-amber-300 font-bold">100% Best Rate Guarantee</span>
        </div>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
            <span class="text-white">Discover Exceptional </span><br class="hidden sm:inline">
            <span id="dynamicHeroWord" class="inline-block bg-gradient-to-r from-teal-300 via-emerald-300 to-amber-300 bg-clip-text text-transparent transition-all duration-300">Holiday Packages</span>
            <span class="text-white"> &amp; Tours</span>
        </h1>
    </div>

    <!-- 4. Clean Lightweight Search Engine Container -->
    <div class="max-w-6xl mx-auto relative z-30 pt-4 sm:pt-6">

        <!-- MAIN SEARCH CARD -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl p-3 sm:p-4 pt-8 sm:pt-9 text-left relative">

            <!-- FLOATING TOP BAR -->
            <div class="absolute -top-5 sm:-top-6 left-2 sm:left-4 md:left-6 right-2 sm:right-4 md:right-6 flex items-center justify-between gap-2 z-40 pointer-events-auto">
                
                <!-- Left: Clean Pill Tabs -->
                <div id="heroTabs" class="inline-flex items-center p-1 bg-white rounded-2xl sm:rounded-full gap-1 overflow-x-auto max-w-[calc(100%-80px)] sm:max-w-none shadow-md border border-slate-200/90 scrollbar-none">
                    
                    <!-- 1. Domestic Packages -->
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

                <!-- Right: Desktop Floating Search Button -->
                <div class="hidden sm:block shrink-0">
                    <button type="button" id="topSearchBtn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs sm:text-sm tracking-wide transition-all shadow-md cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span id="topSearchBtnText">Search Domestic Packages</span>
                    </button>
                </div>
            </div>

            <!-- ==========================================
                 PANEL 1: DOMESTIC PACKAGES
            =========================================== -->
            <div id="panel-domestic" class="tab-panel">
                <form action="search.php" method="GET" class="w-full">
                    <input type="hidden" name="type" value="domestic">
                    <input type="hidden" name="category" value="domestic">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">

                        <!-- Column 1: Destination Live Typeahead -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors rounded-xl lg:rounded-none">
                            <label for="domesticDestInput" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Destination
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="query"
                                       id="domesticDestInput"
                                       autocomplete="off"
                                       placeholder="Where in India do you want to go?" 
                                       value=""
                                       data-category="domestic"
                                       data-type="package"
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <!-- Live Autocomplete Suggestion Dropdown -->
                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span class="dropdown-header-title">Indian Destinations &amp; Circuits</span>
                                    <span class="text-teal-600 font-bold dropdown-status-indicator inline-flex items-center gap-1">
                                        <i class="fa-solid fa-bolt text-[10px]"></i> Live API
                                    </span>
                                </div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroDomPackages)): ?>
                                        <?php foreach ($heroDomPackages as $dp): 
                                            $locParts = explode(',', $dp['location']);
                                            $cleanLoc = trim($locParts[0] ?? $dp['title']);
                                        ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" data-val="<?= htmlspecialchars($cleanLoc) ?>" data-sub="<?= htmlspecialchars($dp['location'] . ' • India') ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-map-location-dot text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($cleanLoc) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($dp['location'] . ' • ' . $dp['title']) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 shrink-0 rounded"><?= htmlspecialchars($dp['badge'] ?: 'Active Package') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-4 text-center text-xs text-slate-400">Type any Indian city or state to search live API</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Travel Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
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

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Calendar Date</span>
                                    <span class="text-teal-600 font-bold text-[11px]">Instant Confirmation</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Quick Shortcuts:</div>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" class="quick-date-btn text-left p-2 border border-slate-200 bg-slate-50 hover:border-brand-500 transition rounded-xl" data-date="This Weekend" data-sub="Saturday Departure">
                                        <span class="block text-xs font-bold text-slate-800">This Weekend</span>
                                        <span class="block text-[10px] text-slate-400">Quick 2-3 days</span>
                                    </button>
                                    <button type="button" class="quick-date-btn text-left p-2 border border-slate-200 bg-slate-50 hover:border-brand-500 transition rounded-xl" data-date="Next Month" data-sub="Flexible Dates">
                                        <span class="block text-xs font-bold text-slate-800">Next Month</span>
                                        <span class="block text-[10px] text-slate-400">Summer break</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Column 4: Guests & Duration -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travelers &amp; Stay
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="guests"
                                       placeholder="Guests &amp; duration" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="mb-3">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Trip Duration (Nights / Days)</div>
                                    <div class="grid grid-cols-4 gap-1.5 text-center text-xs">
                                        <button type="button" data-duration="3N / 4D" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition rounded-lg">3N/4D</button>
                                        <button type="button" data-duration="5N / 6D" class="duration-chip active py-1.5 font-bold bg-brand-600 text-white transition rounded-lg">5N/6D</button>
                                        <button type="button" data-duration="7N / 8D" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition rounded-lg">7N/8D</button>
                                        <button type="button" data-duration="9N / 10D+" class="duration-chip py-1.5 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition rounded-lg">9N+</button>
                                    </div>
                                </div>

                                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="domesticAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="domesticAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="domesticAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Rooms</span><span class="block text-[10px] text-slate-400">Hotel rooms</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="domesticRoomsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="domesticRoomsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="domesticRoomsCount" data-max="5" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" class="dropdown-apply-btn w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition rounded-xl">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Search CTA on Mobile -->
                    <div class="sm:hidden pt-3">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer rounded-xl">
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
                <form action="search.php" method="GET" class="w-full">
                    <input type="hidden" name="type" value="international">
                    <input type="hidden" name="category" value="international">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">
                        
                        <!-- Column 1: Destination -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors rounded-xl lg:rounded-none">
                            <label for="intlDestInput" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Destination
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-globe text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="query"
                                       id="intlDestInput"
                                       autocomplete="off"
                                       placeholder="Where in the world?" 
                                       value=""
                                       data-category="international"
                                       data-type="package"
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span class="dropdown-header-title">Global Destinations</span>
                                    <span class="text-teal-600 font-bold dropdown-status-indicator inline-flex items-center gap-1">
                                        <i class="fa-solid fa-bolt text-[10px]"></i> Live API
                                    </span>
                                </div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroIntlPackages)): ?>
                                        <?php foreach ($heroIntlPackages as $ip): 
                                            $locParts = explode(',', $ip['location']);
                                            $cleanLoc = trim($locParts[0] ?? $ip['title']);
                                        ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" data-val="<?= htmlspecialchars($cleanLoc) ?>" data-sub="<?= htmlspecialchars(($ip['state_country'] ?: $ip['location']) . ' • ' . $ip['title']) ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-globe text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($cleanLoc) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($ip['state_country'] ?: $ip['location']) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 shrink-0 rounded"><?= htmlspecialchars($ip['badge'] ?: 'Top Tour') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-4 text-center text-xs text-slate-400">Type any world destination to search live API</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
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

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Calendar Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Column 4: Guests -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">
                                Travelers &amp; Stay
                            </label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="guests"
                                       placeholder="Guests &amp; stay" 
                                       value=""
                                       readonly
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="intlAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="intlAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="intlAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" class="dropdown-apply-btn w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition rounded-xl">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:hidden pt-3">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer rounded-xl">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search International Tours</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ==========================================
                 PANEL 3: FLIGHTS (Dedicated Flight Engine)
            =========================================== -->
            <div id="panel-flights" class="tab-panel hidden">
                <form action="flights.php" method="GET" class="w-full">
                    <!-- Flight Trip Type Toggles -->
                    <div class="flex items-center gap-5 pb-2.5 px-1 text-xs font-bold text-slate-700">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="trip" value="oneway" checked class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>One Way</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="trip" value="roundtrip" class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>Round Trip</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="trip" value="multicity" class="flight-type-radio text-brand-600 focus:ring-brand-500 accent-brand-600">
                            <span>Multi-City</span>
                        </label>
                    </div>

                    <!-- Hidden Return Date for Round-Trip Form Submission -->
                    <input type="hidden" name="return_date" id="flightReturnDateHidden" value="">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100 relative">
                        
                        <!-- From Airport -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">From Airport</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plane-departure text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="from" 
                                       id="flightFromInput"
                                       autocomplete="off"
                                       placeholder="Search departure airport (e.g. DEL, BOM, Hindon)..." 
                                       value="" 
                                       class="flight-airport-typeahead w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span>Select Departure Airport</span>
                                    <span class="text-sky-600 font-bold">Airport Name</span>
                                </div>
                                <div class="suggestion-list max-h-64 overflow-y-auto space-y-1">
                                    <?php foreach ($heroAirports as $code => $ap): 
                                        $fullAirportLabel = $ap['name'] . ' (' . $code . ')';
                                        $searchKeywords = strtolower($code . ' ' . $ap['name'] . ' ' . $ap['city'] . ' ' . ($ap['state'] ?? ''));
                                    ?>
                                        <div class="dropdown-select-item flight-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" 
                                             data-val="<?= htmlspecialchars($fullAirportLabel) ?>" 
                                             data-code="<?= $code ?>"
                                             data-name="<?= htmlspecialchars($ap['name']) ?>"
                                             data-sub="<?= htmlspecialchars($ap['city'] . ' • ' . $ap['state']) ?>"
                                             data-search="<?= htmlspecialchars($searchKeywords) ?>">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                                    <i class="fa-solid fa-plane-departure text-xs"></i>
                                                </div>
                                                <div class="truncate">
                                                    <span class="block text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($ap['name']) ?></span>
                                                    <span class="block text-[10px] text-slate-400 truncate"><?= htmlspecialchars($ap['city'] . ' • ' . $ap['state']) ?></span>
                                                </div>
                                            </div>
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-2 py-0.5 rounded shrink-0 border border-slate-200"><?= $code ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Direction Swap Button -->
                        <div class="hidden lg:flex absolute left-[25%] -translate-x-1/2 top-1/2 -translate-y-1/2 z-20">
                            <button type="button" id="flightSwapBtn" title="Swap Origin & Destination" class="w-7 h-7 rounded-full bg-white border border-slate-200 hover:border-brand-500 shadow-md hover:shadow text-slate-500 hover:text-brand-600 flex items-center justify-center text-xs transition cursor-pointer active:rotate-180">
                                <i class="fa-solid fa-right-left"></i>
                            </button>
                        </div>

                        <!-- To Airport -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">To Airport</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plane-arrival text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="to" 
                                       id="flightToInput"
                                       autocomplete="off"
                                       placeholder="Search destination airport (e.g. BOM, GOX, Manohar)..." 
                                       value="" 
                                       class="flight-airport-typeahead w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span>Select Destination Airport</span>
                                    <span class="text-teal-600 font-bold">Airport Name</span>
                                </div>
                                <div class="suggestion-list max-h-64 overflow-y-auto space-y-1">
                                    <?php foreach ($heroAirports as $code => $ap): 
                                        $fullAirportLabel = $ap['name'] . ' (' . $code . ')';
                                        $searchKeywords = strtolower($code . ' ' . $ap['name'] . ' ' . $ap['city'] . ' ' . ($ap['state'] ?? ''));
                                    ?>
                                        <div class="dropdown-select-item flight-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" 
                                             data-val="<?= htmlspecialchars($fullAirportLabel) ?>" 
                                             data-code="<?= $code ?>"
                                             data-name="<?= htmlspecialchars($ap['name']) ?>"
                                             data-sub="<?= htmlspecialchars($ap['city'] . ' • ' . $ap['state']) ?>"
                                             data-search="<?= htmlspecialchars($searchKeywords) ?>">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                                    <i class="fa-solid fa-plane-arrival text-xs"></i>
                                                </div>
                                                <div class="truncate">
                                                    <span class="block text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($ap['name']) ?></span>
                                                    <span class="block text-[10px] text-slate-400 truncate"><?= htmlspecialchars($ap['city'] . ' • ' . $ap['state']) ?></span>
                                                </div>
                                            </div>
                                            <span class="font-mono font-bold text-xs bg-slate-100 text-slate-800 px-2 py-0.5 rounded shrink-0 border border-slate-200"><?= $code ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Departure Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
                            <label id="flightDateLabel" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Departure Date</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-calendar-days text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="date" 
                                       id="flightDateInput"
                                       placeholder="Choose flight date..." 
                                       value="<?= date('Y-m-d', strtotime('+3 days')) ?>" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Flight Date</span>
                                </div>
                                <div class="mb-3 space-y-2">
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Departure</label>
                                        <input type="date" id="flightDepDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                                    </div>
                                    <div id="flightReturnDateBlock" class="hidden">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Return Date</label>
                                        <input type="date" id="flightRetDate" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+6 days')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Class & Travelers -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Cabin &amp; Travelers</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="class_label" 
                                       id="flightClassDisplayInput"
                                       placeholder="Cabin & travelers..." 
                                       value="Economy, 1 Adult" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                                <input type="hidden" name="class" id="flightClassInput" value="Economy">
                                <input type="hidden" name="adults" id="flightAdultsHidden" value="1">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Cabin Class</div>
                                <div class="grid grid-cols-2 gap-1.5 text-xs mb-3">
                                    <button type="button" data-cabin="Economy" class="flight-cabin-chip active py-1.5 px-2 font-bold bg-brand-600 text-white transition text-center rounded-lg">Economy</button>
                                    <button type="button" data-cabin="Premium Economy" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center rounded-lg">Premium</button>
                                    <button type="button" data-cabin="Business" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center rounded-lg">Business</button>
                                    <button type="button" data-cabin="First Class" class="flight-cabin-chip py-1.5 px-2 font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-center rounded-lg">First Class</button>
                                </div>
                                <div class="space-y-2 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="flightAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="flightAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="flightAdultsCount" data-max="9" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" id="flightApplyBtn" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition rounded-xl">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Multi-City Extra Leg Container (Shown dynamically when Multi-City is selected) -->
                    <div id="flightMultiCityContainer" class="hidden mt-3 pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-xs font-bold text-brand-700 flex items-center gap-1.5 uppercase tracking-wider">
                                <i class="fa-solid fa-route text-xs"></i> Flight Leg 2
                            </span>
                            <span class="text-[11px] text-slate-400">Multi-destination routing</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            <!-- Leg 2 Departure Airport -->
                            <div class="dropdown-wrapper relative px-3 py-2 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Leg 2: From</label>
                                <input type="text" name="from2" id="flightFrom2Input" autocomplete="off" placeholder="Leg 2 Departure Airport..." class="flight-airport-typeahead w-full text-xs font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                                <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                    <div class="suggestion-list max-h-56 overflow-y-auto space-y-1">
                                        <?php foreach ($heroAirports as $code => $ap): 
                                            $fullAirportLabel = $ap['name'] . ' (' . $code . ')';
                                            $searchKeywords = strtolower($code . ' ' . $ap['name'] . ' ' . $ap['city'] . ' ' . ($ap['state'] ?? ''));
                                        ?>
                                            <div class="dropdown-select-item flight-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" 
                                                 data-val="<?= htmlspecialchars($fullAirportLabel) ?>" 
                                                 data-code="<?= $code ?>" 
                                                 data-name="<?= htmlspecialchars($ap['name']) ?>" 
                                                 data-search="<?= htmlspecialchars($searchKeywords) ?>">
                                                <span class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($ap['name']) ?></span>
                                                <span class="font-mono font-bold text-xs bg-slate-100 px-2 py-0.5 rounded border border-slate-200"><?= $code ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Leg 2 Destination Airport -->
                            <div class="dropdown-wrapper relative px-3 py-2 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Leg 2: To</label>
                                <input type="text" name="to2" id="flightTo2Input" autocomplete="off" placeholder="Leg 2 Destination Airport (e.g. GOX, BLR)..." class="flight-airport-typeahead w-full text-xs font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                                <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                    <div class="suggestion-list max-h-56 overflow-y-auto space-y-1">
                                        <?php foreach ($heroAirports as $code => $ap): 
                                            $fullAirportLabel = $ap['name'] . ' (' . $code . ')';
                                            $searchKeywords = strtolower($code . ' ' . $ap['name'] . ' ' . $ap['city'] . ' ' . ($ap['state'] ?? ''));
                                        ?>
                                            <div class="dropdown-select-item flight-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" 
                                                 data-val="<?= htmlspecialchars($fullAirportLabel) ?>" 
                                                 data-code="<?= $code ?>" 
                                                 data-name="<?= htmlspecialchars($ap['name']) ?>" 
                                                 data-search="<?= htmlspecialchars($searchKeywords) ?>">
                                                <span class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($ap['name']) ?></span>
                                                <span class="font-mono font-bold text-xs bg-slate-100 px-2 py-0.5 rounded border border-slate-200"><?= $code ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Leg 2 Date -->
                            <div class="px-3 py-2 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Leg 2: Date</label>
                                <input type="date" name="date2" id="flightDate2Input" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+6 days')) ?>" class="w-full text-xs font-bold text-slate-800 bg-transparent outline-none border-none p-0 focus:ring-0">
                            </div>
                        </div>
                    </div>


                    <div class="sm:hidden pt-3">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer rounded-xl">
                            <i class="fa-solid fa-plane-departure text-xs"></i>
                            <span>Search Cheap Flights</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ==========================================
                 PANEL 4: HOTELS (Database Driven)
            =========================================== -->
            <div id="panel-hotels" class="tab-panel hidden">
                <form action="search.php" method="GET" class="w-full">
                    <input type="hidden" name="type" value="hotel">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1 lg:gap-0 lg:divide-x lg:divide-slate-100">
                        
                        <!-- Hotel City / Resort -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors rounded-xl lg:rounded-none">
                            <label for="hotelCityInput" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">City / Location</label>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-hotel text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="query" 
                                       id="hotelCityInput"
                                       autocomplete="off"
                                       placeholder="Where do you want to stay?" 
                                       value="" 
                                       data-type="hotel"
                                       class="typeahead-input w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 focus:ring-0 truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-2.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1 flex items-center justify-between">
                                    <span class="dropdown-header-title">Cities &amp; Stays</span>
                                    <span class="text-teal-600 font-bold dropdown-status-indicator inline-flex items-center gap-1">
                                        <i class="fa-solid fa-bolt text-[10px]"></i> Live API
                                    </span>
                                </div>
                                <div class="suggestion-list max-h-60 overflow-y-auto space-y-1">
                                    <?php if (!empty($heroDbHotels)): ?>
                                        <?php foreach ($heroDbHotels as $hh): ?>
                                            <div class="dropdown-select-item flex items-center justify-between p-2 hover:bg-slate-50 cursor-pointer transition rounded-xl" data-val="<?= htmlspecialchars($hh['city']) ?>" data-sub="<?= htmlspecialchars($hh['name'] . ' • ' . ($hh['country'] ?: 'India')) ?>">
                                                <div class="flex items-center gap-2.5 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-hotel text-xs"></i></div>
                                                    <div class="truncate">
                                                        <span class="block text-xs font-bold text-slate-800"><?= htmlspecialchars($hh['city']) ?></span>
                                                        <span class="block text-[10px] text-slate-400"><?= htmlspecialchars($hh['name'] . ' • ' . ($hh['country'] ?: 'India')) ?></span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 shrink-0 rounded"><?= htmlspecialchars($hh['star_rating']) ?>★ in DB</span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-4 text-center text-xs text-slate-400">Type any city to search live hotel locations</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Check-In Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
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

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Check-In Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" id="hotelCheckinDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Check-Out Date -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
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

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pb-1 border-b border-slate-100 flex items-center justify-between">
                                    <span>Select Check-Out Date</span>
                                </div>
                                <div class="mb-3">
                                    <input type="date" id="hotelCheckoutDate" class="date-picker-input w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 text-slate-800 font-bold rounded-xl" min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Guests & Rooms -->
                        <div class="dropdown-wrapper relative px-3.5 py-2.5 sm:py-3 hover:bg-slate-50 transition-colors cursor-pointer rounded-xl lg:rounded-none">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-pointer">Guests &amp; Rooms</label>
                            <div class="dropdown-trigger flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-group text-sm"></i>
                                </div>
                                <input type="text" 
                                       name="hotel_guests" 
                                       id="hotelGuestsInput"
                                       placeholder="Rooms &amp; guests..." 
                                       value="" 
                                       readonly 
                                       class="field-val w-full text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal bg-transparent outline-none border-none p-0 cursor-pointer truncate">
                            </div>

                            <div class="dropdown-menu absolute top-[calc(100%+6px)] left-0 lg:left-auto lg:right-0 w-80 sm:w-96 max-w-[92vw] bg-white shadow-2xl border border-slate-200 p-3.5 z-[100] hidden animate-pop-in rounded-2xl">
                                <div class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Rooms</span><span class="block text-[10px] text-slate-400">Total rooms</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="hotelRoomsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="hotelRoomsCount" class="w-6 text-center font-bold text-xs text-slate-800">1</span>
                                            <button type="button" data-action="plus" data-target="hotelRoomsCount" data-max="5" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div><span class="block text-xs font-bold text-slate-800">Adults</span><span class="block text-[10px] text-slate-400">12+ years</span></div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" data-action="minus" data-target="hotelAdultsCount" data-min="1" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">-</button>
                                            <span id="hotelAdultsCount" class="w-6 text-center font-bold text-xs text-slate-800">2</span>
                                            <button type="button" data-action="plus" data-target="hotelAdultsCount" data-max="10" class="counter-btn w-7 h-7 border border-slate-200 bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600 hover:bg-slate-200 transition rounded">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100">
                                    <button type="button" id="hotelApplyBtn" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition rounded-xl">
                                        Apply Selection
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="sm:hidden pt-3">
                        <button type="submit" class="w-full py-3 px-5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer rounded-xl">
                            <i class="fa-solid fa-hotel text-xs"></i>
                            <span>Search Best Hotels</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Quick Trending Shortcuts -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-5 text-xs text-slate-200 relative z-20">
            <span class="font-bold text-amber-300 flex items-center gap-1">
                <i class="fa-solid fa-fire text-amber-400"></i> Top Searches:
            </span>
            <a href="search.php?query=Kashmir" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-mountain-sun text-[11px] text-teal-300"></i>
                <span>Kashmir Tours</span>
            </a>
            <a href="search.php?query=Dubai" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-city text-[11px] text-teal-300"></i>
                <span>Dubai Getaways</span>
            </a>
            <a href="search.php?query=Goa" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-umbrella-beach text-[11px] text-teal-300"></i>
                <span>Goa Beach Resorts</span>
            </a>
            <a href="flights.php?from=DEL&to=BOM" class="rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plane text-[11px] text-teal-300"></i>
                <span>Delhi to Mumbai Flights</span>
            </a>
        </div>

    </div>

</section>