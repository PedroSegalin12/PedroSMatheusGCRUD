<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Csrf;
use App\Core\Flash;
use App\Repositories\TestdriveRepository;
use App\Services\TestdriveService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TestdriveController
{
    private View $view;
    private TestdriveRepository $repo;
    private TestdriveService $service;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new TestdriveRepository();
        $this->service = new TestdriveService();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $testdrives = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);

        $html = $this->view->render('admin/testdrives/index', compact('testdrives', 'page', 'pages'));
        return new Response($html);
    }

    public function create(): Response
    {
        $userRepo = new \App\Repositories\UserRepository();
        $carroRepo = new \App\Repositories\CarroRepository();
        $users = $userRepo->findAll();
        $carros = $carroRepo->findAll();
        
        $html = $this->view->render('admin/testdrives/create', [
            'csrf' => Csrf::token(), 
            'errors' => [],
            'old' => [],
            'users' => $users,
            'carros' => $carros
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $userRepo = new \App\Repositories\UserRepository();
            $carroRepo = new \App\Repositories\CarroRepository();
            $users = $userRepo->findAll();
            $carros = $carroRepo->findAll();
            
            $html = $this->view->render('admin/testdrives/create', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => $request->request->all(),
                'users' => $users,
                'carros' => $carros
            ]);
            return new Response($html, 422);
        }

        $testdrive = $this->service->make($request->request->all());
        $id = $this->repo->create($testdrive);

        return new RedirectResponse('/admin/testdrives/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $testdrive = $this->repo->find($id);
        if (!$testdrive) return new Response('Testdrive não encontrado', 404);

        $html = $this->view->render('admin/testdrives/show', ['testdrive' => $testdrive]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $testdrive = $this->repo->find($id);
        if (!$testdrive) return new Response('Testdrive não encontrado', 404);

        $userRepo = new \App\Repositories\UserRepository();
        $carroRepo = new \App\Repositories\CarroRepository();
        $users = $userRepo->findAll();
        $carros = $carroRepo->findAll();
        
        // Converter objeto para array se necessário
        $testdriveArray = is_object($testdrive) ? [
            'id' => $testdrive->id,
            'id_user' => $testdrive->id_user,
            'id_carro' => $testdrive->id_carro,
            'data_testdrive' => $testdrive->data_testdrive,
            'data_devolucao' => $testdrive->data_devolucao,
            'status' => $testdrive->status
        ] : $testdrive;

        $html = $this->view->render('admin/testdrives/edit', [
            'testdrive' => $testdriveArray,
            'csrf' => Csrf::token(),
            'errors' => [],
            'users' => $users,
            'carros' => $carros
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);

        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $userRepo = new \App\Repositories\UserRepository();
            $carroRepo = new \App\Repositories\CarroRepository();
            $users = $userRepo->findAll();
            $carros = $carroRepo->findAll();
            
            $existing = $this->repo->find((int)$data['id']);
            $testdriveArray = is_object($existing) ? [
                'id' => $existing->id,
                'id_user' => $existing->id_user,
                'id_carro' => $existing->id_carro,
                'data_testdrive' => $existing->data_testdrive,
                'data_devolucao' => $existing->data_devolucao,
                'status' => $existing->status
            ] : $existing;
            
            $html = $this->view->render('admin/testdrives/edit', [
                'testdrive' => array_merge($testdriveArray, $data),
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'users' => $users,
                'carros' => $carros
            ]);
            return new Response($html, 422);
        }

        $testdrive = $this->service->make($data);
        if (!$testdrive->id) return new Response('ID inválido', 422);

        $this->repo->update($testdrive);
        return new RedirectResponse('/admin/testdrives/show?id=' . $testdrive->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $id = (int)$request->request->get('id', 0);
        if ($id === 0) {
            Flash::push("danger", "ID do testdrive inválido.");
            return new RedirectResponse('/admin/testdrives');
        }

        $this->repo->delete($id);
        Flash::push("success", "Testdrive excluído com sucesso!");
        return new RedirectResponse('/admin/testdrives');
    }
}
