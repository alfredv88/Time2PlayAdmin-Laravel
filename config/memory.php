<?php

/*
|--------------------------------------------------------------------------
| Memory Optimization Configuration
|--------------------------------------------------------------------------
|
| This file contains memory optimization settings to prevent
| "Out of memory" errors in the Time2PlayAdmin application.
|
*/

return [
    
    /*
    |--------------------------------------------------------------------------
    | Memory Limit
    |--------------------------------------------------------------------------
    |
    | Set memory limit to prevent out of memory errors.
    | Default Laravel memory limit is often too low for Firebase operations.
    |
    */
    
    'memory_limit' => env('MEMORY_LIMIT', '256M'),
    
    /*
    |--------------------------------------------------------------------------
    | Execution Time
    |--------------------------------------------------------------------------
    |
    | Maximum execution time for scripts to prevent timeouts.
    |
    */
    
    'max_execution_time' => env('MAX_EXECUTION_TIME', 300),
    
    /*
    |--------------------------------------------------------------------------
    | Input Variables
    |--------------------------------------------------------------------------
    |
    | Maximum number of input variables for forms and requests.
    |
    */
    
    'max_input_vars' => env('MAX_INPUT_VARS', 3000),
    
    /*
    |--------------------------------------------------------------------------
    | Upload Limits
    |--------------------------------------------------------------------------
    |
    | Maximum file upload size and POST data size.
    |
    */
    
    'post_max_size' => env('POST_MAX_SIZE', '64M'),
    'upload_max_filesize' => env('UPLOAD_MAX_FILESIZE', '32M'),
    
    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    |
    | Settings for optimizing database queries and Firebase operations.
    |
    */
    
    'query_optimization' => [
        'default_page_size' => 25,
        'max_page_size' => 100,
        'cache_ttl' => 300, // 5 minutes
        'batch_size' => 50,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Firebase Optimization
    |--------------------------------------------------------------------------
    |
    | Settings for optimizing Firebase operations.
    |
    */
    
    'firebase_optimization' => [
        'batch_size' => 25,
        'timeout' => 30,
        'retry_attempts' => 3,
        'cache_results' => true,
    ],
];
