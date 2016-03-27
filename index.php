<?php
require 'vendor/autoload.php';

// Create app
$app = new \Slim\App();

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        //'cache' => 'cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $twig = $view->getEnvironment();
    $twig->addGlobal('site', [
    	'title' => "EVs Calculator"
    ]);

    return $view;
};


require "router.php";

// Run app
$app->run();