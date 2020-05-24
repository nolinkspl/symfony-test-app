<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function conversions(int $id, string $action = '')
    {
        $result = [$id, $action];

        var_dump($this->container->get('serializer')->serialize($result, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], [])));


        return $this->json($result);
    }
}