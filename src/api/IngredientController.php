<?php

namespace App\api;

use App\Infrastructure\Repository\IngredientRepository;
use App\Model\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

use function PHPUnit\Framework\isNull;

class IngredientController extends AbstractController {

    #[Route('/api/ingredient', methods:['POST'])]
    public function addIngredient(Request $request, EntityManagerInterface $entityManager): Response {
        
        $payload = json_decode($request->getContent(), true);
        if (!isNull($payload["name"]) || !isNull($payload["description"])) {
            return new JsonResponse("invalid entries", 400);
        }

        $new = new Ingredient();
        $new->setName($payload["name"]);
        $new->setDescription($payload["description"]);
        try {
        $entityManager->persist($new);
        $entityManager->flush();

        return new JsonResponse(array("result" => "saved ingredient " . $new->getName()));
        } catch (Exception $e) {
            return new JsonResponse(array("message" => $e->getMessage()), 400);
        }
    }

    #[Route('/api/ingredient', methods:["GET"])]
    public function getIngredients(Request $request, EntityManagerInterface $entityManager): Response{
        /** @var IngredientRepository $ingredientRepository  */
        $ingredientRepository = $entityManager->getRepository(Ingredient::class);
        $res = $ingredientRepository->findAll();
        return new JsonResponse($res, 200);
    }

    #[Route('/api/ingredient/{ingredientId}', methods:["PUT"])]
    public function modifyIngredients(Request $request, EntityManagerInterface $entityManager, string $ingredientId): Response{
        /** @var IngredientRepository $ingredientRepository  */
        $ingredientRepository = $entityManager->getRepository(Ingredient::class);
        $res = $ingredientRepository->findByID(new Uuid($ingredientId));

        
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