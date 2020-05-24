<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExchangerController extends AbstractController
{

    public function index()
    {
        return $this->render('base.html.twig');
    }

    public function token()
    {
        $result = [1,2,3];

        return $this->json($result);
    }

    public function conversions(int $id, string $action)
    {
        return $this->json([$id, $action]);
    }
}