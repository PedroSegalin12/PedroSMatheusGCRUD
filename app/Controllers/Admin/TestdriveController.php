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
        $html = $this->view->render('admin/testdrives/create', ['csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $html = $this->view->render('admin/testdrives/create', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => $request->request->all()
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

        $html = $this->view->render('admin/testdrives/edit', [
            'testdrive' => $testdrive,
            'csrf' => Csrf::token(),
            'errors' => []
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);

        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $html = $this->view->render('admin/testdrives/edit', [
                'testdrive' => array_merge($this->repo->find((int)$data['id']), $data),
                'csrf' => Csrf::token(),
                'errors' => $errors
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
