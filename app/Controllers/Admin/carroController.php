<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\View;
use App\Repositories\montadoraRepository;
use App\Repositories\motoRepository;
use App\Repositories\carroRepository;
use App\Services\carroService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class carroControllera
{
    private View $view;
    private carroRepository $repo;
    private carroService $service;
    private montadoraRepository $montadoraRepo;
    private motoRepository $motoRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new carroRepository();
        $this->service = new carroService();
        $this->montadoraRepo = new montadoraRepository();
        $this->motoRepo = new motoRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $carros = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);
        $montadoras = $this->montadoraRepo->getArray();
        $motos = $this->motoRepo->getArray();
        $html = $this->view->render('admin/carros/index', compact(
            'carros',
            'page',
            'pages',
            'montadoras',
            'motos'
        ));
        return new Response($html);
    }

    public function create(): Response
    {
        $montadoras = $this->montadoraRepo->findAll();
        $motos = $this->motoRepo->findAll();
        $html = $this->view->render('admin/carros/create', ['csrf' => Csrf::token(), 'errors' => [], 'montadoras' => $montadoras, 'motos' => $motos]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $errors = $this->service->validate($request->request->all());
        if ($errors) {
            $montadoras = $this->montadoraRepo->findAll();
            $motos = $this->motoRepo->findAll();
            $html = $this->view->render('admin/carros/create', ['csrf' => Csrf::token(), 'errors' => $errors, 'old' => $request->request->all(), 'montadoras' => $montadoras, 'motos' => $motos]);
            return new Response($html, 422);
        }
        $carro = $this->service->make($request->request->all());
        $id = $this->repo->create($carro);
        return new RedirectResponse('/admin/carros/show?id=' . $id);
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $carro = $this->repo->find($id);
        if (!$carro) return new Response('carro não encontrado', 404);

        $montadoraRepo = new montadoraRepository();
        $montadora = $montadoraRepo->find($carro['montadora_id']);
        $carro['montadora_nome'] = $montadora['nome'] ?? 'Desconhecida';

        $motoRepo = new motoRepository();
        $moto = null;

        if (!empty($carro['moto_id'])) {
            $moto = $motoRepo->find((int)$carro['moto_id']);
        }

        $carro['moto_nome'] = $moto['nome_moto'] ?? 'Desconhecido';
        $html = $this->view->render('admin/carros/show', ['carro' => $carro]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $carro = $this->repo->find($id);
        $montadoras = $this->montadoraRepo->findAll();
        $motos = $this->motoRepo->findAll();
        if (!$carro) return new Response('carro não encontrado', 404);
        $html = $this->view->render('admin/carros/edit', ['carro' => $carro, 'csrf' => Csrf::token(), 'errors' => [], 'montadoras' => $montadoras, 'motos' => $motos]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);
        if ($errors) {
            $montadoras = $this->montadoraRepo->findAll();
            $motos = $this->motoRepo->findAll();
            $html = $this->view->render('admin/carros/edit', ['carro' => array_merge($this->repo->find((int)$data['id']), $data), 'csrf' => Csrf::token(), 'errors' => $errors, 'montadoras' => $montadoras, 'motos' => $motos]);
            return new Response($html, 422);
        }
        $carro = $this->service->make($data);
        if (!$carro->id) return new Response('ID inválido', 422);
        $this->repo->update($carro);
        return new RedirectResponse('/admin/carros/show?id=' . $carro->id);
    }

    public function delete(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $id = (int)$request->request->get('id', 0);
        if ($id > 0) $this->repo->delete($id);
        return new RedirectResponse('/admin/carros');
    }
}
