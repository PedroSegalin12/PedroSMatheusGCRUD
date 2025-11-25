<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\MotoRepository;
use App\Repositories\MontadoraRepository;
use App\Services\MotoService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MotoController
{
    private View $view;
    private MotoRepository $repo;
    private MotoService $service;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new MotoRepository();
        $this->service = new MotoService();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $motos = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);

        // Puxar todas as Montadoras e indexar por id
        $MontadoraRepo = new MontadoraRepository();
        $MontadorasList = $MontadoraRepo->findAll();
        $Montadoras = [];
        foreach ($MontadorasList as $Montadora) {
            $Montadoras[$Montadora['id']] = $Montadora;
        }

        $html = $this->view->render('admin/motos/index', compact('motos', 'page', 'pages', 'Montadoras'));
        return new Response($html);
    }

    public function create(): Response
    {
        $MontadoraRepo = new MontadoraRepository();
        $Montadoras = $MontadoraRepo->findAll();

        $html = $this->view->render('admin/motos/create', [
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => [],
            'Montadoras' => $Montadoras
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $MontadoraRepo = new MontadoraRepository();
            $Montadoras = $MontadoraRepo->findAll();

            $html = $this->view->render('admin/motos/create', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => $request->request->all(),
                'Montadoras' => $Montadoras
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
        
        $MontadoraRepo = new MontadoraRepository();
        $Montadora = $MontadoraRepo->find($moto['Montadora_id']);
        $moto['Montadora_nome'] = $Montadora['nome'] ?? 'Desconhecida';
        
        $html = $this->view->render('admin/motos/show', ['moto' => $moto]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $moto = $this->repo->find($id);
        if (!$moto) return new Response('Moto não encontrada', 404);

        $MontadoraRepo = new MontadoraRepository();
        $Montadoras = $MontadoraRepo->findAll();

        $html = $this->view->render('admin/motos/edit', [
            'moto' => $moto,
            'csrf' => Csrf::token(),
            'errors' => [],
            'Montadoras' => $Montadoras
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $MontadoraRepo = new MontadoraRepository();
            $Montadoras = $MontadoraRepo->findAll();

            $html = $this->view->render('admin/motos/edit', [
                'moto' => array_merge($this->repo->find((int)$data['id']), $data),
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'Montadoras' => $Montadoras
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
