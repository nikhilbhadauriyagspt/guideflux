<?php
$isUserLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$loggedInUserName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Traveler';
$loggedInUserEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$siteName = getSetting('site_name', 'GuideFlux');
$sitePhone = getSetting('site_phone', '+91 98765 43210');
$siteWhatsapp = getSetting('site_whatsapp', '919876543210');
$siteLogo = getSetting('site_logo', 'assets/images/logo/logo.webp');
?>
<!-- Dynamic Navbar Header - Full Width, 100% Flat Border-First (No Shadows, Icon Library Only) -->
<header class="w-full sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
    <!-- Top Utility Bar (Logo Brand Color Background: bg-brand-600) -->
    <div class="w-full bg-brand-600 text-white text-xs py-2 px-3 sm:px-8 xl:px-12 border-b border-brand-700/50">
        <div class="w-full flex flex-wrap justify-between items-center gap-2 sm:gap-3">
            <!-- Left: Minimal Offer Pill -->
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                <span class="inline-flex items-center space-x-1 sm:space-x-1.5 px-2.5 sm:px-3 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold bg-white/20 text-white border border-white/30 shrink-0">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    <span>Summer Deals</span>
                </span>
                <span class="text-teal-50 font-medium text-[11px] sm:text-xs truncate">
                    <span class="hidden sm:inline">Flat 25% Off on Tour Packages &bull; Use: </span>
                    <span class="sm:hidden">25% Off: </span>
                    <strong class="text-amber-300 font-mono bg-brand-800/40 px-1.5 py-0.5 rounded border border-amber-300/40">EXPLORE25</strong>
                </span>
            </div>

            <!-- Right: WhatsApp, Helpline & Currency -->
            <div class="flex items-center space-x-2.5 sm:space-x-4 ml-auto shrink-0">
                <a href="https://wa.me/<?php echo htmlspecialchars($siteWhatsapp); ?>" target="_blank" class="inline-flex items-center space-x-1 sm:space-x-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-white/15 text-white border border-white/25 hover:bg-white/25 transition text-[11px] sm:text-xs font-semibold">
                    <i class="fa-brands fa-whatsapp text-xs sm:text-sm"></i>
                    <span class="hidden xs:inline">WhatsApp</span>
                </a>

                <a href="tel:<?php echo htmlspecialchars($sitePhone); ?>" class="flex items-center space-x-1.5 hover:text-teal-100 transition text-[11px] sm:text-xs text-white">
                    <i class="fa-solid fa-phone text-[10px] sm:text-xs text-teal-200"></i>
                    <span class="hidden sm:inline text-teal-100">24x7:</span> <strong class="text-white font-medium"><?php echo htmlspecialchars($sitePhone); ?></strong>
                </a>

                <span class="text-teal-400 hidden md:inline">|</span>

                <div class="hidden md:flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-white/15 border border-white/25 cursor-pointer hover:bg-white/25 transition">
                    <i class="fa-solid fa-indian-rupee-sign text-[11px]"></i>
                    <span>INR (₹)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar (Full Width, Flat, Bada Logo Size) -->
    <div class="w-full px-4 sm:px-8 xl:px-12">
        <div class="flex items-center justify-between h-16 sm:h-20 md:h-24">
            <!-- Brand Logo -->
            <a href="index.php" class="flex items-center py-2 shrink-0 group focus:outline-none">
                <img src="<?php echo htmlspecialchars($siteLogo); ?>" 
                     alt="<?php echo htmlspecialchars($siteName); ?> - Your Passport to Adventure" 
                     class="h-12 sm:h-16 md:h-18 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <span class="hidden items-center gap-2 font-space font-extrabold text-2xl text-slate-900 tracking-tight">
                    <span class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center text-base"><i class="fa-solid fa-compass"></i></span>
                    <span><?php echo htmlspecialchars($siteName); ?></span>
                </span>
            </a>

            <!-- Center Navigation: Clean Rounded-Full Capsule -->
            <nav class="hidden lg:flex items-center p-1 bg-slate-50 rounded-full border border-slate-200">
                
                <!-- Tour Packages (Dropdown with Domestic & International) -->
                <div class="relative group">
                    <button type="button" class="flex items-center space-x-2 px-4 py-2 rounded-full text-sm font-semibold bg-white text-brand-700 border border-brand-200/80 transition-all focus:outline-none">
                        <i class="fa-solid fa-map-location-dot text-brand-600 text-xs"></i>
                        <span>Tour Packages</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-brand-500 group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>

                    <!-- Dropdown: Categorized into Domestic Travel & International Travel (Flat Border) -->
                    <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute top-full -left-20 mt-3 w-[720px] bg-white rounded-3xl border border-slate-200 p-6 z-50">
                        <!-- Dropdown Header -->
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Explore Tour Packages</h3>
                                <p class="text-xs text-slate-500">Choose from top handpicked domestic getaways & international holiday trips</p>
                            </div>
                            <span class="rounded-full bg-brand-50 text-brand-700 text-xs font-semibold px-3 py-1 border border-brand-200">
                                150+ Itineraries
                            </span>
                        </div>

                        <!-- 2 Main Columns: Domestic Travel & International Travel -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Column 1: Domestic Travel (India) -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-map-location-dot text-brand-600 text-sm"></i>
                                        <h4 class="font-bold text-slate-900 text-sm">Domestic Travel</h4>
                                    </div>
                                    <span class="rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5">India Tours</span>
                                </div>

                                <div class="space-y-1">
                                    <a href="search.php?type=package&query=Kashmir" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-mountain-sun text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Kashmir Paradise</p>
                                                <span class="text-[10px] text-slate-400">5D/4N &bull; Srinagar & Gulmarg</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹17,999</span>
                                    </a>

                                    <a href="search.php?type=package&query=Himachal" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-tree text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Himachal Highlights</p>
                                                <span class="text-[10px] text-slate-400">6D/5N &bull; Manali & Shimla</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹14,999</span>
                                    </a>

                                    <a href="search.php?type=package&query=Kerala" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-umbrella-beach text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Kerala Backwaters</p>
                                                <span class="text-[10px] text-slate-400">5D/4N &bull; Munnar & Alleppey</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹18,500</span>
                                    </a>

                                    <a href="search.php?type=package&query=Goa" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-water text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Goa Holiday Escape</p>
                                                <span class="text-[10px] text-slate-400">4D/3N &bull; Cruise & Resort</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹11,999</span>
                                    </a>

                                    <a href="search.php?type=package&query=Rajasthan" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-monument text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Royal Rajasthan</p>
                                                <span class="text-[10px] text-slate-400">6D/5N &bull; Jaipur & Udaipur</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹16,500</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Column 2: International Travel (World) -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-globe text-brand-600 text-sm"></i>
                                        <h4 class="font-bold text-slate-900 text-sm">International Travel</h4>
                                    </div>
                                    <span class="rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5">World Tours</span>
                                </div>

                                <div class="space-y-1">
                                    <a href="search.php?type=package&query=Dubai" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-city text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Dubai Extravaganza</p>
                                                <span class="text-[10px] text-slate-400">5D/4N &bull; Burj & Safari</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹34,999</span>
                                    </a>

                                    <a href="search.php?type=package&query=Bali" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-spa text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Bali Island Bliss</p>
                                                <span class="text-[10px] text-slate-400">6D/5N &bull; Pool Villa</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹28,500</span>
                                    </a>

                                    <a href="search.php?type=package&query=Thailand" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-landmark text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Thailand Explorer</p>
                                                <span class="text-[10px] text-slate-400">5D/4N &bull; Bangkok & Phuket</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹25,999</span>
                                    </a>

                                    <a href="search.php?type=package&query=Maldives" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-sun text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Maldives Luxury Stay</p>
                                                <span class="text-[10px] text-slate-400">4D/3N &bull; Water Villa</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹52,000</span>
                                    </a>

                                    <a href="search.php?type=package&query=Vietnam" class="flex items-center justify-between p-2 rounded-2xl hover:bg-brand-50/70 transition group/item">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-compass text-xs"></i>
                                            </span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800 group-hover/item:text-brand-700">Vietnam Discovery</p>
                                                <span class="text-[10px] text-slate-400">6D/5N &bull; Hanoi & Cruise</span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-slate-100 text-slate-700 group-hover/item:bg-brand-100 group-hover/item:text-brand-800 text-[11px] font-bold px-2.5 py-0.5 transition">From ₹36,999</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Footer -->
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">Looking for customized itineraries or group discounts?</span>
                            <a href="#custom-quote" class="rounded-full bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2 transition">
                                Request Custom Quote &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Flights -->
                <a href="flights.php" class="flex items-center space-x-1.5 px-4 py-2 rounded-full text-sm font-medium text-slate-600 hover:text-brand-700 hover:bg-white transition">
                    <i class="fa-solid fa-plane-departure text-slate-400 text-xs"></i>
                    <span>Flights</span>
                </a>

                <!-- Hotels -->
                <a href="search.php?type=hotel" class="flex items-center space-x-1.5 px-4 py-2 rounded-full text-sm font-medium text-slate-600 hover:text-brand-700 hover:bg-white transition">
                    <i class="fa-solid fa-hotel text-slate-400 text-xs"></i>
                    <span>Hotels</span>
                </a>

                <!-- Holidays -->
                <a href="search.php?type=package" class="flex items-center space-x-1.5 px-4 py-2 rounded-full text-sm font-medium text-slate-600 hover:text-brand-700 hover:bg-white transition">
                    <i class="fa-solid fa-umbrella-beach text-slate-400 text-xs"></i>
                    <span>Holidays</span>
                </a>
            </nav>

            <!-- Right Actions: Guest OR Logged-In User Profile Dropdown -->
            <div class="hidden md:flex items-center space-x-3">
                <?php if ($isUserLoggedIn): ?>
                    <!-- Logged In User Dropdown Capsule -->
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-full bg-slate-50 hover:bg-slate-100 border border-slate-200 transition focus:outline-none cursor-pointer">
                            <div class="w-8 h-8 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center uppercase shadow-xs">
                                <?php echo substr($loggedInUserName, 0, 1); ?>
                            </div>
                            <div class="text-left leading-none">
                                <span class="block text-xs font-bold text-slate-800"><?php echo htmlspecialchars(explode(' ', $loggedInUserName)[0]); ?></span>
                                <span class="text-[10px] text-teal-600 font-semibold">Traveler Member</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:rotate-180 transition-transform duration-200 ml-1"></i>
                        </button>

                        <!-- User Profile Dropdown Menu -->
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 animate-pop-in">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Signed in as</p>
                                <p class="text-xs font-bold text-slate-900 truncate"><?php echo htmlspecialchars($loggedInUserName); ?></p>
                                <p class="text-[11px] text-slate-500 truncate"><?php echo htmlspecialchars($loggedInUserEmail); ?></p>
                            </div>

                            <div class="py-1">
                                <a href="dashboard.php" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <i class="fa-solid fa-suitcase-rolling text-slate-400 w-4 text-center"></i>
                                    <span>My Bookings &amp; Trips</span>
                                </a>
                                <a href="dashboard.php#saved" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <i class="fa-regular fa-bookmark text-slate-400 w-4 text-center"></i>
                                    <span>Saved Itineraries</span>
                                </a>
                                <a href="dashboard.php#profile" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <i class="fa-regular fa-user text-slate-400 w-4 text-center"></i>
                                    <span>Account Profile</span>
                                </a>
                            </div>

                            <div class="pt-1 border-t border-slate-100">
                                <a href="javascript:void(0)" onclick="
                                    fetch('api/auth.php?action=logout').then(() => window.location.reload());
                                " class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                    <span>Sign Out</span>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Guest: Log In and Sign Up Buttons -->
                    <a href="login.php" class="rounded-full px-5 py-2.5 text-xs font-bold text-slate-700 hover:text-brand-700 hover:bg-slate-100 border border-slate-300 transition flex items-center space-x-1.5">
                        <i class="fa-regular fa-user text-xs text-slate-500"></i>
                        <span>Log In</span>
                    </a>

                    <a href="signup.php" class="rounded-full bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-6 py-2.5 transition active:scale-95 flex items-center space-x-1.5">
                        <i class="fa-solid fa-user-plus text-xs text-teal-200"></i>
                        <span>Sign Up</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex lg:hidden items-center">
                <button id="mobileMenuBtn" type="button" class="p-2.5 rounded-full text-slate-700 hover:text-brand-600 hover:bg-brand-50 transition focus:outline-none" aria-label="Toggle Menu">
                    <i id="hamburgerIcon" class="fa-solid fa-bars text-lg"></i>
                    <i id="closeIcon" class="fa-solid fa-xmark text-lg hidden"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-5 py-5 space-y-4 max-h-[calc(100vh-80px)] overflow-y-auto">
        <?php if ($isUserLoggedIn): ?>
            <!-- Mobile Logged-in profile badge -->
            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-brand-600 text-white font-bold text-sm flex items-center justify-center uppercase shrink-0">
                        <?php echo substr($loggedInUserName, 0, 1); ?>
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-800 truncate"><?php echo htmlspecialchars($loggedInUserName); ?></p>
                        <span class="text-[11px] text-slate-500 truncate block"><?php echo htmlspecialchars($loggedInUserEmail); ?></span>
                    </div>
                </div>
                <a href="javascript:void(0)" onclick="fetch('api/auth.php?action=logout').then(() => window.location.reload());" class="p-2 text-rose-600 text-xs font-bold hover:bg-rose-50 rounded-lg">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        <?php endif; ?>

        <!-- Service Categories -->
        <div class="grid grid-cols-2 gap-2">
            <a href="search.php?type=package" class="flex items-center space-x-2 p-3 rounded-2xl bg-brand-50 text-brand-700 font-bold text-xs border border-brand-200/80 justify-center">
                <i class="fa-solid fa-map-location-dot text-sm"></i>
                <span>Tour Packages</span>
            </a>
            <a href="search.php?type=flight" class="flex items-center space-x-2 p-3 rounded-2xl bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 justify-center hover:bg-slate-100">
                <i class="fa-solid fa-plane-departure text-sm text-brand-600"></i>
                <span>Flights</span>
            </a>
            <a href="search.php?type=hotel" class="flex items-center space-x-2 p-3 rounded-2xl bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 justify-center hover:bg-slate-100">
                <i class="fa-solid fa-hotel text-sm text-brand-600"></i>
                <span>Hotels</span>
            </a>
            <a href="search.php?type=package" class="flex items-center space-x-2 p-3 rounded-2xl bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 justify-center hover:bg-slate-100">
                <i class="fa-solid fa-umbrella-beach text-sm text-brand-600"></i>
                <span>Holidays</span>
            </a>
        </div>

        <!-- Quick Destination Shortcuts (Domestic & International) -->
        <div class="pt-3 border-t border-slate-100 space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Popular Holiday Circuits</span>
            <div class="flex flex-wrap gap-1.5">
                <a href="search.php?type=package&query=Kashmir" class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold hover:bg-brand-50 hover:text-brand-700 border border-slate-200/80">Kashmir</a>
                <a href="search.php?type=package&query=Himachal" class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold hover:bg-brand-50 hover:text-brand-700 border border-slate-200/80">Himachal</a>
                <a href="search.php?type=package&query=Kerala" class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold hover:bg-brand-50 hover:text-brand-700 border border-slate-200/80">Kerala</a>
                <a href="search.php?type=package&query=Goa" class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold hover:bg-brand-50 hover:text-brand-700 border border-slate-200/80">Goa</a>
                <a href="search.php?type=package&query=Dubai" class="px-2.5 py-1 rounded-full bg-brand-50 text-brand-700 text-[11px] font-semibold border border-brand-200">Dubai</a>
                <a href="search.php?type=package&query=Bali" class="px-2.5 py-1 rounded-full bg-brand-50 text-brand-700 text-[11px] font-semibold border border-brand-200">Bali</a>
                <a href="search.php?type=package&query=Thailand" class="px-2.5 py-1 rounded-full bg-brand-50 text-brand-700 text-[11px] font-semibold border border-brand-200">Thailand</a>
            </div>
        </div>

        <?php if (!$isUserLoggedIn): ?>
            <!-- Mobile Guest Login & Sign Up -->
            <div class="pt-3 border-t border-slate-100">
                <div class="grid grid-cols-2 gap-2">
                    <a href="login.php" class="flex items-center justify-center space-x-1.5 py-2.5 rounded-full text-slate-700 border border-slate-300 font-bold text-xs hover:bg-slate-50">
                        <i class="fa-regular fa-user text-xs text-slate-500"></i>
                        <span>Log In</span>
                    </a>
                    <a href="signup.php" class="flex items-center justify-center space-x-1.5 py-2.5 rounded-full bg-brand-600 text-white font-bold text-xs hover:bg-brand-700">
                        <i class="fa-solid fa-user-plus text-xs text-teal-200"></i>
                        <span>Sign Up</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>
