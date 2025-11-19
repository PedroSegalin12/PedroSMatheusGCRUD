<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\carroController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\testdriveController;
use App\Controllers\Admin\montadoraController;
use App\Controllers\Admin\motoController;
use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Middleware\AuthMiddleware;
use Symfony\Component\HttpFoundation\Request;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {

    // Index Site
    $routeCollector->addGroup('/', function (FastRoute\RouteCollector $site) {
        $site->addRoute('GET', '', [SiteController::class, 'index']);
    });

    // Autenticação
    $routeCollector->addGroup('/auth', function (FastRoute\RouteCollector $auth) {
        $auth->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
        $auth->addRoute('GET', '/create', [AuthController::class, 'create']);
        $auth->addRoute('POST', '/login', [AuthController::class, 'login']);
        $auth->addRoute('POST', '/logout', [AuthController::class, 'logout']);
    });

    $routeCollector->addGroup('/admin', function (FastRoute\RouteCollector $group) {

        // Home Admin
        $group->addGroup('', function (FastRoute\RouteCollector $admin) {
            $admin->addRoute('GET', '', [AdminController::class, 'index']);
        });

        // Carros
        $group->addGroup('/carros', function (FastRoute\RouteCollector $carros) {
            $carros->addRoute('GET', '', [carroController::class, 'index']);
            $carros->addRoute('GET', '/create', [carroController::class, 'create']);
            $carros->addRoute('POST', '/store', [carroController::class, 'store']);
            $carros->addRoute('GET', '/show', [carroController::class, 'show']);
            $carros->addRoute('GET', '/edit', [carroController::class, 'edit']);
            $carros->addRoute('POST', '/update', [carroController::class, 'update']);
            $carros->addRoute('POST', '/delete', [carroController::class, 'delete']);
        });

        // Motos
        $group->addGroup('/motos', function (FastRoute\RouteCollector $motos) {
            $motos->addRoute('GET', '', [motoController::class, 'index']);
            $motos->addRoute('GET', '/create', [motoController::class, 'create']);
            $motos->addRoute('POST', '/store', [motoController::class, 'store']);
            $motos->addRoute('GET', '/show', [motoController::class, 'show']);
            $motos->addRoute('GET', '/edit', [motoController::class, 'edit']);
            $motos->addRoute('POST', '/update', [motoController::class, 'update']);
            $motos->addRoute('POST', '/delete', [motoController::class, 'delete']);
        });

        // Montadoras
        $group->addGroup('/montadoras', function (FastRoute\RouteCollector $montadoras) {
            $montadoras->addRoute('GET', '', [montadoraController::class, 'index']);
            $montadoras->addRoute('GET', '/create', [montadoraController::class, 'create']);
            $montadoras->addRoute('POST', '/store', [montadoraController::class, 'store']);
            $montadoras->addRoute('GET', '/show', [montadoraController::class, 'show']);
            $montadoras->addRoute('GET', '/edit', [montadoraController::class, 'edit']);
            $montadoras->addRoute('POST', '/update', [montadoraController::class, 'update']);
            $montadoras->addRoute('POST', '/delete', [montadoraController::class, 'delete']);
        });

        // Testdrives
        $group->addGroup('/testdrives', function (FastRoute\RouteCollector $testdrives) {
            $testdrives->addRoute('GET', '', [testdriveController::class, 'index']);
            $testdrives->addRoute('GET', '/create', [testdriveController::class, 'create']);
            $testdrives->addRoute('POST', '/store', [testdriveController::class, 'store']);
            $testdrives->addRoute('GET', '/show', [testdriveController::class, 'show']);
            $testdrives->addRoute('GET', '/edit', [testdriveController::class, 'edit']);
            $testdrives->addRoute('POST', '/update', [testdriveController::class, 'update']);
            $testdrives->addRoute('POST', '/delete', [testdriveController::class, 'delete']);
        });

        // Usuários
        $group->addGroup('/users', function (FastRoute\RouteCollector $users) {
            $users->addRoute('GET', '', [UserController::class, 'index']);
            $users->addRoute('GET', '/create', [UserController::class, 'create']);
            $users->addRoute('POST', '/store', [UserController::class, 'store']);
            $users->addRoute('GET', '/show', [UserController::class, 'show']);
            $users->addRoute('POST', '/delete', [UserController::class, 'delete']);
        });
    });
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) $uri = substr($uri, 0, $pos);
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$request = Request::createFromGlobals();

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $controller = new $class();

        // Módulos protegidos
        $protectedRoutes = [
            '/admin',
        ];

        // Se a rota começar com alguma dessas, exige login
        foreach ($protectedRoutes as $prefix) {
            if (str_starts_with($uri, $prefix)) {
                $redirect = AuthMiddleware::requireLogin();
                if ($redirect) { $redirect->send(); exit; }
                break;
            }
        }

        $response = $controller->$method($request);
        $response->send();
        break;
}
