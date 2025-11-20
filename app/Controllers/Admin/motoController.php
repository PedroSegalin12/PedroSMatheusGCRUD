<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\motoRepository;
use App\Repositories\montadoraRepository;
use App\Services\motoService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class motoController
{
    private View $view;
    private motoRepository $repo;
    private motoService $service;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new motoRepository();
        $this->service = new motoService();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $motos = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);

        // Puxar todas as montadoras e indexar por id
        $montadoraRepo = new montadoraRepository();
        $montadorasList = $montadoraRepo->findAll();
        $montadoras = [];
        foreach ($montadorasList as $montadora) {
            $montadoras[$montadora['id']] = $montadora;
        }

        $html = $this->view->render('admin/motos/index', compact('motos', 'page', 'pages', 'montadoras'));
        return new Response($html);
    }

    public function create(): Response
    {
        $montadoraRepo = new montadoraRepository();
        $montadoras = $montadoraRepo->findAll();

        $html = $this->view->render('admin/motos/create', [
            'csrf' => Csrf::token(),
            'errors' => [],
            'montadoras' => $montadoras
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $montadoraRepo = new montadoraRepository();
            $montadoras = $montadoraRepo->findAll();

            $html = $this->view->render('admin/motos/create', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => $request->request->all(),
                'montadoras' => $montadoras
            ]);
            return new Response($html, 422);
        }
        $moto = $this->service->make($request->request->all());
        $id = $this->repo->create($moto);
        return new RedirectResponse('/admin/motos/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $moto = $this->repo->find($id);
        if (!$moto) return new Response('Moto não encontrada', 404);
        $html = $this->view->render('admin/motos/show', ['moto' => $moto]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $moto = $this->repo->find($id);
        if (!$moto) return new Response('Moto não encontrada', 404);

        $montadoraRepo = new montadoraRepository();
        $montadoras = $montadoraRepo->findAll();

        $html = $this->view->render('admin/motos/edit', [
            'moto' => $moto,
            'csrf' => Csrf::token(),
            'errors' => [],
            'montadoras' => $montadoras
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $montadoraRepo = new montadoraRepository();
            $montadoras = $montadoraRepo->findAll();

            $html = $this->view->render('admin/motos/edit', [
                'moto' => array_merge($this->repo->find((int)$data['id']), $data),
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'montadoras' => $montadoras
            ]);
            return new Response($html, 422);
        }
        $moto = $this->service->make($data);
        $this->repo->update($moto);
        return new RedirectResponse('/admin/motos/show?id=' . $moto->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $id = (int)$request->request->get('id', 0);
        if ($id > 0) {
            $this->repo->delete($id);
            Flash::push("success", "Moto excluída com sucesso!");
        }
        return new RedirectResponse('/admin/motos');
    }
}
