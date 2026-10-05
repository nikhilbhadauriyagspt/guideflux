<?php
/**
 * Admin Travelers & Registered Users Controller
 * Displays all registered users, email verification badges, signup date, last active, and status controls
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/settings.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$pdo = getDBConnection();
$alertMessage = '';
$alertType = 'success';

// Handle user status actions (Block, Activate, Delete)
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $act = $_GET['action'];

    if ($pdo && $userId > 0) {
        try {
            if ($act === 'block') {
                $pdo->prepare("UPDATE `users` SET `status` = 'blocked' WHERE `id` = ?")->execute([$userId]);
                $alertMessage = "User has been blocked successfully.";
            } elseif ($act === 'activate') {
                $pdo->prepare("UPDATE `users` SET `status` = 'active', `is_verified` = 1 WHERE `id` = ?")->execute([$userId]);
                $alertMessage = "User activated and verified.";
            } elseif ($act === 'delete') {
                $pdo->prepare("DELETE FROM `users` WHERE `id` = ?")->execute([$userId]);
                $alertMessage = "User record deleted.";
            }
        } catch (Exception $e) {
            $alertMessage = "Action failed: " . $e->getMessage();
            $alertType = 'error';
        }
    }
}

// Fetch users
$users = [];
$totalUsers = 0;
$verifiedUsers = 0;
$pendingUsers = 0;

if ($pdo) {
    try {
        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
        $verifiedUsers = (int)$pdo->query("SELECT COUNT(*) FROM `users` WHERE `is_verified` = 1")->fetchColumn();
        $pendingUsers = (int)$pdo->query("SELECT COUNT(*) FROM `users` WHERE `is_verified` = 0")->fetchColumn();

        $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (!empty($searchQuery)) {
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `name` LIKE ? OR `email` LIKE ? OR `phone` LIKE ? ORDER BY `id` DESC");
            $stmt->execute(["%$searchQuery%", "%$searchQuery%", "%$searchQuery%"]);
            $users = $stmt->fetchAll();
        } else {
            $users = $pdo->query("SELECT * FROM `users` ORDER BY `id` DESC")->fetchAll();
        }
    } catch (Exception $e) {
        // Fallback
    }
}

$pageTitle = "Registered Travelers & Users";
include 'components/head.php';
?>

<div class="min-h-screen flex bg-[#f8fafc] antialiased selection:bg-teal-600 selection:text-white">
    
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 xl:pl-72 flex flex-col min-w-0 transition-all">
        
        <!-- Header -->
        <?php include 'components/header.php'; ?>

        <!-- Content Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full space-y-6">

            <!-- Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-3xl bg-slate-900 text-white border border-slate-800">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-teal-300 text-xs font-semibold">
                        <i class="fa-solid fa-users"></i>
                        <span>Customer Management</span>
                    </div>
                    <h1 class="text-2xl font-bold font-space">Travelers &amp; Customer Accounts</h1>
                    <p class="text-xs text-slate-400">View real-time registered members, verified email statuses, and access control.</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-white/10 backdrop-blur rounded-2xl border border-white/10 text-center">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Total Users</div>
                        <div class="text-base font-bold text-white"><?php echo $totalUsers; ?></div>
                    </div>
                    <div class="px-4 py-2 bg-emerald-500/20 backdrop-blur rounded-2xl border border-emerald-500/30 text-center">
                        <div class="text-[10px] uppercase font-bold text-emerald-300">Verified</div>
                        <div class="text-base font-bold text-emerald-400"><?php echo $verifiedUsers; ?></div>
                    </div>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-4 rounded-2xl text-xs font-bold border <?php echo $alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'; ?>">
                    <i class="fa-solid <?php echo $alertType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?> mr-1.5"></i>
                    <?php echo htmlspecialchars($alertMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                
                <!-- Table Header & Search -->
                <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">All Registered Travelers</h2>
                        <p class="text-xs text-slate-500"><?php echo count($users); ?> accounts found in database</p>
                    </div>

                    <form action="users.php" method="GET" class="flex gap-2 max-w-sm w-full">
                        <div class="relative w-full">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Search by name, email, phone..."
                                   class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium focus:outline-none focus:border-brand-600 focus:bg-white">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Responsive Users Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 sm:px-6">Traveler / User</th>
                                <th class="py-3.5 px-4">Contact Phone</th>
                                <th class="py-3.5 px-4">Email Verification</th>
                                <th class="py-3.5 px-4">Account Status</th>
                                <th class="py-3.5 px-4">Joined Date</th>
                                <th class="py-3.5 px-4">Last Active</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                        <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 block"></i>
                                        No registered travelers found in database.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <!-- User info -->
                                        <td class="py-3.5 px-4 sm:px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0">
                                                    <?php echo substr($u['name'], 0, 1); ?>
                                                </div>
                                                <div class="truncate">
                                                    <p class="font-bold text-slate-900 text-xs truncate"><?php echo htmlspecialchars($u['name']); ?></p>
                                                    <span class="text-[11px] text-slate-400 truncate block"><?php echo htmlspecialchars($u['email']); ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Phone -->
                                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-800">
                                            <?php echo !empty($u['phone']) ? htmlspecialchars($u['phone']) : '<span class="text-slate-400 font-normal">Not provided</span>'; ?>
                                        </td>

                                        <!-- Verification badge -->
                                        <td class="py-3.5 px-4">
                                            <?php if ($u['is_verified'] == 1): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                                    <span>Verified</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i class="fa-solid fa-clock text-amber-600"></i>
                                                    <span>Pending OTP</span>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Account status -->
                                        <td class="py-3.5 px-4">
                                            <?php if ($u['status'] === 'active'): ?>
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Blocked
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Joined -->
                                        <td class="py-3.5 px-4 text-[11px] text-slate-500">
                                            <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                        </td>

                                        <!-- Last active -->
                                        <td class="py-3.5 px-4 text-[11px] text-slate-500">
                                            <?php echo !empty($u['last_login']) ? date('M d, h:i A', strtotime($u['last_login'])) : '<span class="text-slate-400">Never</span>'; ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3.5 px-4 sm:px-6 text-right space-x-1">
                                            <?php if ($u['status'] === 'active'): ?>
                                                <a href="users.php?action=block&user_id=<?php echo $u['id']; ?>" onclick="return confirm('Are you sure you want to block this user?');" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg text-xs font-bold inline-block" title="Block User">
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="users.php?action=activate&user_id=<?php echo $u['id']; ?>" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg text-xs font-bold inline-block" title="Unblock & Verify">
                                                    <i class="fa-solid fa-check"></i>
                                                </a>
                                            <?php endif; ?>

                                            <a href="users.php?action=delete&user_id=<?php echo $u['id']; ?>" onclick="return confirm('Permanently delete this user account?');" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold inline-block" title="Delete User">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>

        <?php include 'components/footer.php'; ?>
    </div>
</div>
