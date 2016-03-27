<?php
require 'vendor/autoload.php';
require 'lib/EVsApi.php';

// Create app
$app = new \Slim\App();

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        //'cache' => 'cache'
        'debug' => true
    ]);
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $twig = $view->getEnvironment();
    $twig->addGlobal('site', [
    	'title' => "EVs Calculator",
    	'base_url' => "https://evscalculator.com",
    	'description' => "Professional pokemon training tool"
    ]);

    return $view;
};


require "router.php";

// Run app
$app->run();