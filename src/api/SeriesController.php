<?php

namespace App\api;

use App\Infrastructure\Repository\SeriesRepository;
use App\Model\Entity\Series;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

use function PHPUnit\Framework\isNull;

class SeriesController extends AbstractController {

    #[Route('/api/series', methods:['POST'])]
    public function addSeries(Request $request, EntityManagerInterface $entityManager): Response {
        
        $payload = json_decode($request->getContent(), true);
        if (!isNull($payload["name"])) {
            return new JsonResponse("invalid entries", 400);
        }

        $new = new Series();
        $new->setName($payload["name"]);
        try {
        $entityManager->persist($new);
        $entityManager->flush();

        return new JsonResponse(array("result" => "saved series " . $new->getName()));
        } catch (Exception $e) {
            return new JsonResponse(array("message" => $e->getMessage()), 400);
        }
    }

    #[Route('/api/series', methods:["GET"])]
    public function getSeriess(Request $request, EntityManagerInterface $entityManager): Response{
        /** @var SeriesRepository $seriesRepository  */
        $seriesRepository = $entityManager->getRepository(Series::class);
        $res = $seriesRepository->findAll();
        return new JsonResponse($res, 200);
    }

    #[Route('/api/series/{seriesId}', methods:["PUT"])]
    public function modifySeriess(Request $request, EntityManagerInterface $entityManager, string $seriesId): Response{
        /** @var SeriesRepository $seriesRepository  */
        $seriesRepository = $entityManager->getRepository(Series::class);
        $res = $seriesRepository->findByID(new Uuid($seriesId));

        
        $payload = json_decode($request->getContent(), true);

        if(isset($payload["name"])){
            $res->setName($payload["name"]); 
        }

        $entityManager->flush();
        return new JsonResponse($res, 200);
    }
}