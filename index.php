<?php
include './vendor/autoload.php';
include "./libs/rb/connect.php";
$route = new \Klein\Klein();
$loader = new \Twig\Loader\FilesystemLoader(['./templates', './public']);
$twig = new \Twig\Environment($loader, [
//    'cache' => './cache',
]);
function errorSender()
{
    return json_encode(['code' => -1, 'message' => "Error"]);
}

function isLogged(): bool
{
    if (
        !empty($_SESSION['logged_user'])
        && ($_SESSION['logged_user']['user_login'] !== null
            || $_SESSION['logged_user']['user_login'] !== "")
    ) {
        return true;
    } else {
        return false;
    }
}

function callApi($method, $params = [])
{
    $host = $_SERVER['HTTP_HOST'];
    $curl = curl_init("http://$host/api/$method");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    return json_decode($response, true);
}

$route->with('/?', function () use ($route, $twig) {
    $route->get('/', function () use ($twig) {
        return $twig->render('/index.twig', [
            'logged' => isLogged(),
            'animelist' => callApi('animes.get')
        ]);
    });
    $route->get('/anime/[i:id]', function ($req) use ($twig) {
        return $twig->render('/animePage.twig', [
            'logged' => isLogged(),
            'anime_info' =>
                callApi('animes.getbyid',
                    ['anime_id' => $req->id])
        ]);
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

$route->onHttpError(function () {
    echo 'sd';
});

$route->dispatch();