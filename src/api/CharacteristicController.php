<?php

namespace App\api;

use App\Infrastructure\Repository\CharacteristicRepository;
use App\Model\Entity\Characteristic;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

use function PHPUnit\Framework\isNull;

class CharacteristicController extends AbstractController {

    #[Route('/api/characteristic', methods:['POST'])]
    public function addCharacteristic(Request $request, EntityManagerInterface $entityManager): Response {
        
        $payload = json_decode($request->getContent(), true);
        if (!isNull($payload["name"]) || !isNull($payload["description"])) {
            return new JsonResponse("invalid entries", 400);
        }

        $new = new Characteristic();
        $new->setName($payload["name"]);
        $new->setDescription($payload["description"]);
        try {
        $entityManager->persist($new);
        $entityManager->flush();

        return new JsonResponse(array("result" => "saved characteristic " . $new->getName()));
        } catch (Exception $e) {
            return new JsonResponse(array("message" => $e->getMessage()), 400);
        }
    }

    #[Route('/api/characteristic', methods:["GET"])]
    public function getCharacteristics(Request $request, EntityManagerInterface $entityManager): Response{
        /** @var CharacteristicRepository $characteristicRepository  */
        $characteristicRepository = $entityManager->getRepository(Characteristic::class);
        $res = $characteristicRepository->findAll();
        return new JsonResponse($res, 200);
    }

    #[Route('/api/characteristic/{characteristicId}', methods:["PUT"])]
    public function modifyCharacteristics(Request $request, EntityManagerInterface $entityManager, string $characteristicId): Response{
        /** @var CharacteristicRepository $characteristicRepository  */
        $characteristicRepository = $entityManager->getRepository(Characteristic::class);
        $res = $characteristicRepository->findByID(new Uuid($characteristicId));

        
        $payload = json_decode($request->getContent(), true);

        if(isset($payload["name"])){
            $res->setName($payload["name"]); 
        }
        if(isset($payload["description"])){
            $res->setDescription($payload["description"]); 
        }

        $entityManager->flush();
        return new JsonResponse($res, 200);
    }
}