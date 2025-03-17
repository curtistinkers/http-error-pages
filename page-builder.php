<?php

use Twig\Environment as Twig_Environment;
use Twig\Loader\FilesystemLoader as Twig_Loader_Filesystem;

require 'vendor/autoload.php';

// Set up Twig environment
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

// Load the template
$template = $twig->load('error-page.html.twig');

// Get HTTP status codes
$status_codes_json = file_get_contents('status-codes.json');
$status_codes_array = json_decode($status_codes_json, true);


foreach ($status_codes_array as $http_status_category) {
    foreach ($http_status_category as $code => $message) {
        // Create the output filename
        $output_file = 'web/' . $code . '.html';

        // Display a message
        echo "$code: $message (file: $output_file) \n";

        // Prepare the data
        $data = [
            'status_code' => $code,
            'status_message' => $message,
        ];

        // Render the template
        $content = $template->render($data);

        // Write to a file
        file_put_contents($output_file, $content);
    }
}

//print_r($status_array['4xx']);

?>
