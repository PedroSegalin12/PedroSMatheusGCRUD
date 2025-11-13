<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Repositories\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrdersController
{
    private View $view;
    private OrderRepository $repo;

    public function __construct()
    {
        $this->view = new View();
        $this->repo = new OrderRepository();
    }

    public function index(Request $request): Response
    {
        $orders = $this->repo->findAll();
        $html = $this->view->render('admin/orders/index', compact('orders'));
        return new Response($html);
    }

    // As outras funções podem ser criadas depois (create, store, etc)
}
