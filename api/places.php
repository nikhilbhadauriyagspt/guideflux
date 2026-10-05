<?php
/**
 * Places / Locations Live Autocomplete Proxy API
 * Supports:
 * 1. OpenStreetMap Photon API (Default, 100% Free & Unlimited)
 * 2. Google Places API (When Google Key is enabled in settings)
 */
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config/settings.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if (strlen($query) < 2) {
    echo json_encode(['success' => true, 'data' => []]);
    exit();
}

$settings = getGlobalSettings();
$provider = isset($settings['location_provider']) ? $settings['location_provider'] : 'osm';
$googleKey = isset($settings['google_places_api_key']) ? trim($settings['google_places_api_key']) : '';

$results = [];

// 1. If Google Places API is selected and key is provided
if ($provider === 'google' && !empty($googleKey)) {
    $googleUrl = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?' . http_build_query([
        'input' => $query,
        'key' => $googleKey,
        'types' => 'geocode|establishment'
    ]);

    $ch = curl_init($googleUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['predictions'])) {
        foreach ($data['predictions'] as $pred) {
            $results[] = [
                'name' => $pred['structured_formatting']['main_text'] ?? $pred['description'],
                'full_address' => $pred['description'],
                'city' => $pred['terms'][count($pred['terms'])-2]['value'] ?? '',
                'country' => $pred['terms'][count($pred['terms'])-1]['value'] ?? '',
                'lat' => null,
                'lon' => null,
                'source' => 'google'
            ];
        }
    }
}

// 2. Default & Fallback: OpenStreetMap Photon Live API (100% Free)
if (empty($results)) {
    $osmUrl = 'https://photon.komoot.io/api/?' . http_build_query([
        'q' => $query,
        'limit' => 8
    ]);

    $ch = curl_init($osmUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'GuideFlux Travel Portal / 2.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['features']) && is_array($data['features'])) {
        foreach ($data['features'] as $feat) {
            $props = $feat['properties'] ?? [];
            $coords = $feat['geometry']['coordinates'] ?? [0, 0];

            $name = $props['name'] ?? '';
            $city = $props['city'] ?? $props['town'] ?? $props['state'] ?? $name;
            $state = $props['state'] ?? '';
            $country = $props['country'] ?? '';
            
            $addressParts = array_filter([$name, $city, $state, $country]);
            $fullAddress = implode(', ', array_unique($addressParts));

            $results[] = [
                'name' => $name,
                'full_address' => $fullAddress,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'lat' => $coords[1] ?? null,
                'lon' => $coords[0] ?? null,
                'source' => 'osm'
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'provider' => ($provider === 'google' && !empty($googleKey)) ? 'google' : 'osm',
    'data' => $results
]);
