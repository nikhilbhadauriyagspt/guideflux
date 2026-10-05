<!-- Top Admin Header Bar (Responsive & Fluid for Mobile, Laptop, Big Screens) -->
<header class="sticky top-0 z-30 h-16 xl:h-18 bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3 sm:gap-4 shrink-0">
    
    <!-- Left: Mobile Toggle & Search Bar -->
    <div class="flex items-center gap-3 sm:gap-4 flex-1 max-w-xl">
        <!-- Mobile Sidebar Toggle -->
        <button type="button" onclick="toggleSidebar()" aria-label="Toggle Sidebar" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors shrink-0">
            <i class="fa-solid fa-bars-staggered text-sm"></i>
        </button>

        <!-- Search Bar (Adapts smoothly) -->
        <div class="relative w-full max-w-md hidden sm:block">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" placeholder="Search bookings, packages, customers..." 
                   class="w-full pl-9 pr-12 py-2 text-xs bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200 focus:border-brand-600 rounded-xl outline-none transition-all placeholder:text-slate-400">
            <div class="absolute right-2.5 top-1/2 -translate-y-1/2 hidden md:flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-200/70 text-slate-500 pointer-events-none">
                <span>⌘K</span>
            </div>
        </div>

        <!-- Page breadcrumb label for small mobile screens -->
        <div class="sm:hidden font-space font-bold text-sm text-slate-800">
            Dashboard
        </div>
    </div>

    <!-- Right: Quick Actions, Live Status, Notifications & User Profile -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        
        <!-- Live System Indicator (Hidden on small mobile) -->
        <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200/60 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Live Sync</span>
        </div>

        <!-- Add Package Action Button -->
        <button onclick="alert('Quick Action: Add Package modal triggered')" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer shrink-0">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span class="hidden sm:inline">Add Package</span>
        </button>

        <!-- Notifications Dropdown -->
        <div class="relative">
            <button id="notif-btn" onclick="toggleDropdown('notif-menu')" aria-label="Notifications" class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-regular fa-bell text-sm"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
            </button>
            
            <!-- Dropdown Menu (Responsive positioning) -->
            <div id="notif-menu" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-lg border border-slate-200 py-2 z-50 animate-pop-in">
                <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-xs text-slate-800">Notifications</span>
                    <span class="text-[10px] font-semibold bg-brand-50 text-brand-700 px-2 py-0.5 rounded-full">3 New</span>
                </div>
                <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                    <a href="javascript:void(0)" class="flex items-start gap-3 p-3 hover:bg-slate-50 transition-colors">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-semibold text-slate-800">Booking Confirmed</p>
                            <p class="text-slate-500 text-[11px]">Rahul Sharma booked "Dubai Luxury Tour" ($1,250).</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">5 min ago</span>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="flex items-start gap-3 p-3 hover:bg-slate-50 transition-colors">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-semibold text-slate-800">New Review</p>
                            <p class="text-slate-500 text-[11px]">Priya Patel rated Goa Resort 5-stars.</p>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">42 min ago</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="h-5 w-px bg-slate-200 hidden sm:block mx-0.5"></div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button id="user-menu-btn" onclick="toggleDropdown('user-menu')" class="flex items-center gap-2 p-1 sm:p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80" alt="Avatar" class="w-8 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                <div class="text-left hidden md:block">
                    <div class="text-xs font-bold text-slate-800 leading-tight"><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?></div>
                    <div class="text-[10px] text-slate-400 font-medium">Super Admin</div>
                </div>
                <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] ml-0.5 hidden sm:block"></i>
            </button>

            <!-- User Menu Dropdown -->
            <div id="user-menu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-slate-200 py-1.5 z-50">
                <div class="px-3.5 py-2 border-b border-slate-100">
                    <p class="text-[10px] text-slate-400">Signed in as</p>
                    <p class="text-xs font-bold text-slate-800 truncate"><?php echo isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : 'admin@guideflux.com'; ?></p>
                </div>
                <div class="py-1">
                    <a href="dashboard.php" class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors">
                        <i class="fa-solid fa-chart-pie w-4 text-slate-400 text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="setup.php" class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors">
                        <i class="fa-solid fa-database w-4 text-slate-400 text-xs"></i>
                        <span>Database Setup</span>
                    </a>
                    <a href="../index.php" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors">
                        <i class="fa-solid fa-globe w-4 text-slate-400 text-xs"></i>
                        <span>Live Website</span>
                    </a>
                </div>
                <div class="border-t border-slate-100 pt-1">
                    <a href="logout.php" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-xs"></i>
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
