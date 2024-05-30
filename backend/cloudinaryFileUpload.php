<?php

require '../vendor/autoload.php'; // If you're using Composer (recommended)
          
use Cloudinary\Cloudinary;
          
$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name' => 'dptlknkgn',
            'api_key'    => '818552648835784',
            'api_secret' => '_ebVLP3oDBN2M3vcC_tqkKs5h6s',
        ],
    ]
);