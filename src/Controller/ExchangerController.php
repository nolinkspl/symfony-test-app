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
        $result = [
            'token'    => 'foo',
            'expireAt' => (new \DateTime('+1 hour'))->format('Y-m-d H:i:s'),
        ];

        return $this->json($result);
    }

    public function conversions(int $id)
    {
        return $this->json([$id]);
    }
}