<?php

namespace Curtistinkers\HttpErrorPages;

use Symfony\Component\Yaml\Yaml;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigLoaderFilesystem;

require 'vendor/autoload.php';

// Set up Twig environment
$loader = new TwigLoaderFilesystem('templates');
$twig = new TwigEnvironment($loader);

// Load the template
$template = $twig->load('error-page.html.twig');

// Load YAML into array
$http_status_codes = Yaml::parseFile('http-status-codes.yaml');

// print_r($http_status_codes);

foreach ($http_status_codes as $category => $statuses) {
    // print_r($statuses);
    foreach ($statuses as $code => $detail) {
        if ($detail['enabled']) {
            // echo $detail['reason'];
            // echo $detail['description'];

            // Prepare the data
            $data = [
                'code' => $code,
                'reason' => $detail['reason'],
                'description' => $detail['description'],
            ];

            // Render the template
            $content = $template->render($data);

            // echo $content;

            file_put_contents("web/$code.html", $content);
        }
    }
}

?>
