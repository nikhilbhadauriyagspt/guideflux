    <!-- Admin Footer & Common Interactive Scripts -->
    <script>
        // Toggle Mobile Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                }
            }
        }

        // Dropdown toggler
        function toggleDropdown(id) {
            const target = document.getElementById(id);
            if (!target) return;
            
            // Close other dropdowns
            ['notif-menu', 'user-menu'].forEach(menuId => {
                if (menuId !== id) {
                    const el = document.getElementById(menuId);
                    if (el) el.classList.add('hidden');
                }
            });

            target.classList.toggle('hidden');
        }

        // Click outside to close dropdowns
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#notif-btn') && !e.target.closest('#notif-menu')) {
                const notif = document.getElementById('notif-menu');
                if (notif) notif.classList.add('hidden');
            }
            if (!e.target.closest('#user-menu-btn') && !e.target.closest('#user-menu')) {
                const user = document.getElementById('user-menu');
                if (user) user.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
