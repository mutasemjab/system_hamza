<?php

// Application settings helper — auto-loaded by composer
// Add any global setting functions here as needed.

if (!function_exists('appSetting')) {
    function appSetting(string $key, $default = null)
    {
        return $default;
    }
}
