<?php
session_start();

require_once __DIR__ . '/../config/db.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$isAjax = isset($_POST['ajax']) && $_POST['ajax'] === '1';

// Handle Login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $pdo = getDBConnection();
        $authenticated = false;
        $adminUser = null;

        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM `admins` WHERE `email` = ? AND `status` = 'active' LIMIT 1");
                $stmt->execute([$email]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password'])) {
                    $authenticated = true;
                    $adminUser = $admin;
                }
            } catch (Exception $e) {
                // Ignore and check fallback
            }
        }

        // Fallback default admin credentials
        if (!$authenticated && $email === 'admin@guideflux.com' && $password === 'admin123') {
            $authenticated = true;
            $adminUser = [
                'name' => 'Admin Manager',
                'email' => 'admin@guideflux.com',
                'role' => 'superadmin'
            ];
        }

        if ($authenticated && $adminUser) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = isset($adminUser['id']) ? $adminUser['id'] : 1;
            $_SESSION['admin_name'] = $adminUser['name'];
            $_SESSION['admin_email'] = $adminUser['email'];
            $_SESSION['admin_role'] = isset($adminUser['role']) ? $adminUser['role'] : 'superadmin';

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Login Successful! Welcome back.',
                    'admin_name' => $adminUser['name'],
                    'redirect' => 'dashboard.php'
                ]);
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = 'Invalid email address or password.';
        }
    }

    if ($isAjax && !empty($error)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $error
        ]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - GuideFlux</title>
    <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["'Plus Jakarta Sans'", 'sans-serif'],
                        space: ["'Space Grotesk'", 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            500: '#0d9488',
                            600: '#068285',  // GuideFlux Teal
                            700: '#07696c',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex items-center justify-center p-4 antialiased">

    <!-- Lightweight Animated Success Modal -->
    <div id="toast-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs hidden transition-all">
        <div class="bg-white rounded-2xl p-6 max-w-xs w-full border border-slate-200 text-center shadow-md transform scale-95 transition-transform duration-200" id="toast-card">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center text-xl mb-3 border border-emerald-100">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900">Signed In!</h3>
            <p class="text-xs text-slate-500 mt-1">Loading dashboard...</p>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-4 overflow-hidden">
                <div id="toast-progress" class="bg-brand-600 h-full w-0 transition-all duration-[900ms] ease-out"></div>
            </div>
        </div>
    </div>

    <!-- Clean, Simple, Light Login Box -->
    <div class="w-full max-w-sm">
        
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <a href="../index.php" class="inline-flex items-center gap-2.5 mb-3 group">
                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center text-base">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <span class="font-space font-bold text-2xl text-slate-900">
                    Guide<span class="text-brand-600">Flux</span>
                </span>
            </a>
            <p class="text-xs text-slate-500 font-medium">Admin Portal & Management</p>
        </div>

        <!-- Login Form Card (Clean & Flat with minimal border) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
            
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Sign in to account</h2>
                <p class="text-xs text-slate-400 mt-0.5">Enter your administrative credentials</p>
            </div>

            <!-- Error Banner -->
            <div id="error-banner" class="<?php echo !empty($error) ? '' : 'hidden'; ?> mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 flex items-center gap-2 text-rose-700 text-xs font-medium">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
                <span id="error-text"><?php echo htmlspecialchars($error); ?></span>
            </div>

            <form id="login-form" action="login.php" method="POST" class="space-y-4" onsubmit="handleAjaxLogin(event)">
                <input type="hidden" name="ajax" value="1">
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email address</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" id="email" name="email" required 
                               value="admin@guideflux.com"
                               placeholder="admin@guideflux.com" 
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-800 text-xs font-medium placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition-colors">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                        <button type="button" onclick="fillDemoCredentials()" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">
                            Auto Fill
                        </button>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" id="password" name="password" required 
                               value="admin123"
                               placeholder="••••••••" 
                               class="w-full pl-9 pr-9 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-800 text-xs font-medium placeholder:text-slate-400 focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition-colors">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i id="password-toggle-icon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500 accent-brand-600">
                        <span class="text-xs text-slate-600">Remember me</span>
                    </label>
                    <span class="text-[11px] text-slate-400">Default: admin123</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer">
                    <span id="btn-text">Sign In</span>
                    <i id="btn-icon" class="fa-solid fa-arrow-right text-[11px]"></i>
                </button>
            </form>

            <!-- Quick DB Setup link -->
            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <a href="setup.php" class="text-[11px] font-medium text-slate-500 hover:text-brand-600 inline-flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-database text-[10px]"></i>
                    <span>Database Installer / Migration</span>
                </a>
            </div>
        </div>

        <!-- Footer Link -->
        <div class="text-center mt-5">
            <a href="../index.php" class="text-xs font-medium text-slate-500 hover:text-slate-800 transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Website</span>
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function fillDemoCredentials() {
            document.getElementById('email').value = 'admin@guideflux.com';
            document.getElementById('password').value = 'admin123';
        }

        function handleAjaxLogin(e) {
            e.preventDefault();
            const form = document.getElementById('login-form');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnIcon = document.getElementById('btn-icon');
            const errorBanner = document.getElementById('error-banner');
            const errorText = document.getElementById('error-text');

            btnText.textContent = 'Verifying...';
            btnIcon.className = 'fa-solid fa-circle-notch fa-spin text-[11px]';
            submitBtn.disabled = true;
            errorBanner.classList.add('hidden');

            const formData = new FormData(form);

            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const toastModal = document.getElementById('toast-modal');
                    const toastCard = document.getElementById('toast-card');
                    const toastProgress = document.getElementById('toast-progress');
                    
                    toastModal.classList.remove('hidden');
                    setTimeout(() => {
                        toastCard.classList.remove('scale-95');
                        toastCard.classList.add('scale-100');
                        toastProgress.style.width = '100%';
                    }, 30);

                    setTimeout(() => {
                        window.location.href = data.redirect || 'dashboard.php';
                    }, 800);
                } else {
                    errorText.textContent = data.message || 'Invalid credentials.';
                    errorBanner.classList.remove('hidden');
                    btnText.textContent = 'Sign In';
                    btnIcon.className = 'fa-solid fa-arrow-right text-[11px]';
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                form.submit();
            });
        }
    </script>
</body>
</html>
