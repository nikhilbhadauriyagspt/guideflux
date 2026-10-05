<?php
/**
 * Admin Comprehensive Settings & API Integration Controller
 * Modules:
 * 1. Website Branding & Logo Upload
 * 2. Flight Search API (Amadeus GDS / TBO / High-Fidelity Schedule Engine)
 * 3. Location & Places API (OpenStreetMap Nominatim / Photon vs Google Places API)
 * 4. Payment Gateway Integration (Razorpay / Stripe / Cashfree)
 * 5. SMTP & Webmail Email Delivery Configuration
 * 6. Social Login OAuth (Google & Facebook)
 * 7. Social Media Profile Links
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../includes/mailer.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$pdo = getDBConnection();
$alertMessage = '';
$alertType = 'success';
$activeTab = isset($_GET['tab']) ? trim($_GET['tab']) : 'branding';

// Handle Settings Update POST Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'save_general_settings') {
        $activeTab = 'branding';
        $postData = [
            'site_name' => trim($_POST['site_name'] ?? 'GuideFlux'),
            'site_tagline' => trim($_POST['site_tagline'] ?? ''),
            'site_phone' => trim($_POST['site_phone'] ?? ''),
            'site_whatsapp' => trim($_POST['site_whatsapp'] ?? ''),
            'site_email' => trim($_POST['site_email'] ?? ''),
            'site_address' => trim($_POST['site_address'] ?? ''),
            'site_logo' => trim($_POST['site_logo'] ?? 'assets/images/logo/logo.webp'),
        ];

        // Handle Logo File Upload
        if (isset($_FILES['site_logo_file']) && $_FILES['site_logo_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/logo/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['site_logo_file']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            if (in_array($ext, $allowedExts)) {
                $filename = 'logo_' . time() . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['site_logo_file']['tmp_name'], $targetPath)) {
                    $postData['site_logo'] = 'uploads/logo/' . $filename;
                }
            }
        }

        if (updateSettings($postData)) {
            $alertMessage = "Website branding details saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update branding settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_flight_settings') {
        $activeTab = 'flights';
        $postData = [
            'flight_api_provider' => trim($_POST['flight_api_provider'] ?? 'simulator'),
            'amadeus_environment' => trim($_POST['amadeus_environment'] ?? 'test'),
            'amadeus_api_key' => trim($_POST['amadeus_api_key'] ?? ''),
            'amadeus_api_secret' => trim($_POST['amadeus_api_secret'] ?? ''),
            'tbo_api_key' => trim($_POST['tbo_api_key'] ?? ''),
            'tripjack_api_key' => trim($_POST['tripjack_api_key'] ?? ''),
            'flight_markup_type' => trim($_POST['flight_markup_type'] ?? 'fixed'),
            'flight_commission_domestic' => (float)($_POST['flight_commission_domestic'] ?? 350),
            'flight_commission_international' => (float)($_POST['flight_commission_international'] ?? 850),
            'flight_convenience_fee' => (float)($_POST['flight_convenience_fee'] ?? 249),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "Flight API credentials and Commission / Markup rules saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update Flight API settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_location_settings') {
        $activeTab = 'location';
        $postData = [
            'location_provider' => trim($_POST['location_provider'] ?? 'osm'),
            'google_places_api_key' => trim($_POST['google_places_api_key'] ?? ''),
            'mapbox_api_key' => trim($_POST['mapbox_api_key'] ?? ''),
            'locationiq_api_key' => trim($_POST['locationiq_api_key'] ?? ''),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "Location & Places API configuration saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update Location API settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_payment_settings') {
        $activeTab = 'payments';
        $postData = [
            'payment_gateway_provider' => trim($_POST['payment_gateway_provider'] ?? 'razorpay'),
            'payment_mode' => trim($_POST['payment_mode'] ?? 'test'),
            'razorpay_key_id' => trim($_POST['razorpay_key_id'] ?? ''),
            'razorpay_key_secret' => trim($_POST['razorpay_key_secret'] ?? ''),
            'stripe_publishable_key' => trim($_POST['stripe_publishable_key'] ?? ''),
            'stripe_secret_key' => trim($_POST['stripe_secret_key'] ?? ''),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "Payment Gateway API keys and mode saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update Payment Gateway settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_smtp_settings') {
        $activeTab = 'email';
        $postData = [
            'mail_mode' => trim($_POST['mail_mode'] ?? 'testing'),
            'testing_otp' => trim($_POST['testing_otp'] ?? '123456'),
            'smtp_host' => trim($_POST['smtp_host'] ?? ''),
            'smtp_port' => trim($_POST['smtp_port'] ?? '465'),
            'smtp_user' => trim($_POST['smtp_user'] ?? ''),
            'smtp_pass' => trim($_POST['smtp_pass'] ?? ''),
            'smtp_encryption' => trim($_POST['smtp_encryption'] ?? 'ssl'),
            'smtp_from_name' => trim($_POST['smtp_from_name'] ?? 'GuideFlux Travel'),
            'smtp_from_email' => trim($_POST['smtp_from_email'] ?? ''),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "SMTP and Email configuration saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update SMTP settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_social_settings') {
        $activeTab = 'social_login';
        $postData = [
            'google_login_active' => isset($_POST['google_login_active']) && $_POST['google_login_active'] == '1' ? '1' : '0',
            'google_client_id' => trim($_POST['google_client_id'] ?? ''),
            'google_client_secret' => trim($_POST['google_client_secret'] ?? ''),
            'facebook_login_active' => isset($_POST['facebook_login_active']) && $_POST['facebook_login_active'] == '1' ? '1' : '0',
            'facebook_app_id' => trim($_POST['facebook_app_id'] ?? ''),
            'facebook_app_secret' => trim($_POST['facebook_app_secret'] ?? ''),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "Social login OAuth settings saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update social login settings.";
            $alertType = "error";
        }

    } elseif ($action === 'save_social_links') {
        $activeTab = 'social_links';
        $postData = [
            'social_facebook' => trim($_POST['social_facebook'] ?? ''),
            'social_instagram' => trim($_POST['social_instagram'] ?? ''),
            'social_twitter' => trim($_POST['social_twitter'] ?? ''),
            'social_youtube' => trim($_POST['social_youtube'] ?? ''),
            'social_linkedin' => trim($_POST['social_linkedin'] ?? ''),
        ];

        if (updateSettings($postData)) {
            $alertMessage = "Social media profile links saved successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to update social links.";
            $alertType = "error";
        }

    } elseif ($action === 'test_smtp_mail') {
        $activeTab = 'email';
        $testEmail = isset($_POST['test_email']) ? trim($_POST['test_email']) : '';
        if (empty($testEmail)) {
            $alertMessage = "Please enter an email address to send test email.";
            $alertType = "error";
        } else {
            $testHtml = getOtpEmailTemplate('Admin Tester', '987654', 'signup');
            $res = sendGuideFluxMail($testEmail, 'Admin Tester', 'GuideFlux SMTP Test Email', $testHtml);
            if ($res['success']) {
                $alertMessage = "Test email sent successfully! (" . $res['message'] . ")";
                $alertType = "success";
            } else {
                $alertMessage = "SMTP Error: " . $res['message'];
                $alertType = "error";
            }
        }
    }
}

// Fetch fresh settings from DB
$settings = getGlobalSettings(true);
$pageTitle = "System & API Settings";
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
                        <i class="fa-solid fa-sliders"></i>
                        <span>Configuration &amp; API Manager</span>
                    </div>
                    <h1 class="text-2xl font-bold font-space">System &amp; API Settings</h1>
                    <p class="text-xs text-slate-400">Manage Flight API, OpenStreetMap / Google Places, Payment Gateways, SMTP, OAuth, and Branding.</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs px-3 py-1.5 rounded-xl font-bold <?php echo ($settings['mail_mode'] ?? 'testing') === 'testing' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'; ?>">
                        <i class="fa-solid <?php echo ($settings['mail_mode'] ?? 'testing') === 'testing' ? 'fa-vial' : 'fa-bolt'; ?> mr-1"></i>
                        Mail: <?php echo strtoupper($settings['mail_mode'] ?? 'testing'); ?>
                    </span>
                    <span class="text-xs px-3 py-1.5 rounded-xl font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                        <i class="fa-solid fa-map-pin mr-1"></i>
                        Places: <?php echo strtoupper($settings['location_provider'] ?? 'osm'); ?>
                    </span>
                </div>
            </div>

            <?php if (!empty($alertMessage)): ?>
                <div class="p-4 rounded-2xl text-xs font-bold border flex items-center justify-between <?php echo $alertType === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'; ?>">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid <?php echo $alertType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?> text-sm"></i>
                        <span><?php echo htmlspecialchars($alertMessage); ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none border-b border-slate-200">
                <button type="button" onclick="switchSettingsTab('branding')" id="tabBtn-branding" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'branding' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-paintbrush text-xs"></i>
                    <span>Website Branding</span>
                </button>
                <button type="button" onclick="switchSettingsTab('flights')" id="tabBtn-flights" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'flights' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-plane-departure text-xs"></i>
                    <span>Flight Search API</span>
                </button>
                <button type="button" onclick="switchSettingsTab('location')" id="tabBtn-location" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'location' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Places &amp; Maps API</span>
                </button>
                <button type="button" onclick="switchSettingsTab('payments')" id="tabBtn-payments" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'payments' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-credit-card text-xs"></i>
                    <span>Payment Gateways</span>
                </button>
                <button type="button" onclick="switchSettingsTab('email')" id="tabBtn-email" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'email' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-envelope text-xs"></i>
                    <span>SMTP &amp; Email</span>
                </button>
                <button type="button" onclick="switchSettingsTab('social_login')" id="tabBtn-social_login" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'social_login' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-users-rectangle text-xs"></i>
                    <span>Social Login OAuth</span>
                </button>
                <button type="button" onclick="switchSettingsTab('social_links')" id="tabBtn-social_links" class="settings-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap <?php echo $activeTab === 'social_links' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'; ?>">
                    <i class="fa-solid fa-share-nodes text-xs"></i>
                    <span>Social Media Links</span>
                </button>
            </div>

            <!-- ===================================================================
                 TAB 1: WEBSITE BRANDING & CONTACT
            ==================================================================== -->
            <div id="tabPanel-branding" class="settings-panel <?php echo $activeTab === 'branding' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Website Branding &amp; Contact Details</h2>
                            <p class="text-xs text-slate-500">Logo, brand title, contact numbers, and office addresses</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-paintbrush"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=branding" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="save_general_settings">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Brand / Site Name</label>
                            <input type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-600">
                            <span class="text-[11px] text-slate-400 mt-1 block">Displayed across navbar, footer, emails, invoices, and vouchers.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline / Slogan</label>
                            <input type="text" name="site_tagline" value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>"
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-brand-600">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Helpline Phone Number</label>
                                <input type="text" name="site_phone" value="<?php echo htmlspecialchars($settings['site_phone'] ?? ''); ?>"
                                       class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-600">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">WhatsApp Number (with Country Code)</label>
                                <input type="text" name="site_whatsapp" value="<?php echo htmlspecialchars($settings['site_whatsapp'] ?? ''); ?>" placeholder="919876543210"
                                       class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-brand-600">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Official Support Email</label>
                            <input type="email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>"
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-brand-600">
                        </div>

                        <!-- Logo Configuration -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Website Logo</label>
                            
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-16 bg-white rounded-xl border border-slate-200 flex items-center justify-center p-2 shrink-0">
                                    <img src="../<?php echo htmlspecialchars($settings['site_logo'] ?? 'assets/images/logo/logo.webp'); ?>" 
                                         alt="Current Logo" 
                                         class="max-h-full max-w-full object-contain"
                                         onerror="this.src='../assets/images/logo/logo.webp'">
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="site_logo_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                    <span class="text-[10px] text-slate-400 block">Upload PNG, WebP, SVG or JPG (Recommended: Transparent PNG or WebP)</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Or Direct Logo File Path / URL</label>
                                <input type="text" name="site_logo" value="<?php echo htmlspecialchars($settings['site_logo'] ?? 'assets/images/logo/logo.webp'); ?>"
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Head Office Address</label>
                            <textarea name="site_address" rows="2" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:border-brand-600"><?php echo htmlspecialchars($settings['site_address'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Branding Details
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===================================================================
                 TAB 2: FLIGHT SEARCH API CONFIGURATION
            ==================================================================== -->
            <div id="tabPanel-flights" class="settings-panel <?php echo $activeTab === 'flights' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Flight Search API &amp; GDS Aggregator</h2>
                            <p class="text-xs text-slate-500">Configure Amadeus GDS API, TBO, TripJack, and High-Fidelity Flight Simulation Engine</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=flights" method="POST" class="space-y-5">
                        <input type="hidden" name="action" value="save_flight_settings">

                        <!-- Flight API Engine Switch -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Flight Search Engine</label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="flight_api_provider" value="simulator" <?php echo ($settings['flight_api_provider'] ?? 'simulator') === 'simulator' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">High-Fidelity Engine (Free &amp; Always Up)</span>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">Real schedules for IndiGo, Air India, Akasa Air, Vistara, Emirates, SpiceJet with 3-tier fare matrix.</span>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="flight_api_provider" value="amadeus" <?php echo ($settings['flight_api_provider'] ?? 'simulator') === 'amadeus' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">Amadeus GDS Live API</span>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">Connects live to Amadeus Flight Offers API v2 for real-time worldwide flight GDS inventory.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Amadeus GDS API Credentials -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg bg-sky-100 text-sky-800 font-mono font-bold text-xs">Amadeus</span>
                                    <span class="text-xs font-bold text-slate-800">Amadeus Self-Service API Credentials</span>
                                </div>
                                <a href="https://developers.amadeus.com/register" target="_blank" class="text-xs text-brand-600 font-bold hover:underline inline-flex items-center gap-1">
                                    <span>Get Free API Key</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Amadeus Environment</label>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="radio" name="amadeus_environment" value="test" <?php echo ($settings['amadeus_environment'] ?? 'test') === 'test' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <span>Test / Sandbox (<code>test.api.amadeus.com</code>) - Free</span>
                                    </label>
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="radio" name="amadeus_environment" value="production" <?php echo ($settings['amadeus_environment'] ?? 'test') === 'production' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <span>Production (<code>api.amadeus.com</code>) - Live</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Amadeus API Key (Client ID)</label>
                                    <input type="text" name="amadeus_api_key" value="<?php echo htmlspecialchars($settings['amadeus_api_key'] ?? ''); ?>" placeholder="Enter Amadeus Client ID / API Key"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Amadeus API Secret (Client Secret)</label>
                                    <input type="password" name="amadeus_api_secret" value="<?php echo htmlspecialchars($settings['amadeus_api_secret'] ?? ''); ?>" placeholder="••••••••"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Aggregators (TBO / TripJack) -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-4">
                            <span class="text-xs font-bold text-slate-800 block">Indian B2B Flight Aggregator API Keys (Optional)</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">TBO Holidays Flight API Key</label>
                                    <input type="text" name="tbo_api_key" value="<?php echo htmlspecialchars($settings['tbo_api_key'] ?? ''); ?>" placeholder="TBO API Key (Optional)"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">TripJack Flight API Key</label>
                                    <input type="text" name="tripjack_api_key" value="<?php echo htmlspecialchars($settings['tripjack_api_key'] ?? ''); ?>" placeholder="TripJack API Key (Optional)"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                                </div>
                            </div>
                        </div>

                        <!-- 💰 Flight Markup & Admin Commission Configuration Card -->
                        <div class="p-6 rounded-2xl border-2 border-emerald-200 bg-emerald-50/40 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-emerald-200/80">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                                        <i class="fa-solid fa-hand-holding-dollar"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900">Flight Commission &amp; Profit Markup Rules</h3>
                                        <p class="text-[11px] text-slate-500">Define your earnings added on top of the airline/GDS base fare</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-600 text-white">Your Direct Profit</span>
                            </div>

                            <!-- Commission Type (Fixed ₹ vs Percentage %) -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Commission Calculation Method</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2.5 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 transition">
                                        <input type="radio" name="flight_markup_type" value="fixed" <?php echo ($settings['flight_markup_type'] ?? 'fixed') === 'fixed' ? 'checked' : ''; ?> class="accent-emerald-600">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Fixed Flat Amount (₹ per ticket)</span>
                                            <span class="block text-[10px] text-slate-500">e.g. Add flat ₹350 on every domestic booking</span>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-2.5 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 transition">
                                        <input type="radio" name="flight_markup_type" value="percentage" <?php echo ($settings['flight_markup_type'] ?? 'fixed') === 'percentage' ? 'checked' : ''; ?> class="accent-emerald-600">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Percentage Markup (% on fare)</span>
                                            <span class="block text-[10px] text-slate-500">e.g. Add 5% profit margin on flight total</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Domestic, International & Convenience Fee Inputs -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Domestic Flights Commission</label>
                                    <div class="relative">
                                        <input type="number" step="any" name="flight_commission_domestic" value="<?php echo htmlspecialchars($settings['flight_commission_domestic'] ?? '350'); ?>" required
                                               class="w-full pl-8 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-emerald-600">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">₹ / %</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 mt-1 block">DEL, BOM, BLR, GOA, etc.</span>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">International Flights Commission</label>
                                    <div class="relative">
                                        <input type="number" step="any" name="flight_commission_international" value="<?php echo htmlspecialchars($settings['flight_commission_international'] ?? '850'); ?>" required
                                               class="w-full pl-8 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-emerald-600">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">₹ / %</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 mt-1 block">Dubai, Bangkok, Singapore, Bali</span>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Convenience Fee (Per Passenger)</label>
                                    <div class="relative">
                                        <input type="number" step="any" name="flight_convenience_fee" value="<?php echo htmlspecialchars($settings['flight_convenience_fee'] ?? '249'); ?>" required
                                               class="w-full pl-7 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-bold text-slate-800 focus:outline-none focus:border-emerald-600">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">₹</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 mt-1 block">Standard ticketing fee</span>
                                </div>
                            </div>

                            <!-- Live Calculation Preview Example -->
                            <div class="p-3.5 rounded-xl bg-white border border-emerald-200/80 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="font-bold text-slate-800 block"><i class="fa-solid fa-calculator text-emerald-600 mr-1.5"></i>Example Customer Fare Breakdown:</span>
                                    <span class="text-[11px] text-slate-500">Airline Net Fare (₹4,000) + Your Commission (₹350) + Fee (₹249)</span>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[10px] uppercase font-bold text-emerald-700 block">Customer Pays: ₹4,599</span>
                                    <span class="text-xs font-black text-slate-900">Your Profit: <span class="text-emerald-600">+₹599 / ticket</span></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Flight API &amp; Commission Configuration
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===================================================================
                 TAB 3: LOCATION & PLACES AUTOCOMPLETE API
            ==================================================================== -->
            <div id="tabPanel-location" class="settings-panel <?php echo $activeTab === 'location' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Places, Cities &amp; Maps Autocomplete API</h2>
                            <p class="text-xs text-slate-500">Powers live destination searches in hero bar, hotel add manager, and package builder</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=location" method="POST" class="space-y-5">
                        <input type="hidden" name="action" value="save_location_settings">

                        <!-- Provider Switch -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Location Data Provider</label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="location_provider" value="osm" <?php echo ($settings['location_provider'] ?? 'osm') === 'osm' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-slate-800">OpenStreetMap Photon / Nominatim</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">100% Free</span>
                                        </div>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">No API Key required. Instant city &amp; landmark geocoding worldwide.</span>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 p-3.5 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="location_provider" value="google" <?php echo ($settings['location_provider'] ?? 'osm') === 'google' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-bold text-slate-800">Google Places API</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800">Google Maps</span>
                                        </div>
                                        <span class="block text-[11px] text-slate-500 mt-0.5">Requires Google Cloud Maps/Places API Key for precise establishment lookup.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Google Maps / Places API Key -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fa-brands fa-google text-rose-500"></i>
                                    <span>Google Places / Maps API Key</span>
                                </span>
                                <a href="https://console.cloud.google.com/google/maps-apis" target="_blank" class="text-xs text-brand-600 font-bold hover:underline inline-flex items-center gap-1">
                                    <span>Google Cloud Console</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                            <input type="password" name="google_places_api_key" value="<?php echo htmlspecialchars($settings['google_places_api_key'] ?? ''); ?>" placeholder="AIzaSy..."
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                        </div>

                        <!-- Mapbox / LocationIQ (Optional) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Mapbox Public Access Token (Optional)</label>
                                <input type="text" name="mapbox_api_key" value="<?php echo htmlspecialchars($settings['mapbox_api_key'] ?? ''); ?>" placeholder="pk.eyJ1..."
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">LocationIQ Private Key (Optional)</label>
                                <input type="text" name="locationiq_api_key" value="<?php echo htmlspecialchars($settings['locationiq_api_key'] ?? ''); ?>" placeholder="pk.98765..."
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Places &amp; Location API Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===================================================================
                 TAB 4: PAYMENT GATEWAY CONFIGURATION
            ==================================================================== -->
            <div id="tabPanel-payments" class="settings-panel <?php echo $activeTab === 'payments' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Payment Gateway &amp; Token Lock API</h2>
                            <p class="text-xs text-slate-500">Collect Token Advance (e.g. ₹2,500) and instant online booking payments via UPI / Cards / Netbanking</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=payments" method="POST" class="space-y-5">
                        <input type="hidden" name="action" value="save_payment_settings">

                        <!-- Gateway Selection -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Payment Gateway Provider</label>
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                        <input type="radio" name="payment_mode" value="test" <?php echo ($settings['payment_mode'] ?? 'test') === 'test' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <span>Test / Sandbox</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                        <input type="radio" name="payment_mode" value="live" <?php echo ($settings['payment_mode'] ?? 'test') === 'live' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <span>Live Production</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="payment_gateway_provider" value="razorpay" <?php echo ($settings['payment_gateway_provider'] ?? 'razorpay') === 'razorpay' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">Razorpay (India UPI/Cards)</span>
                                        <span class="block text-[10px] text-slate-400">GPay, PhonePe, Paytm, Cards</span>
                                    </div>
                                </label>

                                <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="payment_gateway_provider" value="stripe" <?php echo ($settings['payment_gateway_provider'] ?? 'razorpay') === 'stripe' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">Stripe (Global Cards)</span>
                                        <span class="block text-[10px] text-slate-400">International cards &amp; USD/EUR</span>
                                    </div>
                                </label>

                                <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="payment_gateway_provider" value="offline" <?php echo ($settings['payment_gateway_provider'] ?? 'razorpay') === 'offline' ? 'checked' : ''; ?> class="mt-0.5 accent-brand-600">
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">Offline / Pay at Hotel</span>
                                        <span class="block text-[10px] text-slate-400">Book with 0 advance</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Razorpay API Keys -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">Razorpay API Keys</span>
                                <a href="https://dashboard.razorpay.com/app/keys" target="_blank" class="text-xs text-brand-600 font-bold hover:underline inline-flex items-center gap-1">
                                    <span>Razorpay Dashboard</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Razorpay Key ID</label>
                                    <input type="text" name="razorpay_key_id" value="<?php echo htmlspecialchars($settings['razorpay_key_id'] ?? ''); ?>" placeholder="rzp_test_... or rzp_live_..."
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Razorpay Key Secret</label>
                                    <input type="password" name="razorpay_key_secret" value="<?php echo htmlspecialchars($settings['razorpay_key_secret'] ?? ''); ?>" placeholder="••••••••"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 focus:outline-none focus:border-brand-600">
                                </div>
                            </div>
                        </div>

                        <!-- Stripe API Keys -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-4">
                            <span class="text-xs font-bold text-slate-800 block">Stripe API Keys</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Stripe Publishable Key</label>
                                    <input type="text" name="stripe_publishable_key" value="<?php echo htmlspecialchars($settings['stripe_publishable_key'] ?? ''); ?>" placeholder="pk_test_..."
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Stripe Secret Key</label>
                                    <input type="password" name="stripe_secret_key" value="<?php echo htmlspecialchars($settings['stripe_secret_key'] ?? ''); ?>" placeholder="••••••••"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Payment Gateway Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===================================================================
                 TAB 5: SMTP & EMAIL DELIVERY SETTINGS
            ==================================================================== -->
            <div id="tabPanel-email" class="settings-panel <?php echo $activeTab === 'email' ? '' : 'hidden'; ?>">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 max-w-5xl">
                    
                    <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Email &amp; SMTP Webmail Settings</h2>
                                <p class="text-xs text-slate-500">Configure cPanel Webmail / SMTP host and Testing Mode</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </div>

                        <form action="settings.php?tab=email" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="save_smtp_settings">

                            <!-- Mail Mode Switch (Testing vs Live) -->
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Email Delivery Mode</label>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                        <input type="radio" name="mail_mode" value="testing" <?php echo ($settings['mail_mode'] ?? 'testing') === 'testing' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Testing Mode</span>
                                            <span class="block text-[10px] text-slate-500">Fixed OTP (123456) for instant test</span>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-brand-500 transition">
                                        <input type="radio" name="mail_mode" value="live" <?php echo ($settings['mail_mode'] ?? 'testing') === 'live' ? 'checked' : ''; ?> class="accent-brand-600">
                                        <div>
                                            <span class="block text-xs font-bold text-slate-800">Live SMTP</span>
                                            <span class="block text-[10px] text-slate-500">Sends real OTP via PHPMailer</span>
                                        </div>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Testing Fixed OTP Code</label>
                                    <input type="text" name="testing_otp" value="<?php echo htmlspecialchars($settings['testing_otp'] ?? '123456'); ?>" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono font-bold text-slate-800">
                                </div>
                            </div>

                            <!-- Live SMTP Credentials -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">SMTP Host</label>
                                    <input type="text" name="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>" placeholder="mail.yourdomain.com"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Port</label>
                                    <input type="text" name="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? '465'); ?>" placeholder="465"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">SMTP Username / Email</label>
                                    <input type="text" name="smtp_user" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>" placeholder="support@yourdomain.com"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">SMTP Password</label>
                                    <input type="password" name="smtp_pass" value="<?php echo htmlspecialchars($settings['smtp_pass'] ?? ''); ?>" placeholder="••••••••"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Encryption</label>
                                    <select name="smtp_encryption" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800">
                                        <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? 'ssl') === 'ssl' ? 'selected' : ''; ?>>SSL (Port 465)</option>
                                        <option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'ssl') === 'tls' ? 'selected' : ''; ?>>TLS / STARTTLS (Port 587)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">From Sender Name</label>
                                    <input type="text" name="smtp_from_name" value="<?php echo htmlspecialchars($settings['smtp_from_name'] ?? 'GuideFlux Travel'); ?>"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">From Sender Email</label>
                                <input type="email" name="smtp_from_email" value="<?php echo htmlspecialchars($settings['smtp_from_email'] ?? ($settings['site_email'] ?? '')); ?>"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800">
                            </div>

                            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                                Save SMTP &amp; Mail Settings
                            </button>
                        </form>
                    </div>

                    <!-- Live SMTP Test Card -->
                    <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4 h-fit">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-teal-600"></i>
                            <span>Send Live Test Email</span>
                        </h3>
                        <p class="text-xs text-slate-500">Send an instant test OTP email to verify your SMTP setup is working.</p>

                        <form action="settings.php?tab=email" method="POST" class="space-y-3">
                            <input type="hidden" name="action" value="test_smtp_mail">
                            <input type="email" name="test_email" required placeholder="Enter test email address..."
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-brand-600">
                            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition active:scale-95 cursor-pointer">
                                Test Send OTP Email
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- ===================================================================
                 TAB 6: SOCIAL LOGIN OAUTH (GOOGLE & FACEBOOK)
            ==================================================================== -->
            <div id="tabPanel-social_login" class="settings-panel <?php echo $activeTab === 'social_login' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Google &amp; Facebook 1-Click Social Sign In</h2>
                            <p class="text-xs text-slate-500">Enable instant 1-click customer login with Google and Facebook OAuth</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-users-rectangle"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=social_login" method="POST" class="space-y-5">
                        <input type="hidden" name="action" value="save_social_settings">

                        <!-- Google OAuth Details -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fa-brands fa-google text-rose-500 text-base"></i>
                                    <span class="text-xs font-bold text-slate-900">Google 1-Click Sign In</span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="google_login_active" value="0">
                                    <input type="checkbox" name="google_login_active" value="1" <?php echo ($settings['google_login_active'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-4 h-4 text-brand-600 rounded accent-brand-600">
                                    <span class="text-xs font-bold text-slate-700">Active</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase">Google Client ID</label>
                                <input type="text" name="google_client_id" value="<?php echo htmlspecialchars($settings['google_client_id'] ?? ''); ?>" placeholder="123456-xxx.apps.googleusercontent.com"
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase">Google Client Secret</label>
                                <input type="password" name="google_client_secret" value="<?php echo htmlspecialchars($settings['google_client_secret'] ?? ''); ?>" placeholder="GOCSPX-xxxxxx"
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                            <div class="text-[10px] text-slate-400 bg-white p-2 rounded-lg border border-slate-200/80">
                                <strong>Authorized Redirect URI:</strong> <code><?php echo ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://") . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000'); ?>/api/google-callback.php</code>
                            </div>
                        </div>

                        <!-- Facebook OAuth Details -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fa-brands fa-facebook text-blue-600 text-base"></i>
                                    <span class="text-xs font-bold text-slate-900">Facebook 1-Click Sign In</span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="facebook_login_active" value="0">
                                    <input type="checkbox" name="facebook_login_active" value="1" <?php echo ($settings['facebook_login_active'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-4 h-4 text-brand-600 rounded accent-brand-600">
                                    <span class="text-xs font-bold text-slate-700">Active</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase">Facebook App ID</label>
                                <input type="text" name="facebook_app_id" value="<?php echo htmlspecialchars($settings['facebook_app_id'] ?? ''); ?>" placeholder="123456789012345"
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase">Facebook App Secret</label>
                                <input type="password" name="facebook_app_secret" value="<?php echo htmlspecialchars($settings['facebook_app_secret'] ?? ''); ?>" placeholder="••••••••"
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl font-mono text-slate-800">
                            </div>
                            <div class="text-[10px] text-slate-400 bg-white p-2 rounded-lg border border-slate-200/80">
                                <strong>Authorized Redirect URI:</strong> <code><?php echo ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://") . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000'); ?>/api/facebook-callback.php</code>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Social Login Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===================================================================
                 TAB 7: SOCIAL MEDIA PROFILE LINKS
            ==================================================================== -->
            <div id="tabPanel-social_links" class="settings-panel <?php echo $activeTab === 'social_links' ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5 max-w-4xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Social Media Profile Links</h2>
                            <p class="text-xs text-slate-500">Links shown on header, footer and customer communications</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-share-nodes"></i>
                        </div>
                    </div>

                    <form action="settings.php?tab=social_links" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="save_social_links">

                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-facebook text-sm"></i>
                                </div>
                                <input type="url" name="social_facebook" value="<?php echo htmlspecialchars($settings['social_facebook'] ?? ''); ?>" placeholder="https://facebook.com/yourpage"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-instagram text-sm"></i>
                                </div>
                                <input type="url" name="social_instagram" value="<?php echo htmlspecialchars($settings['social_instagram'] ?? ''); ?>" placeholder="https://instagram.com/yourprofile"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-x-twitter text-sm"></i>
                                </div>
                                <input type="url" name="social_twitter" value="<?php echo htmlspecialchars($settings['social_twitter'] ?? ''); ?>" placeholder="https://twitter.com/yourhandle"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-youtube text-sm"></i>
                                </div>
                                <input type="url" name="social_youtube" value="<?php echo htmlspecialchars($settings['social_youtube'] ?? ''); ?>" placeholder="https://youtube.com/@yourchannel"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-linkedin text-sm"></i>
                                </div>
                                <input type="url" name="social_linkedin" value="<?php echo htmlspecialchars($settings['social_linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/company/yourcompany"
                                       class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 cursor-pointer">
                            Save Social Links
                        </button>
                    </form>
                </div>
            </div>

        </main>

        <?php include 'components/footer.php'; ?>
    </div>
</div>

<script>
function switchSettingsTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.settings-panel').forEach(el => el.classList.add('hidden'));
    // Show active panel
    const activePanel = document.getElementById('tabPanel-' + tabName);
    if (activePanel) activePanel.classList.remove('hidden');

    // Reset button styles
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('bg-brand-600', 'text-white');
        btn.classList.add('bg-white', 'text-slate-700', 'hover:bg-slate-100', 'border', 'border-slate-200');
    });

    // Highlight active button
    const activeBtn = document.getElementById('tabBtn-' + tabName);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-slate-700', 'hover:bg-slate-100', 'border', 'border-slate-200');
        activeBtn.classList.add('bg-brand-600', 'text-white');
    }

    // Update URL hash/query without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.replaceState({}, '', url);
}
</script>
