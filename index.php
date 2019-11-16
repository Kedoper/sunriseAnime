<?php
include './vendor/autoload.php';
include "./libs/rb/connect.php";
$route = new \Klein\Klein();
$loader = new \Twig\Loader\FilesystemLoader(['./templates', './public']);
$twig = new \Twig\Environment($loader, [
//    'cache' => '/path/to/compilation_cache',
]);

function errorSender()
{
    return json_encode(['code' => -1, 'message' => "Error"]);
}

$route->with('/?', function () use ($route, $twig) {
    $route->get('/', function () use ($twig) {
        return $twig->render('/index.twig', ['logged' => false]);
    });
});

$route->with('/api', function () use ($route) {
    $route->post('/animes.[*:method]', function ($req) {
        if (file_exists("./api/animes/{$req->method}.php")) {
            include "./api/animes/{$req->method}.php";
        } else {
            die(errorSender());
        }
    });
});

$route->dispatch();