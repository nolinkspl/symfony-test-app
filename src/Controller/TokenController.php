<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class TokenController extends DefaultController
{

    public function token():JsonResponse
    {
        $result = [
            'token'    => 'foo',
            'expireAt' => (new \DateTime('+1 hour'))->format('Y-m-d H:i:s'),
        ];

        return $this->json($result);
    }
}