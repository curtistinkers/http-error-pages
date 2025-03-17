<?php

use Twig\Environment as Twig_Environment;
use Twig\Loader\FilesystemLoader as Twig_Loader_Filesystem;

require 'vendor/autoload.php';

$location_root = '/usr/local/share/http-error-pages/web';

// Set up Twig environment
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

// Load the template
$template = $twig->load('nginx-location.conf.twig');

// Get HTTP status codes
$status_codes_json = file_get_contents('status-codes.json');
$status_codes_array = json_decode($status_codes_json, true);

$conf_builder = null;

foreach ($status_codes_array as $http_status_category) {
    foreach ($http_status_category as $code => $message) {

        // Prepare the data
        $data = [
            'status_code' => $code,
            'status_message' => $message,
            'location_root' => $location_root,
        ];

        // Render the template
        $content = $template->render($data);

        $conf_builder .= $content;

        
    }
}

echo $conf_builder;

// Write to a file
file_put_contents('custom-error-pages.conf', $conf_builder);

?>
