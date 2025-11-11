<?php

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use App\Repositories\ProductRepository;
use App\Repositories\DebtorRepository;
use App\Repositories\OrderItemsRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrdersController
{
    private View $view;
    private OrderRepository $repo;
    private OrderService $service;

    private ProductRepository $productRepo;
    private DebtorRepository $debtorRepo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new OrderRepository();
        $this->service = new OrderService();
        $this->productRepo = new ProductRepository();
        $this->debtorRepo = new DebtorRepository();
    }

    public function index(Request $request): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 5;
        $total = $this->repo->countAll();
        $orders = $this->repo->paginate($page, $perPage);
        $pages = (int)ceil($total / $perPage);
        $html = $this->view->render('admin/orders/index', compact('orders', 'page', 'pages'));
        return new Response($html);
    }

    public function create(): Response
    {
        $products = $this->productRepo->all();
        $debtors = $this->debtorRepo->all();
        $html = $this->view->render('admin/orders/create', [
            'csrf' => Csrf::token(), 
            'errors' => [], 
            'products' => $products,
            'debtors' => $debtors
        ]);
        return new Response($html);
    }

    public function store(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);

        if ($errors) {
            $products = $this->productRepo->all();
            $debtors = $this->debtorRepo->all();
            $html = $this->view->render('admin/orders/create', [
                'csrf' => Csrf::token(), 
                'errors' => $errors, 
                'old' => $data,
                'products' => $products,
                'debtors' => $debtors
            ]);
            return new Response($html, 422);
        }

        $orderId = $this->service->makeAndSave($data); 
        Flash::push('success', "Venda (Pedido #{$orderId}) registrada com sucesso!");
        return new RedirectResponse('/admin/orders');
    }

    public function show(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $order = $this->repo->findWithDetails($id);
        if (!$order) return new Response('Pedido não encontrado', 404);
        $html = $this->view->render('admin/orders/show', ['order' => $order]);
        return new Response($html);
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->query->get('id', 0);
        $order = $this->repo->find($id);
        if (!$order) return new Response('Pedido não encontrado', 404);
        
        $products = $this->productRepo->all();
        $debtors = $this->debtorRepo->all();

        $html = $this->view->render('admin/orders/edit', [
            'order' => $order, 
            'csrf' => Csrf::token(), 
            'errors' => [],
            'products' => $products,
            'debtors' => $debtors
        ]);
        return new Response($html);
    }

    public function update(Request $request): Response
    {
        if (!Csrf::validate($request->request->get('_csrf'))) return new Response('Token CSRF inválido', 419);
        $data = $request->request->all();
        $errors = $this->service->validate($data);

        if ($errors) {
            $products = $this->productRepo->all();
            $debtors = $this->debtorRepo->all();
            $html = $this->view->render('admin/orders/edit', [
                'order' => array_merge($this->repo->find((int)$data['id']), $data), 
                'csrf' => Csrf::token(), 
                'errors' => $errors,
                'products' => $products,
                'debtors' => $debtors
            ]);
            return new Response($html, 422);
        }
        
        $order = $this->service->make($data);
        if (!$order->id) return new Response('ID inválido', 422);
        
        // A lógica de update de Pedidos é complexa e deve ser delegada ao Service
        $this->service->updateOrderTransaction($order); 

        Flash::push('success', "Pedido atualizado com sucesso!");
        return new RedirectResponse('/admin/orders');
    }

    public function delete(Request $request): Response
    {
        $id = (int)$request->request->get('id', 0);
        
        $result = $this->service->deleteOrderTransaction($id); 

        if ($result === false) {
             Flash::push("danger", "Pedido não pode ser excluído, ocorreu um erro na reversão das transações!");
        } else {
             Flash::push('success', "Pedido excluído e transações revertidas com sucesso!");
        }

        return new RedirectResponse('/admin/orders');
    }
}