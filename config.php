<?php
require 'db.php'; // Assuming you have a database connection here
require 'vendor/autoload.php'; // Make sure to install the required libraries using Composer

// Create a new Google Client object
$googleClient = new Google_Client();
$googleClient->setClientId('');
$googleClient->setClientSecret('');
$googleClient->setRedirectUri('http://localhost/callback.php');
$googleClient->addScope('email');
$googleClient->addScope('profile');

// Disable SSL verification for development (not recommended in production)
$googleClient->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

// Generate the Google login URL
$loginUrl = $googleClient->createAuthUrl();
?>
