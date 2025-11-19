<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\testdriveRepository;
use App\Services\testdriveservice; // Alterado de AutorService
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Renomeado de AutorController para testdriveController
class testdriveController
{
    private View $view;
    private testdriveRepository $repo;
    private testdriveservice $service; // Usando testdriveservice

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new testdriveRepository();
        $this->service = new testdriveservice(); // Instancia testdriveservice
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        // Variável renomeada para $testdrives
        $testdrives = $this->repo->paginate($page, $perPage); 
        $pages = (int)ceil($total / $perPage);
        // Caminho da view alterado
        $html = $this->view->render('admin/testdrives/index', compact('testdrives', 'page', 'pages')); 
        return new Response($html);
    }

    public function create(): Response
    {
        // Caminho da view alterado
        $html = $this->view->render('admin/testdrives/create', ['csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        
        $errors = $this->service->validate($request->request->all());
        
        if ($errors) {
            // Caminho da view alterado
            $html = $this->view->render('admin/testdrives/create', ['csrf' => Csrf::token(), 'errors' => $errors, 'old' => $request->request->all()]);
            return new Response($html, 422);
        }
        
        // $testdrive é o objeto retornado pelo service
        $testdrive = $this->service->make($request->request->all());
        $id = $this->repo->create($testdrive);
        
        // Redirecionamento alterado
        return new RedirectResponse('/admin/testdrives/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        // Busca o empréstimo pelo ID
        $testdrive = $this->repo->find($id); 
        if (!$testdrive) return new Response('Empréstimo não encontrado', 404);
        // Caminho da view alterado
        $html = $this->view->render('admin/testdrives/show', ['testdrive' => $testdrive]); 
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        // Busca o empréstimo pelo ID
        $testdrive = $this->repo->find($id); 
        if (!$testdrive) return new Response('Empréstimo não encontrado', 404);
        // Caminho da view alterado
        $html = $this->view->render('admin/testdrives/edit', ['testdrive' => $testdrive, 'csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        
        if ($errors) {
            // Caminho da view alterado
            $html = $this->view->render('admin/testdrives/edit', ['testdrive' => array_merge((array)$this->repo->find((int)$data['id']), $data), 'csrf' => Csrf::token(), 'errors' => $errors]);
            return new Response($html, 422);
        }
        
        // Cria o objeto testdrive
        $testdrive = $this->service->make($data);
        if (!$testdrive->id) return new Response('ID inválido', 422);
        
        $this->repo->update($testdrive);
        
        // Redirecionamento alterado
        return new RedirectResponse('/admin/testdrives/show?id=' . $testdrive->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }
        
        $id = (int)$request->request->get('id', 0);

        if ($id === 0) {
            Flash::push("danger", "ID do Empréstimo inválido.");
            return new RedirectResponse('/admin/testdrives');
        }
        
        if ($id > 0) {
            $this->repo->delete($id);
            Flash::push("success", "Empréstimo excluído com sucesso!");
        }
        
        return new RedirectResponse('/admin/testdrives');
    }
}