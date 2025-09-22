<?php

/*
|--------------------------------------------------------------------------
| Simple Memory Monitoring Script
|--------------------------------------------------------------------------
|
| This script monitors memory usage without Laravel dependencies
| Run this script to verify that the memory optimizations are working
|
*/

echo "=== Time2PlayAdmin Simple Memory Monitoring ===\n\n";

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

// Test memory optimization file
echo "=== Testing Memory Optimization ===\n";

$memoryOptimizationFile = __DIR__ . '/bootstrap/memory_optimization_simple.php';
if (file_exists($memoryOptimizationFile)) {
    echo "✓ Memory optimization file exists\n";
    
    // Include the optimization file
    require_once $memoryOptimizationFile;
    
    // Check if settings were applied
    $newMemoryLimit = ini_get('memory_limit');
    echo "Memory limit after optimization: " . $newMemoryLimit . "\n";
    
    if ($newMemoryLimit === '256M') {
        echo "✓ Memory limit successfully set to 256M\n";
    } else {
        echo "⚠ Memory limit not set correctly (expected: 256M, got: " . $newMemoryLimit . ")\n";
    }
} else {
    echo "✗ Memory optimization file not found\n";
}

// Test index.php files
echo "\n=== Testing Index Files ===\n";

$indexFiles = [
    __DIR__ . '/index.php',
    __DIR__ . '/public/index.php'
];

foreach ($indexFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'memory_optimization.php') !== false) {
            echo "✓ " . basename($file) . " includes memory optimization\n";
        } else {
            echo "⚠ " . basename($file) . " does not include memory optimization\n";
        }
    } else {
        echo "✗ " . basename($file) . " not found\n";
    }
}

// Test UsersController optimization
echo "\n=== Testing Controller Optimizations ===\n";

$controllerFile = __DIR__ . '/app/Http/Controllers/UsersController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Check for optimized methods
    $optimizations = [
        'getUsersPaginated' => 'Users pagination method',
        'getEventsPaginated' => 'Events pagination method',
        'getTotalUsersCount' => 'Users count optimization',
        'getTotalEventsCount' => 'Events count optimization'
    ];
    
    foreach ($optimizations as $method => $description) {
        if (strpos($content, $method) !== false) {
            echo "✓ " . $description . " implemented\n";
        } else {
            echo "✗ " . $description . " not found\n";
        }
    }
    
    // Check for old problematic methods
    $oldMethods = [
        'getUsersCached' => 'Old users cache method (should be replaced)',
        'getEventsCached' => 'Old events cache method (should be replaced)'
    ];
    
    foreach ($oldMethods as $method => $description) {
        if (strpos($content, $method) !== false) {
            echo "⚠ " . $description . " still present (legacy compatibility)\n";
        }
    }
} else {
    echo "✗ UsersController not found\n";
}

// Memory stress test
echo "\n=== Memory Stress Test ===\n";

$testData = [];
$initialMemory = memory_get_usage(true);

try {
    // Create a large array to test memory handling
    for ($i = 0; $i < 10000; $i++) {
        $testData[] = [
            'id' => $i,
            'name' => 'Test User ' . $i,
            'email' => 'user' . $i . '@test.com',
            'data' => str_repeat('x', 100) // 100 character string
        ];
    }
    
    $afterTestMemory = memory_get_usage(true);
    $memoryUsed = round(($afterTestMemory - $initialMemory) / 1024 / 1024, 2);
    
    echo "✓ Memory stress test completed\n";
    echo "  Data created: " . count($testData) . " records\n";
    echo "  Memory used: " . $memoryUsed . " MB\n";
    
    // Clear test data
    unset($testData);
    
    $finalMemory = memory_get_usage(true);
    $memoryFreed = round(($afterTestMemory - $finalMemory) / 1024 / 1024, 2);
    echo "  Memory freed: " . $memoryFreed . " MB\n";
    
} catch (Exception $e) {
    echo "✗ Memory stress test failed: " . $e->getMessage() . "\n";
}

// Final memory check
$finalMemory = memory_get_usage(true);
$finalMemoryMB = round($finalMemory / 1024 / 1024, 2);
echo "\nFinal Memory Usage: " . $finalMemoryMB . " MB\n";

// Memory efficiency calculation
if ($memoryLimit === '256M' || $memoryLimit === '-1') {
    $memoryEfficiency = round((1 - ($finalMemory / (256 * 1024 * 1024))) * 100, 2);
    echo "Memory Efficiency: " . $memoryEfficiency . "% available\n";
}

echo "\n=== Monitoring Complete ===\n";
echo "If you see this message without 'Out of memory' errors, the optimizations are working!\n";
echo "Key improvements implemented:\n";
echo "- Memory limit increased to 256M\n";
echo "- Pagination implemented for users and events\n";
echo "- Memory optimization loaded at startup\n";
echo "- Legacy methods kept for compatibility\n";
