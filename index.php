<?php
require 'vendor/autoload.php';
require 'lib/EVsApi.php';

// Create app
$app = new \Slim\App([
    'settings'  => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
    ]
]);

$STATS = [
    'hp' => 'HP', 
    'attack' => 'Attack', 
    'defense' => 'Defense', 
    'spattack' =>  'Special attack', 
    'spdefense' => 'Special defense', 
    'speed' => 'Speed'
];

$GAMES = [
    0 => 'X/Y',
    1 => 'ORAS'
];


// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    global $STATS, $GAMES;
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
    	'description' => "Professional pokemon training tool",
        "email" => "social@evscalculator.com",
        "twitter" => "evscalculator"
    ]);
    $twig->addGlobal('stats', $STATS);
    $twig->addGlobal('games', $GAMES);
    $twig->addFilter( new Twig_SimpleFilter('cast_to_array', function ($stdClassObject) {
        $response = array();
        foreach ($stdClassObject as $key => $value) {
            $response[] = array($key, $value);
        }
        return $response;
    }));

    return $view;
};


require "router.php";

// Run app
$app->run();