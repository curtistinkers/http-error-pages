<?php

namespace Curtistinkers\HttpErrorPages;

use Symfony\Component\Yaml\Yaml;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigLoaderFilesystem;

require 'vendor/autoload.php';

$location_root = '/usr/local/share/http-error-pages/web';

// Set up Twig environment
$loader = new TwigLoaderFilesystem('templates');
$twig = new TwigEnvironment($loader);

// Load the template
$template = $twig->load('nginx-location.conf.twig');

$conf_builder = <<<EOT
    ## Add custom error pages to NGINX
    ##

    EOT;

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

            $filename = 'web/' . $code . '.html';

            // Display code and reason
            // echo $code . ' => ' . $reason . "\n";

            // Prepare the data for the template
            $data = [
                'code' => $code,
                'reason' => $reason,
                'location' => $location_root,
            ];

            // Render the template
            $content = $template->render($data);

            $conf_builder .= $content;
        }
        $statuses->next();
    }
    $http_status_codes->next();
}

// echo $conf_builder;

// Write to a file
file_put_contents('nginx-error-pages.conf', $conf_builder);

?>
