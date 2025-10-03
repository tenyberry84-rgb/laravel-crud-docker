<?php

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load config
$config = require __DIR__ . '/config/database.php';
define('DB_HOST', $config['host']);
define('DB_NAME', $config['database']);
define('DB_USER', $config['username']);
define('DB_PASS', $config['password']);
