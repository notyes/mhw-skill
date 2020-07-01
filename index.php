<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use ActiveRecord\ActiveDatabase;

require 'vendor/autoload.php';

// Create Slim app
$app = new \Slim\App();

$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__.'/templates', [
        'cache' => __DIR__.'/templates/cache',
        'debug' => true,
    ]);

    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};
$container['db'] = function ($c) {

    $db_config = array(
        'hostname' => "128.199.199.133",
        'username' => "root",
        'password' => "cguvePtZRszJjk9qFmzBZif6r6drRq",
        'database' => "hunter_world",
        'dbdriver' => "mysqli",
        'pconnect' => FALSE,
        'db_debug' => TRUE
    );

    ActiveDatabase::addConfig("read", $db_config);

    //Use the named connection
    $db = ActiveDatabase::get("read");
    return $db;

};


// Define named route
$app->get('/', function ($request, $response, $args) {

    $url = $this->router->pathFor('skills');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('home');

$app->get('/skills', function ($request, $response, $args) {

    $query = $this->db->get( 'skills' );
    $dataSkills = $query->result();

    return $this->view->render($response, 'skills.twig', [
        'dataList' => $dataSkills,
        'list' => true,
    ]);

})->setName('skills');

$app->get('/skills_armor', function ($request, $response, $args) {

    $query = $this->db->get( 'skills_set' );
    $dataSkills = $query->result();

    return $this->view->render($response, 'skills.twig', [
        'dataList' => $dataSkills,
        'set' => true,
    ]);

})->setName('skills_armor');

$app->get('/skills_food', function ($request, $response, $args) {

    $query = $this->db->get( 'skills_food' );
    $dataSkills = $query->result();

    return $this->view->render($response, 'skills.twig', [
        'dataList' => $dataSkills,
        'food' => true,
    ]);

})->setName('skills_food');


// Run app
$app->run();
