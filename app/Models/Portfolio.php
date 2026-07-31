<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Accessor for map_url to automatically convert existing URLs on the fly
     * and save the healed URL to database.
     */
    public function getMapUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // If it's already a valid embed URL, return it
        if (strpos($value, '/maps/embed') !== false || strpos($value, 'output=embed') !== false) {
            return $value;
        }

        $parsed = $this->parseMapUrl($value);
        if ($parsed !== $value) {
            $this->attributes['map_url'] = $parsed;
            // Prevent event looping by saving quietly if needed, but basic save is fine
            try {
                $this->save();
            } catch (\Exception $e) {
                // Fail silently in case of DB read-only or locks
            }
        }

        return $parsed;
    }

    /**
     * Mutator for map_url to sanitize the URL before storing.
     */
    public function setMapUrlAttribute($value)
    {
        $this->attributes['map_url'] = $this->parseMapUrl($value);
    }

    /**
     * Helper to parse any Google Maps URL, iframe, or short link into an embed URL.
     */
    private function parseMapUrl($input)
    {
        $input = trim($input);
        if (empty($input)) {
            return '';
        }

        // 1. If it's a full iframe tag, extract the src attribute
        if (strpos($input, '<iframe') !== false) {
            if (preg_match('/src="([^"]+)"/', $input, $matches)) {
                return $matches[1];
            }
        }

        // 2. If it's a shortened URL, resolve it
        if (preg_match('/(maps\.app\.goo\.gl|goo\.gl\/maps)/i', $input)) {
            $resolved = $this->getFinalUrl($input);
            if ($resolved && $resolved !== $input) {
                $input = $resolved;
            }
        }

        // 3. If it's already an embed URL, return it
        if (strpos($input, '/maps/embed') !== false || strpos($input, 'output=embed') !== false) {
            return $input;
        }

        // 4. Try to convert standard google maps URL to embed format
        // Try to extract place name
        if (preg_match('/\/maps\/place\/([^\/]+)/i', $input, $matches)) {
            $place = urldecode($matches[1]);
            $place = str_replace('+', ' ', $place);
            return 'https://maps.google.com/maps?q=' . urlencode($place) . '&output=embed';
        }

        // Try to extract coordinates
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/i', $input, $matches)) {
            $lat = $matches[1];
            $lng = $matches[2];
            return "https://maps.google.com/maps?q={$lat},{$lng}&output=embed";
        }

        // Fallback: If it's a URL, wrap it in a search embed, otherwise return as is
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            return 'https://maps.google.com/maps?q=' . urlencode($input) . '&output=embed';
        }

        return $input;
    }

    /**
     * Resolves redirects for shortened URLs.
     */
    private function getFinalUrl($url)
    {
        if (!function_exists('curl_init')) {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'max_redirects' => 1,
                    'timeout' => 5,
                    'ignore_errors' => true
                ]
            ]);
            $headers = @get_headers($url, 1, $context);
            if ($headers) {
                if (isset($headers['Location'])) {
                    return is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                }
                if (isset($headers['location'])) {
                    return is_array($headers['location']) ? end($headers['location']) : $headers['location'];
                }
            }
            return $url;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        $info = curl_getinfo($ch);
        if ($info['http_code'] == 301 || $info['http_code'] == 302) {
            preg_match('/[Ll]ocation:\s*(.*)/', $response, $matches);
            if (isset($matches[1])) {
                return trim($matches[1]);
            }
        }
        return $url;
    }
}
