<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\MontadoraRepository;
use App\Repositories\CarroRepository;
use App\Services\MontadoraService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MontadoraController
{
    private View $view;
    private MontadoraRepository $repo;
    private MontadoraService $service;

    private CarroRepository $productRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new MontadoraRepository();
        $this->service = new MontadoraService();
        $this->productRepo = new CarroRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $Montadoras = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);
        $html = $this->view->render('admin/montadoras/index', compact('montadoras', 'page', 'pages'));
        return new Response($html);
    }

    public function create(): Response
    {
        $html = $this->view->render('admin/montadoras/create', ['csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $html = $this->view->render('admin/montadoras/create', ['csrf' => Csrf::token(), 'errors' => $errors, 'old' => $request->request->all()]);
            return new Response($html, 422);
        }
        $Montadora = $this->service->make($request->request->all());
        $id = $this->repo->create($Montadora);
        return new RedirectResponse('/admin/montadoras/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $Montadora = $this->repo->find($id);
        if (!$Montadora) return new Response('Montadora não encontrada', 404);
        $html = $this->view->render('admin/montadoras/show', ['Montadora' => $Montadora]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $Montadora = $this->repo->find($id);
        if (!$Montadora) return new Response('Montadora não encontrada', 404);
        $html = $this->view->render('admin/montadoras/edit', ['Montadora' => $Montadora, 'csrf' => Csrf::token(), 'errors' => []]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $html = $this->view->render('admin/montadoras/edit', ['Montadora' => array_merge($this->repo->find((int)$data['id']), $data), 'csrf' => Csrf::token(), 'errors' => $errors]);
            return new Response($html, 422);
        }
        $Montadora = $this->service->make($data);
        if (!$Montadora->id) return new Response('ID inválido', 422);
        $this->repo->update($Montadora);
        return new RedirectResponse('/admin/montadoras/show?id=' . $Montadora->id);
    }

    public function delete(Request $request): Response
    {
        // Pegar produto com categoria
        $categories = $this->productRepo->findByMontadoraId((int)$request->request->get('id', 0));
        if (count($categories) > 0) {
            Flash::push("danger", "Categoria não pode ser excluída");
            return new RedirectResponse('/admin/categories');
        }

        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $id = (int)$request->request->get('id', 0);
        if ($id > 0) $this->repo->delete($id);
        return new RedirectResponse('/admin/montadoras');
    }
}
