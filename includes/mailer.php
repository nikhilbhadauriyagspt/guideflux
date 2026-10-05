<?php
/**
 * GuideFlux Email Sender Engine using Official PHPMailer Library
 * Compatible with cPanel Webmail, Hostinger, Gmail & Custom SMTP servers
 */
require_once __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send Email via PHPMailer
 */
function sendGuideFluxMail($toEmail, $toName, $subject, $htmlContent, $altBody = '') {
    $settings = getGlobalSettings();
    $mailMode = isset($settings['mail_mode']) ? $settings['mail_mode'] : 'testing';

    // In Testing Mode, return instant simulated success
    if ($mailMode === 'testing') {
        return [
            'success' => true,
            'mode' => 'testing',
            'testing_otp' => isset($settings['testing_otp']) ? $settings['testing_otp'] : '123456',
            'message' => 'Testing mode is ACTIVE. Use OTP: ' . (isset($settings['testing_otp']) ? $settings['testing_otp'] : '123456')
        ];
    }

    // LIVE PHPMailer Mode
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $settings['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $settings['smtp_user'];
        $mail->Password   = $settings['smtp_pass'];
        
        $port = (int)$settings['smtp_port'];
        $mail->Port = $port;

        $encryption = strtolower($settings['smtp_encryption']);
        if ($encryption === 'ssl' || $port === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls' || $port === 587) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
        }

        // SSL Certificate options (prevents self-signed or shared cPanel cert rejection)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->Timeout = 15;
        $mail->CharSet = 'UTF-8';

        // Sender & Recipient
        $fromEmail = !empty($settings['smtp_from_email']) ? $settings['smtp_from_email'] : $settings['smtp_user'];
        $fromName = !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : $settings['site_name'];

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo($fromEmail, $fromName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlContent;
        $mail->AltBody = !empty($altBody) ? $altBody : strip_tags($htmlContent);

        $mail->send();

        return [
            'success' => true,
            'mode' => 'live',
            'message' => 'Email sent successfully via PHPMailer!'
        ];

    } catch (Exception $e) {
        return [
            'success' => false,
            'mode' => 'live',
            'message' => 'PHPMailer Error: ' . $mail->ErrorInfo
        ];
    }
}

/**
 * Generate a luxury styled HTML email template for OTP
 */
function getOtpEmailTemplate($userName, $otpCode, $purpose = 'signup') {
    $siteName = getSetting('site_name', 'GuideFlux');
    $siteEmail = getSetting('site_email', 'support@guideflux.com');
    $sitePhone = getSetting('site_phone', '+91 98765 43210');
    
    $title = $purpose === 'forgot' ? 'Password Reset Verification' : 'Verify Your Email Address';
    $message = $purpose === 'forgot' 
        ? 'We received a request to reset your GuideFlux travel account password. Use the verification code below to set a new password.'
        : 'Welcome to ' . htmlspecialchars($siteName) . '! Please confirm your email address to activate your travel account and claim your ₹1,500 welcome discount.';

    return '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
            .card { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
            .header { background: linear-gradient(135deg, #068285 0%, #071f25 100%); padding: 32px 24px; text-align: center; color: #ffffff; }
            .content { padding: 32px 28px; text-align: center; }
            .otp-box { background: #f0fdfa; border: 2px dashed #0d9488; border-radius: 16px; padding: 18px 24px; display: inline-block; margin: 24px 0; }
            .otp-code { font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #068285; font-family: monospace; }
            .footer { background: #f8fafc; padding: 20px 24px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #f1f5f9; }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="header">
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">' . htmlspecialchars($siteName) . '</h1>
                <p style="margin: 6px 0 0 0; font-size: 13px; opacity: 0.85;">' . $title . '</p>
            </div>
            <div class="content">
                <h2 style="font-size: 18px; margin: 0 0 10px 0; color: #0f172a;">Hi ' . htmlspecialchars($userName) . ',</h2>
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin: 0;">' . $message . '</p>
                <div class="otp-box">
                    <div style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #0f766e; margin-bottom: 4px;">One-Time Verification Code</div>
                    <div class="otp-code">' . $otpCode . '</div>
                </div>
                <p style="font-size: 12px; color: #94a3b8; margin: 0;">This OTP is valid for <strong>10 minutes</strong>. Do not share it with anyone.</p>
            </div>
            <div class="footer">
                <p style="margin: 0 0 6px 0;">Need help? Contact our 24x7 Travel Concierge at <a href="mailto:' . htmlspecialchars($siteEmail) . '" style="color: #068285; text-decoration: none; font-weight: bold;">' . htmlspecialchars($siteEmail) . '</a> or call ' . htmlspecialchars($sitePhone) . '</p>
                <p style="margin: 0;">&copy; ' . date('Y') . ' ' . htmlspecialchars($siteName) . '. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';
}
