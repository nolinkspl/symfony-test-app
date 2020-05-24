<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExchangerController extends AbstractController
{

    public function index()
    {
        var_dump('test1');

        return $this->render('index');
    }

    public function token()
    {
        var_dump('test2');
    }
}