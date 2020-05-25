<?php

namespace App\Controller;

use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TokenController extends DefaultController
{

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function token(): JsonResponse
    {
        $expireAt = new \DateTime('+1 hour');
        $tokenUid = hash('sha1', $expireAt);
        $token = new Token($tokenUid, $expireAt);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $this->json($token->info());
    }
}