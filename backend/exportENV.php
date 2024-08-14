<?php

require_once __DIR__ .'../../vendor/autoload.php';


// Load environment variables from .env in local development
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

