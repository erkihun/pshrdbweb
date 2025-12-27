<?php

// Provide a safe entry point for optional helper files so Composer scripts do not fail when the file is missing.
$helpers = __DIR__ . '/../app/helpers.php';

if (file_exists($helpers)) {
    require_once $helpers;
}
