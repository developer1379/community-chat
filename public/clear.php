<?php

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once __DIR__.'/../bootstrap/app.php';

// Bootstrap the Console Kernel to initialize configuration and facades
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Clearing Laravel Cache Bypassing Routes</h2>";

try {
    Artisan::call('config:clear');
    echo "Config cache cleared!<br>";
    
    Artisan::call('route:clear');
    echo "Route cache cleared!<br>";
    
    Artisan::call('view:clear');
    echo "View cache cleared!<br>";
    
    Artisan::call('cache:clear');
    echo "App cache cleared!<br>";
    
    echo "<h3 style='color:green;'>All Laravel caches cleared successfully! Please delete clear.php after use.</h3>";
} catch (\Exception $e) {
    echo "<h3 style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
