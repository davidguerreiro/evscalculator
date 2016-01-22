<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';


header("Access-Control-Allow-Origin: *");

$app = new \Slim\App;

require 'v1/api.php';

$app->run();