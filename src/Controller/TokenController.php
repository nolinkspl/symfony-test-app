<?php

namespace App\Controller;

use App\Entity\Token;
use Symfony\Component\HttpFoundation\JsonResponse;

class TokenController extends DefaultController
{

    public function token(): JsonResponse
    {
        $expireAt = new \DateTime('+1 hour');
        $tokenUid = hash('sha1', $expireAt);
        $token = new Token($tokenUid, $expireAt);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($token);
        $entityManager->flush();

        return $this->json($token->info());
    }
}