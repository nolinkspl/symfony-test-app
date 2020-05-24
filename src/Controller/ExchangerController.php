<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExchangerController extends AbstractController
{

    public function index()
    {
        var_dump('test1');

        return $this->render('base.html.twig');
    }

    public function token()
    {
        var_dump('test2');
        $result = [1,2,3];

        return json_encode($result);
    }
}