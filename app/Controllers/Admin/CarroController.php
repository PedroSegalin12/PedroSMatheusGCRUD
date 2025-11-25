<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\View;
use App\Repositories\MontadoraRepository;
use App\Repositories\CarroRepository;
use App\Services\CarroService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarroController
{
    private View $view;
    private CarroRepository $repo;
    private CarroService $service;
    private MontadoraRepository $MontadoraRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new CarroRepository();
        $this->service = new CarroService();
        $this->MontadoraRepo = new MontadoraRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $carros = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);

        $html = $this->view->render('admin/carros/index', compact('carros', 'page', 'pages'));
        return new Response($html);
    }

    public function create(): Response
    {
        $Montadoras = $this->MontadoraRepo->findAll();

        $html = $this->view->render('admin/carros/create', [
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => [],
            'Montadoras' => $Montadoras
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $data = $request->request->all();
        $errors = $this->service->validate($data);

        if ($errors) {
            $Montadoras = $this->MontadoraRepo->findAll();
            $html = $this->view->render('admin/carros/create', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => $data,
                'Montadoras' => $Montadoras
            ]);
            return new Response($html, 422);
        }

        $carro = $this->service->make($data);
        $id = $this->repo->create($carro);

        return new RedirectResponse('/admin/carros/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $carro = $this->repo->find($id);
        if (!$carro) return new Response('Carro não encontrado', 404);

        $Montadora = $this->MontadoraRepo->find($carro['Montadora_id']);
        $carro['Montadora_nome'] = $Montadora['nome'] ?? 'Desconhecida';

        $html = $this->view->render('admin/carros/show', ['carro' => $carro]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $carro = $this->repo->find($id);
        if (!$carro) return new Response('Carro não encontrado', 404);

        $Montadoras = $this->MontadoraRepo->findAll();

        $html = $this->view->render('admin/carros/edit', [
            'csrf' => Csrf::token(),
            'errors' => [],
            'carro' => $carro,
            'old' => $carro,
            'Montadoras' => $Montadoras
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $data = $request->request->all();
        $errors = $this->service->validate($data);

        if ($errors) {
            $Montadoras = $this->MontadoraRepo->findAll();
            $html = $this->view->render('admin/carros/edit', [
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'carro' => array_merge($this->repo->find((int)$data['id']), $data),
                'old' => $data,
                'Montadoras' => $Montadoras
            ]);
            return new Response($html, 422);
        }

        $carro = $this->service->make($data);
        $this->repo->update($carro);

        return new RedirectResponse('/admin/carros/show?id=' . $carro->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) {
            return new Response('Token CSRF inválido', 419);
        }

        $id = (int)$request->request->get('id', 0);
        if ($id > 0) $this->repo->delete($id);

        return new RedirectResponse('/admin/carros');
    }
}
