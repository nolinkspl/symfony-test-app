<?php

namespace App\Controller;

use App\Entity\Amount;
use App\Entity\Conversion;
use App\Entity\Currency;
use App\Repository\ConversionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConversionController extends DefaultController
{

    /** @var ConversionRepository */
    private $conversionRepository;

    public function __construct(ConversionRepository $conversionRepository)
    {
        $this->conversionRepository = $conversionRepository;
        var_dump($conversionRepository->findOneBy(['id'=>1])->getExpireAt());
    }

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
        $conversion = $this->getConversionById($id);
        return $this->json(array_merge(['isExecuted' => $conversion->getIsExecuted()], $conversion->info()));
    }

    public function executeConversion(int $id): JsonResponse
    {
        $conversion = $this->getConversionById($id);
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

        if ($this->findConversionById($id) !== null) {
            throw new HttpException(409, 'Operation with this ID already exists');
        }

        $usdCurrency = $this->getDoctrine()
                            ->getRepository(Currency::class)
                            ->findOneBy(['code' => 'USD']);
        $rubCurrency = $this->getDoctrine()
                            ->getRepository(Currency::class)
                            ->findOneBy(['code' => 'RUB']);




        $conversion = new Conversion();
        $conversion->setExpireAt(new \DateTime('+1 minute'));
        $conversion->setUid($id);
        $conversion->setFromAmount((new Amount())->setAmount(125125)->setCurrency($rubCurrency));
        $conversion->setToAmount((new Amount())->setCurrency($usdCurrency));

        $this->storeConversion($conversion);

        return $this->json($conversion->info());
    }

    private function getConversionById(int $id): Conversion
    {
        $result = $this->findConversionById($id);

        if ($result === null) {
            throw $this->createNotFoundException('No conversion found for id ' . $id);
        }

        return $result;
    }

    private function findConversionById(int $id): ?Conversion
    {
        return $this->getDoctrine()
                       ->getRepository(Conversion::class)
                       ->find($id);
    }

    private function storeConversion(Conversion $conversion)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversion);
        $entityManager->flush();
    }
}