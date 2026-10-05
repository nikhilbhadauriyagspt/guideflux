<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- Admin Sidebar (Fully Responsive for Mobile, Laptop, Ultra-wide Displays) -->
<aside id="admin-sidebar" class="fixed top-0 left-0 bottom-0 z-50 w-64 xl:w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 select-none">
    
    <!-- Sidebar Header / Logo -->
    <div class="h-16 xl:h-20 flex items-center justify-between px-5 xl:px-6 border-b border-slate-100 bg-white shrink-0">
        <a href="dashboard.php" class="flex items-center gap-2.5 xl:gap-3 group">
            <div class="w-9 h-9 xl:w-10 xl:h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center text-base xl:text-lg transition-transform group-hover:scale-105">
                <i class="fa-solid fa-compass"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-space font-bold text-lg xl:text-xl tracking-tight text-slate-900 leading-tight">
                    Guide<span class="text-brand-600">Flux</span>
                </span>
                <span class="text-[9px] xl:text-[10px] uppercase font-bold tracking-widest text-slate-400">Admin Control</span>
            </div>
        </a>
        
        <!-- Mobile Close Button -->
        <button type="button" onclick="toggleSidebar()" aria-label="Close Sidebar" class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
    </div>

    <!-- Navigation Scrollable Area -->
    <div class="flex-1 overflow-y-auto px-3.5 xl:px-4 py-5 space-y-6">
        
        <!-- Section 1: Overview -->
        <div>
            <div class="px-3 mb-2 text-[10px] xl:text-[11px] font-bold uppercase tracking-wider text-slate-400">Main Menu</div>
            <ul class="space-y-1">
                <li>
                    <a href="dashboard.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'dashboard.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-chart-pie w-4 text-center text-sm <?php echo $currentPage == 'dashboard.php' ? 'text-white' : 'text-brand-600'; ?>"></i>
                            <span>Dashboard</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-bold <?php echo $currentPage == 'dashboard.php' ? 'bg-white/20 text-white' : 'bg-brand-50 text-brand-700'; ?>">Live</span>
                    </a>
                </li>
                <li>
                    <a href="bookings.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'bookings.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-calendar-check w-4 text-center text-sm <?php echo $currentPage == 'bookings.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Bookings</span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $currentPage == 'bookings.php' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'; ?>">Live</span>
                    </a>
                </li>
                <li>
                    <a href="packages.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'packages.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-map-location-dot w-4 text-center text-sm <?php echo $currentPage == 'packages.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Tour Packages</span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $currentPage == 'packages.php' ? 'bg-white/20 text-white' : 'bg-brand-50 text-brand-700'; ?>">Live</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section 2: Catalog & Listings -->
        <div>
            <div class="px-3 mb-2 text-[10px] xl:text-[11px] font-bold uppercase tracking-wider text-slate-400">Listings & Stays</div>
            <ul class="space-y-1">
                <li>
                    <a href="hotels.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'hotels.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-hotel w-4 text-center text-sm <?php echo $currentPage == 'hotels.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Hotels &amp; Resorts</span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $currentPage == 'hotels.php' ? 'bg-white/20 text-white' : 'bg-purple-50 text-purple-700'; ?>">Live</span>
                    </a>
                </li>
                <li>
                    <a href="flights.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'flights.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-plane-departure w-4 text-center text-sm <?php echo $currentPage == 'flights.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Flights &amp; Routes</span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $currentPage == 'flights.php' ? 'bg-white/20 text-white' : 'bg-sky-50 text-sky-700'; ?>">Live</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-star w-4 text-center text-sm text-amber-500"></i>
                            <span>Guest Reviews</span>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">4.9 ★</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section 3: Management & Tools -->
        <div>
            <div class="px-3 mb-2 text-[10px] xl:text-[11px] font-bold uppercase tracking-wider text-slate-400">System & Database</div>
            <ul class="space-y-1">
                <li>
                    <a href="users.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'users.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-users w-4 text-center text-sm <?php echo $currentPage == 'users.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Travelers &amp; Users</span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $currentPage == 'users.php' ? 'bg-white/20 text-white' : 'bg-brand-50 text-brand-700'; ?>">Live</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-colors <?php echo $currentPage == 'settings.php' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900'; ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-sliders w-4 text-center text-sm <?php echo $currentPage == 'settings.php' ? 'text-white' : 'text-slate-400'; ?>"></i>
                            <span>Settings &amp; SMTP</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="setup.php" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-database w-4 text-center text-sm text-teal-600"></i>
                            <span>DB Setup / Migration</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Sidebar Footer Profile & Quick Website Link -->
    <div class="p-3.5 xl:p-4 border-t border-slate-100 bg-white shrink-0">
        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl border border-slate-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80" alt="Avatar" class="w-8 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                <div class="truncate">
                    <div class="text-xs font-bold text-slate-800 truncate"><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin Manager'; ?></div>
                    <div class="text-[10px] text-slate-400 truncate"><?php echo isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'admin@guideflux.com'; ?></div>
                </div>
            </div>
            <a href="logout.php" title="Sign Out" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors shrink-0">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
            </a>
        </div>
        
        <div class="mt-2.5">
            <a href="../index.php" target="_blank" class="w-full inline-flex items-center justify-center gap-2 py-2 px-3 text-xs font-medium text-slate-600 bg-white hover:bg-slate-100 hover:text-slate-900 border border-slate-200 rounded-xl transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>Open Live Website</span>
            </a>
        </div>
    </div>
</aside>
