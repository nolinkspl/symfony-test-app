<?php

namespace App\Controller;

use App\Entity\Conversion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ConversionController extends DefaultController
{

    public function conversions(): JsonResponse
    {
        /** @var Conversion[] $conversions */
        $conversions = $this->getDoctrine()
                       ->getRepository(Conversion::class)
                       ->findAll();

        $result = [];
        foreach ($conversions as $conversion) {
            $result[] = $conversion->getUid();
        }

        return $this->json(['conversions' => $result]);
    }

    public function conversion(int $id): JsonResponse
    {
        return $this->json($this->findConversionById($id)->info());
    }

    public function executeConversion(int $id): JsonResponse
    {
        $conversion = $this->findConversionById($id);
        $conversion->execute();
        $this->storeConversion($conversion);

        return $this->json('Transaction executed successfully');
    }

    public function prepareConversion(int $id, Request $request): JsonResponse
    {
        $data = [];
        if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : []);
        }

        if (empty($data)) {
            throw new BadRequestHttpException();
        }

        return $this->json(['foo' => 'bar']);
    }

    private function findConversionById(int $id): Conversion
    {
        /** @var Conversion $result */
        $result = $this->getDoctrine()
                       ->getRepository(Conversion::class)
                       ->find($id);

        if ($result === null) {
            throw $this->createNotFoundException('No conversion found for id ' . $id);
        }

        return $result;
    }

    private function storeConversion(Conversion $conversion)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversion);
        $entityManager->flush();
    }
}