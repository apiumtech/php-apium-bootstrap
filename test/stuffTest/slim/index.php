<?php

require_once dirname(__FILE__).'/../libs/EasyRdf/EasyRdf.php';

// Prepare app
$app = new \Slim\Slim(
    array('negotiation.enabled' => true
));

// Setup routes
$app->get('/', function () use ($app) {

    $format = "html";

    switch($format) {
        case 'html':
            return $app->redirect('http://www.aelius.com/njh/', 303);
        default:
            $rootUrl = $app->request()->getUrl() . $app->request()->getScriptName();
            return $app->redirect("$rootUrl/foaf.$format", 303);
    }
});

$app->get('/foaf:format', function () use ($app) {
    $format = "html";

    $uri = $app->request()->getUrl() . $app->request()->getPath();
    var_dump($format);
    printf($uri);
    $foaf = new EasyRdf_Graph($uri);
    $foaf->parseFile(__DIR__ . '/foaf.ttl', 'turtle', $uri);
    $app->response()->body( $foaf->serialise($format) );
});

// Run app
$app->run();