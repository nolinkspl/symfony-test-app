<?php

namespace App\Controller;

use App\Entity\Conversion;
use App\Service\ConversionManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConversionController extends DefaultController
{

    /** @var ConversionManager */
    private $conversionManager;

    public function __construct(ConversionManager $conversionManager)
    {
        $this->conversionManager = $conversionManager;
    }

    public function conversions(): JsonResponse
    {
        return $this->json(['conversions' => $this->conversionManager->findAllConversionIDs()]);
    }

    public function conversion(int $id): JsonResponse
    {
        $conversion = $this->getConversionById($id);
        return $this->json(array_merge(['isExecuted' => $conversion->getIsExecuted()], $conversion->info()));
    }

    public function executeConversion(int $id): JsonResponse
    {
        $conversion = $this->getConversionById($id);
        $this->conversionManager->executeConversion($conversion);

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

        if ($this->conversionManager->findConversionById($id) !== null) {
            throw new HttpException(409, 'Operation with this ID already exists');
        }

        $conversion = $this->conversionManager->prepareConversion($data);

        return $this->json($conversion->info());
    }

    private function getConversionById(int $id): Conversion
    {
        $result = $this->conversionManager->findConversionById($id);

        if ($result === null) {
            throw $this->createNotFoundException('No conversion found for id ' . $id);
        }

        return $result;
    }
}