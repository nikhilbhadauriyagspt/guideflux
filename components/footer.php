<?php
/**
 * Footer Component - GuideFlux
 * - Pre-Footer Newsletter & Travel Club Strip
 * - 5-Column Comprehensive Travel Portal Directory
 * - Payment Partner Badges & Official Tourism Accreditations
 * - 100% Flat, Border-First, Zero Shadows
 * - Pure Font Awesome 6 Icons Only (Zero Emojis)
 */
$fSiteName = getSetting('site_name', 'GuideFlux');
$fSitePhone = getSetting('site_phone', '+91 98765 43210');
$fSiteEmail = getSetting('site_email', 'concierge@guideflux.com');
?>
<!-- ==========================================
     PRE-FOOTER: EXCLUSIVE TRAVEL CLUB
=========================================== -->
<section class="py-12 md:py-16 px-4 sm:px-8 xl:px-12 bg-white border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="p-8 sm:p-10 md:p-12 rounded-3xl bg-slate-50 border border-slate-200 flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden">
            
            <!-- Ambient Dot Matrix Texture -->
            <div class="absolute inset-0 pointer-events-none opacity-[0.03] bg-[radial-gradient(#068285_1px,transparent_1px)] [background-size:24px_24px]"></div>

            <div class="max-w-xl text-center lg:text-left space-y-2 relative z-10">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                    <i class="fa-solid fa-tag text-brand-600"></i>
                    <span><?php echo htmlspecialchars($fSiteName); ?> Travel Club</span>
                </span>
                
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    Get Secret Flight Drops &amp; Weekend Deals
                </h3>

                <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed">
                    Join 120,000+ smart travelers who receive our secret hotel sales, zero-convenience fee codes, and visa-free travel guides every Thursday.
                </p>
            </div>

            <!-- Newsletter Input Form -->
            <div class="w-full lg:w-auto relative z-10">
                <form class="flex flex-col sm:flex-row items-center gap-2 max-w-md mx-auto lg:mx-0" onsubmit="event.preventDefault(); alert('Welcome to <?php echo htmlspecialchars($fSiteName); ?> Travel Club! Check your inbox for your ₹1,500 welcome discount code.');">
                    <div class="relative w-full sm:w-80">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" 
                               required 
                               placeholder="Enter your email address..." 
                               class="w-full pl-11 pr-4 py-3 text-xs bg-white text-slate-800 placeholder-slate-400 border border-slate-200 rounded-full focus:outline-none focus:border-brand-500 font-semibold transition">
                    </div>

                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider transition-all transform hover:-translate-y-0.5 active:scale-95 shrink-0 flex items-center justify-center space-x-2">
                        <span>Subscribe Free</span>
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </form>

                <span class="block text-[11px] text-slate-400 mt-2 text-center lg:text-left flex items-center justify-center lg:justify-start space-x-1.5">
                    <i class="fa-solid fa-lock text-[10px] text-emerald-600"></i>
                    <span>Zero spam. One email per week. Unsubscribe anytime in 1 click.</span>
                </span>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     MAIN PORTAL FOOTER (5-COLUMN DIRECTORY)
=========================================== -->
<footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-28 lg:pb-12 px-4 sm:px-8 xl:px-12 relative overflow-hidden select-none">
    <div class="max-w-7xl mx-auto space-y-14">

        <!-- 1. Five Navigation Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
            
            <!-- Col 1: Brand Info & 24x7 Concierge (4 Cols) -->
            <div class="lg:col-span-4 space-y-5">
                <a href="index.php" class="flex items-center space-x-2.5 group inline-block">
                    <span class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center border border-brand-500 group-hover:bg-brand-500 transition-colors">
                        <i class="fa-solid fa-compass text-lg"></i>
                    </span>
                    <span class="text-2xl font-black text-white tracking-tight">
                        <?php echo htmlspecialchars($fSiteName); ?>
                    </span>
                </a>

                <p class="text-xs sm:text-[13px] text-slate-400 leading-relaxed font-normal">
                    India's leading experiential holiday portal. We craft deeply personalized vacations across 45+ domestic circuits and international destinations with 100% price transparency and on-trip concierge care.
                </p>

                <!-- Direct Contact Details -->
                <div class="space-y-2.5 text-xs text-slate-300 pt-1">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-slate-800 text-brand-400 flex items-center justify-center shrink-0 border border-slate-700">
                            <i class="fa-solid fa-phone text-[11px]"></i>
                        </span>
                        <div>
                            <span class="block text-white font-bold"><?php echo htmlspecialchars($fSitePhone); ?></span>
                            <span class="text-[10px] text-slate-400">24x7 Dedicated Trip Helpdesk</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-slate-800 text-brand-400 flex items-center justify-center shrink-0 border border-slate-700">
                            <i class="fa-solid fa-envelope text-[11px]"></i>
                        </span>
                        <a href="mailto:<?php echo htmlspecialchars($fSiteEmail); ?>" class="text-slate-300 hover:text-white transition font-medium">
                            <?php echo htmlspecialchars($fSiteEmail); ?>
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-slate-800 text-brand-400 flex items-center justify-center shrink-0 border border-slate-700">
                            <i class="fa-solid fa-location-dot text-[11px]"></i>
                        </span>
                        <span class="text-slate-400 font-normal">
                            Barakhamba Road, Connaught Place, New Delhi 110001
                        </span>
                    </div>
                </div>

                <!-- Social Handles -->
                <div class="flex items-center space-x-2 pt-2">
                    <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center transition">
                        <i class="fa-brands fa-facebook-f text-xs"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center transition">
                        <i class="fa-brands fa-instagram text-xs"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center transition">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                    <a href="#" aria-label="X Twitter" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center transition">
                        <i class="fa-brands fa-x-twitter text-xs"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white border border-slate-700 flex items-center justify-center transition">
                        <i class="fa-brands fa-linkedin-in text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Top Domestic Circuits (2 Cols) -->
            <div class="lg:col-span-2 space-y-3.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white border-b border-slate-800 pb-2 flex items-center">
                    <i class="fa-solid fa-map-location-dot text-brand-400 mr-2"></i>
                    <span>Domestic Tours</span>
                </h4>
                <ul class="space-y-2 text-xs font-normal text-slate-400">
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Kashmir Valley & Gulmarg</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Goa Beachfront Resorts</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Kerala Backwaters & Munnar</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Leh Ladakh Expedition</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Himachal Magic & Kasol</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Royal Rajasthan Palaces</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Andaman Islands & Scuba</a></li>
                    <li><a href="#domestic-packages" class="hover:text-white transition flex items-center">Uttarakhand River Rafting</a></li>
                </ul>
            </div>

            <!-- Col 3: International Getaways (2 Cols) -->
            <div class="lg:col-span-2 space-y-3.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white border-b border-slate-800 pb-2 flex items-center">
                    <i class="fa-solid fa-earth-americas text-brand-400 mr-2"></i>
                    <span>International</span>
                </h4>
                <ul class="space-y-2 text-xs font-normal text-slate-400">
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Dubai & Burj Khalifa</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Maldives Overwater Villas</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Bali Romantic Escapes</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Singapore & Sentosa Island</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Thailand Island Explorer</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Vietnam & Halong Cruise</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Switzerland Alpine Express</a></li>
                    <li><a href="#international-packages" class="hover:text-white transition flex items-center">Mauritius Luxury Beach</a></li>
                </ul>
            </div>

            <!-- Col 4: Holiday Themes (2 Cols) -->
            <div class="lg:col-span-2 space-y-3.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white border-b border-slate-800 pb-2 flex items-center">
                    <i class="fa-solid fa-compass text-brand-400 mr-2"></i>
                    <span>Travel By Vibe</span>
                </h4>
                <ul class="space-y-2 text-xs font-normal text-slate-400">
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Honeymoon Specials</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Snow Peaks & Treks</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Tropical Beach Vacations</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Royal Forts & Havelis</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Wildlife & Tiger Safaris</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Spiritual & Yoga Retreats</a></li>
                    <li><a href="#experience-themes" class="hover:text-white transition flex items-center">Weekend Trips Under ₹9,999</a></li>
                    <li><a href="#hotels-section" class="hover:text-white transition flex items-center">5★ Luxury Resorts</a></li>
                </ul>
            </div>

            <!-- Col 5: Trust, Safety & Company (2 Cols) -->
            <div class="lg:col-span-2 space-y-3.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white border-b border-slate-800 pb-2 flex items-center">
                    <i class="fa-solid fa-shield-halved text-brand-400 mr-2"></i>
                    <span>Trust &amp; Policies</span>
                </h4>
                <ul class="space-y-2 text-xs font-normal text-slate-400">
                    <li><a href="about.php" class="hover:text-white transition flex items-center">About <?php echo htmlspecialchars($fSiteName); ?></a></li>
                    <li><a href="privacy-policy.php" class="hover:text-white transition flex items-center">Privacy Policy</a></li>
                    <li><a href="terms.php" class="hover:text-white transition flex items-center">Terms &amp; Conditions</a></li>
                    <li><a href="cancellation-policy.php" class="hover:text-white transition flex items-center">Cancellation &amp; Refund</a></li>
                    <li><a href="policies.php?tab=booking" class="hover:text-white transition flex items-center">Token Advance Guarantee</a></li>
                    <li><a href="contact.php" class="hover:text-white transition flex items-center">24x7 Help Concierge</a></li>
                    <li><a href="contact.php#faq" class="hover:text-white transition flex items-center">Frequently Asked Questions</a></li>
                    <li><a href="my-trips.php" class="hover:text-white transition flex items-center">My Trips &amp; Vouchers</a></li>
                </ul>
            </div>

        </div>

        <!-- 2. Payment Security & Partner Accreditations Strip -->
        <div class="pt-8 border-t border-slate-800/80 flex flex-col md:flex-row items-center justify-between gap-6 text-xs text-slate-400">
            
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-[11px] font-bold uppercase text-slate-500 tracking-wider">Accepted Payment Modes:</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">UPI</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">Razorpay</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">Visa</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">Mastercard</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">RuPay</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-white font-bold text-[11px]">NetBanking</span>
            </div>

            <div class="flex flex-wrap items-center gap-5 text-slate-400 font-semibold">
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-lock text-emerald-400 text-xs"></i>
                    <span>256-Bit SSL Secured</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-shield-halved text-brand-400 text-xs"></i>
                    <span>Verified Travel Partners</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                    <span>4.9 ★ Rated Stays</span>
                </div>
            </div>

        </div>

        <!-- 3. Bottom Legal Disclaimer & Copyright -->
        <div class="pt-6 border-t border-slate-800/60 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                <span>&copy; <?= date('Y') ?> <strong class="text-slate-400"><?php echo htmlspecialchars($fSiteName); ?> Holidays &amp; Stays Pvt. Ltd.</strong> All rights reserved.</span>
                <span class="text-slate-700 hidden sm:inline">&bull;</span>
                <a href="privacy-policy.php" class="hover:text-slate-300 transition">Privacy</a>
                <span class="text-slate-700">&bull;</span>
                <a href="terms.php" class="hover:text-slate-300 transition">Terms</a>
                <span class="text-slate-700">&bull;</span>
                <a href="cancellation-policy.php" class="hover:text-slate-300 transition">Refunds</a>
            </div>

            <div class="text-center md:text-right text-slate-500 font-normal">
                Fares &amp; inclusions are verified directly with certified airline &amp; hospitality partners. Fares are subject to seasonal availability.
            </div>
        </div>

    </div>
</footer>

<!-- Mobile Bottom Navigation (Visible on mobile/tablet <lg, hidden on desktop) -->
<?php require_once __DIR__ . '/bottom-nav.php'; ?>

<!-- Main Scripts -->
<script src="assets/js/main.js"></script>
</body>
</html>
