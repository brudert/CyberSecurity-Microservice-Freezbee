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
use Symfony\Component\Uid\UuidV8;

use function PHPUnit\Framework\isNull;

class CharacteristicController extends AbstractController {

    #[Route('/api/characteristic', methods:['POST'])]
    public function addCharacteristic(Request $request, EntityManagerInterface $entityManager): Response {
        
        $payload = json_decode($request->getContent(), true);
        if (!isNull($payload["name"]) || !isNull($payload["description"])) {
            return new JsonResponse("invalid entries", 400);
        }

        $new = new Characteristic();
        $new->setName("hello");
        $new->setDescription("world");
        try {
        $entityManager->persist($new);
        $entityManager->flush();

        return new JsonResponse(json_encode("{\"result\": \"saved characteristic " . $new->getName() . "\"}"));
        } catch (Exception $e) {
            return new JsonResponse(array("message" => $e->getMessage()), 400);
        }
    }
}