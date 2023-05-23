<?php
spl_autoload_register(function($class) {
    $prefixes = [
        'General' => '/',
        'Controllers' => '/../controllers/',
        'Psr\\Cache' => 'Psr/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = __DIR__ . $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require_once $file;
                break;
            }
        }
    }
});