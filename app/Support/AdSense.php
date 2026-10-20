<?php

namespace App\Support;

use App\Models\Setting;

/** Google AdSense publisher ID from settings, normalised to "pub-1234567890123456" (null when unset or malformed). */
class AdSense
{
    public static function publisherId(): ?string
    {
        $id = trim((string) Setting::get('adsense_publisher_id'));

        return preg_match('/^(?:ca-)?(pub-\d{10,20})$/i', $id, $m) ? strtolower($m[1]) : null;
    }
}
