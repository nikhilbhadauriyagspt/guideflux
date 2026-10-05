<?php
/**
 * GuideFlux Settings & Global Configuration Helper
 * Loads website branding, contact info, SMTP details, and OTP mode dynamically from database
 */
require_once __DIR__ . '/db.php';

function getGlobalSettings($reload = false) {
    static $settings = null;
    if ($settings !== null && !$reload) {
        return $settings;
    }

    $defaultSettings = [
        'site_name' => 'GuideFlux',
        'site_tagline' => 'Your Passport to Adventure',
        'site_phone' => '+91 98765 43210',
        'site_email' => 'support@guideflux.com',
        'site_whatsapp' => '919876543210',
        'site_address' => 'DLF Cyber City, Tower B, Gurugram, Haryana - 122002',
        'site_logo' => 'assets/images/logo/logo.webp',
        
        // SMTP / Mail Configuration
        'mail_mode' => 'testing', // 'testing' or 'live'
        'testing_otp' => '123456',
        'smtp_host' => 'smtp.example.com',
        'smtp_port' => '465',
        'smtp_user' => 'support@guideflux.com',
        'smtp_pass' => '',
        'smtp_encryption' => 'ssl', // 'ssl' or 'tls'
        'smtp_from_name' => 'GuideFlux Travel',
        'smtp_from_email' => 'support@guideflux.com',

        // Google & Facebook Social Login OAuth Configuration
        'google_login_active' => '0', // '1' or '0'
        'google_client_id' => '',
        'google_client_secret' => '',
        'facebook_login_active' => '0', // '1' or '0'
        'facebook_app_id' => '',
        'facebook_app_secret' => '',
        
        // Flight Search API & Commission / Markup Configuration
        'flight_api_provider' => 'simulator', // 'amadeus' or 'simulator'
        'amadeus_environment' => 'test', // 'test' or 'production'
        'amadeus_api_key' => '',
        'amadeus_api_secret' => '',
        'tbo_api_key' => '',
        'tripjack_api_key' => '',
        'flight_markup_type' => 'fixed', // 'fixed' (₹) or 'percentage' (%)
        'flight_commission_domestic' => '350', // e.g. ₹350 or 5%
        'flight_commission_international' => '850', // e.g. ₹850 or 8%
        'flight_convenience_fee' => '249', // e.g. ₹249 per ticket

        // Location & Places Search API Configuration
        'location_provider' => 'osm', // 'osm' (free OpenStreetMap) or 'google' (Google Places) or 'mapbox'
        'google_places_api_key' => '',
        'mapbox_api_key' => '',
        'locationiq_api_key' => '',

        // Payment Gateway API Configuration (Token Lock / Advance)
        'payment_gateway_provider' => 'razorpay', // 'razorpay', 'cashfree', 'stripe', 'offline'
        'payment_mode' => 'test', // 'test' or 'live'
        'razorpay_key_id' => '',
        'razorpay_key_secret' => '',
        'stripe_publishable_key' => '',
        'stripe_secret_key' => '',

        // Social links
        'social_facebook' => 'https://facebook.com',
        'social_instagram' => 'https://instagram.com',
        'social_twitter' => 'https://twitter.com',
        'social_youtube' => 'https://youtube.com',
        'social_linkedin' => 'https://linkedin.com',
    ];

    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM `settings`");
            while ($row = $stmt->fetch()) {
                $defaultSettings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // fallback to defaults if table not yet created
        }
    }

    $settings = $defaultSettings;
    return $settings;
}

/**
 * Shorthand helper for getting a single setting value
 */
function getSetting($key, $default = '') {
    $settings = getGlobalSettings();
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Helper to update multiple settings key-values
 */
function updateSettings(array $data) {
    $pdo = getDBConnection();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`)");
        foreach ($data as $k => $v) {
            $stmt->execute([$k, is_array($v) ? json_encode($v) : trim((string)$v)]);
        }
        // Reload cache
        getGlobalSettings(true);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
