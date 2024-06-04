<?php

require '../vendor/autoload.php'; // If you're using Composer (recommended)
          
use Cloudinary\Cloudinary;
          
$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name' => 'dvrnpdfnp',
            'api_key'    => '493436548692596',
            'api_secret' => 'PX1hOaRlLOQwqleR7cPm6gufVQw',
        ],
    ]
);