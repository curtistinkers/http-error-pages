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

// Load YAML file into \ArrayIterator
$http_status_codes = new \ArrayIterator(Yaml::parseFile('http-status-codes.yaml'));

while ($http_status_codes->valid()) {
    // Load $codes array into \ArrayIterator
    $statuses = new \ArrayIterator($http_status_codes->current());

    while ($statuses->valid()) {
        $enabled = $statuses->current()['enabled'];

        // Set remaining variables if enabled
        if ($enabled) {
            $code = $statuses->key();
            $reason = $statuses->current()['reason'];
            $description = $statuses->current()['description'];

            $filename = 'html/' . $code . '.html';

            // Display code and reason
            // echo $code . ' => ' . $reason . "\n";

            // Prepare the data for the template
            $data = [
                'code' => $code,
                'reason' => $reason,
                'description' => $description,
            ];

            // Render content
            $content = $template->render($data);
            
            file_put_contents($filename, $content); 
        }
        $statuses->next();
    }
    $http_status_codes->next();
}

?>
