<?php

/*
|--------------------------------------------------------------------------
| Memory Optimization Bootstrap
|--------------------------------------------------------------------------
|
| This file sets memory optimization settings to prevent
| "Out of memory" errors in the Time2PlayAdmin application.
| Include this file in your bootstrap/app.php or index.php
|
*/

// Set memory limit to prevent out of memory errors
ini_set('memory_limit', '256M');

// Set execution time limit
ini_set('max_execution_time', 300);

// Set maximum input variables
ini_set('max_input_vars', 3000);

// Set upload limits
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '32M');

// Optimize garbage collection
if (function_exists('gc_enable')) {
    gc_enable();
}

// Set error reporting for production
if (env('APP_ENV') === 'production') {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Log memory optimization
if (function_exists('error_log')) {
    error_log('Memory optimization settings applied: ' . ini_get('memory_limit'));
}
