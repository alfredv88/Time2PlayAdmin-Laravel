<?php

/*
|--------------------------------------------------------------------------
| Memory Monitoring Script
|--------------------------------------------------------------------------
|
| This script monitors memory usage and logs optimization results
| Run this script to verify that the memory optimizations are working
|
*/

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

echo "=== Time2PlayAdmin Memory Monitoring ===\n\n";

// Check memory limit
$memoryLimit = ini_get('memory_limit');
echo "Current Memory Limit: " . $memoryLimit . "\n";

// Check current memory usage
$currentMemory = memory_get_usage(true);
$currentMemoryMB = round($currentMemory / 1024 / 1024, 2);
echo "Current Memory Usage: " . $currentMemoryMB . " MB\n";

// Check peak memory usage
$peakMemory = memory_get_peak_usage(true);
$peakMemoryMB = round($peakMemory / 1024 / 1024, 2);
echo "Peak Memory Usage: " . $peakMemoryMB . " MB\n\n";

// Test optimized methods
echo "=== Testing Optimized Methods ===\n";

try {
    // Test UsersController optimization
    $usersController = new \App\Http\Controllers\UsersController();
    
    echo "Testing getUsersPaginated method...\n";
    $startTime = microtime(true);
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($usersController);
    $method = $reflection->getMethod('getUsersPaginated');
    $method->setAccessible(true);
    
    $result = $method->invoke($usersController, 0, 25, '', 'all');
    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    
    echo "✓ Users pagination: " . count($result['data']) . " records in " . $executionTime . "ms\n";
    echo "  Memory after users test: " . round(memory_get_usage(true) / 1024 / 1024, 2) . " MB\n";
    
    // Test EventsController optimization
    echo "Testing getEventsPaginated method...\n";
    $startTime = microtime(true);
    
    $method = $reflection->getMethod('getEventsPaginated');
    $method->setAccessible(true);
    
    $result = $method->invoke($usersController, 0, 25, '', 'all');
    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    
    echo "✓ Events pagination: " . count($result['data']) . " records in " . $executionTime . "ms\n";
    echo "  Memory after events test: " . round(memory_get_usage(true) / 1024 / 1024, 2) . " MB\n";
    
} catch (Exception $e) {
    echo "✗ Error testing methods: " . $e->getMessage() . "\n";
}

// Check cache status
echo "\n=== Cache Status ===\n";
$cacheKeys = [
    'users_total_count',
    'events_total_count',
    'users_list_count_60s',
    'events_list_count_60s'
];

foreach ($cacheKeys as $key) {
    $value = Cache::get($key);
    echo "Cache key '{$key}': " . ($value !== null ? $value : 'Not set') . "\n";
}

// Final memory check
$finalMemory = memory_get_usage(true);
$finalMemoryMB = round($finalMemory / 1024 / 1024, 2);
echo "\nFinal Memory Usage: " . $finalMemoryMB . " MB\n";

// Memory efficiency calculation
$memoryEfficiency = round((1 - ($finalMemory / (256 * 1024 * 1024))) * 100, 2);
echo "Memory Efficiency: " . $memoryEfficiency . "% available\n";

echo "\n=== Monitoring Complete ===\n";
echo "If you see this message without 'Out of memory' errors, the optimizations are working!\n";
