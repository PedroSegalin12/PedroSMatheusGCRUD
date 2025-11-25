<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\CarroController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\TestdriveController;
use App\Controllers\Admin\MontadoraController;
use App\Controllers\Admin\MotoController;
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
            $carros->addRoute('GET', '', [CarroController::class, 'index']);
            $carros->addRoute('GET', '/create', [CarroController::class, 'create']);
            $carros->addRoute('POST', '/store', [CarroController::class, 'store']);
            $carros->addRoute('GET', '/show', [CarroController::class, 'show']);
            $carros->addRoute('GET', '/edit', [CarroController::class, 'edit']);
            $carros->addRoute('POST', '/update', [CarroController::class, 'update']);
            $carros->addRoute('POST', '/delete', [CarroController::class, 'delete']);
        });

        // Motos
        $group->addGroup('/motos', function (FastRoute\RouteCollector $motos) {
            $motos->addRoute('GET', '', [MotoController::class, 'index']);
            $motos->addRoute('GET', '/create', [MotoController::class, 'create']);
            $motos->addRoute('POST', '/store', [MotoController::class, 'store']);
            $motos->addRoute('GET', '/show', [MotoController::class, 'show']);
            $motos->addRoute('GET', '/edit', [MotoController::class, 'edit']);
            $motos->addRoute('POST', '/update', [MotoController::class, 'update']);
            $motos->addRoute('POST', '/delete', [MotoController::class, 'delete']);
        });

        // Montadoras
        $group->addGroup('/Montadoras', function (FastRoute\RouteCollector $Montadoras) {
            $Montadoras->addRoute('GET', '', [MontadoraController::class, 'index']);
            $Montadoras->addRoute('GET', '/create', [MontadoraController::class, 'create']);
            $Montadoras->addRoute('POST', '/store', [MontadoraController::class, 'store']);
            $Montadoras->addRoute('GET', '/show', [MontadoraController::class, 'show']);
            $Montadoras->addRoute('GET', '/edit', [MontadoraController::class, 'edit']);
            $Montadoras->addRoute('POST', '/update', [MontadoraController::class, 'update']);
            $Montadoras->addRoute('POST', '/delete', [MontadoraController::class, 'delete']);
        });

        // Testdrives
        $group->addGroup('/testdrives', function (FastRoute\RouteCollector $testdrives) {
            $testdrives->addRoute('GET', '', [TestdriveController::class, 'index']);
            $testdrives->addRoute('GET', '/create', [TestdriveController::class, 'create']);
            $testdrives->addRoute('POST', '/store', [TestdriveController::class, 'store']);
            $testdrives->addRoute('GET', '/show', [TestdriveController::class, 'show']);
            $testdrives->addRoute('GET', '/edit', [TestdriveController::class, 'edit']);
            $testdrives->addRoute('POST', '/update', [TestdriveController::class, 'update']);
            $testdrives->addRoute('POST', '/delete', [TestdriveController::class, 'delete']);
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
