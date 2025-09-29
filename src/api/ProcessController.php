<?php

namespace App\api;

use App\Infrastructure\Repository\ProcessRepository;
use App\Model\Entity\Process;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ProcessController extends AbstractController {

    #[Route("/process/{processId}", methods: ["PUT"])]
    public function modifyProcess(Request $request, EntityManagerInterface $entityManager, string $processId): Response {
        /** @var ProcessRepository $processRepository  */
        $processRepository = $entityManager->getRepository(Process::class);
        $res = $processRepository->findById(new Uuid($processId));

        $payload = json_decode($request->getContent(), true);
        
        if(isset($payload["name"])){
            $res->setName($payload["name"]); 
        }
        if(isset($payload["description"])){
            $res->setDescription($payload["description"]); 
        }

        if(isset($payload["tests"])){
            $res->setTests($payload["tests"]); 
        }
        
        $entityManager->flush();

        return new JsonResponse($res);
    }

    #[Route("/process/validate/{processId}", methods: ["PUT"])]
    public function validateProcess(Request $request, EntityManagerInterface $entityManager, string $processId): Response {
        /** @var ProcessRepository $processRepository  */
        $processRepository = $entityManager->getRepository(Process::class);
        $res = $processRepository->findById(new Uuid($processId));

        $res->setTestsValidated();

        $entityManager->flush();
        
        return new JsonResponse($res);
    }

    #[Route("/process", methods: ["POST"])]
    public function createProcess(Request $request, EntityManagerInterface $entityManager, string $processId): Response {
        /** @var ProcessRepository $processRepository  */
        $processRepository = $entityManager->getRepository(Process::class);

        
        $payload = json_decode($request->getContent(), true);

        if(!isset($payload["name"]) || !isset($payload["description"]) || !isset($payload["tests"])) {
            return new JsonResponse("Invalid input", 400);
        }

        $res = new Process;

        $res->setName($payload["name"]);
        $res->setDescription($payload["description"]); 
        $res->setTests($payload["tests"]); 

        $entityManager->persist($res);
        
        $entityManager->flush();
        
        return new JsonResponse($res);
    }

    #[Route("/process/{processId}", methods: ["DELETE"])]
    public function deleteProcess(Request $request, EntityManagerInterface $entityManager, string $processId): Response {
        
        $processRepository = $entityManager->getRepository(Process::class);
        $res = $processRepository->findById(new Uuid($processId));

        $entityManager->remove($res);

        return new JsonResponse($res);
    }

    #[Route("/process/{processId}", methods: ["get"])]
    public function getProcess(Request $request, EntityManagerInterface $entityManager, string $processId): Response {
        
        $processRepository = $entityManager->getRepository(Process::class);
        $res = $processRepository->findById(new Uuid($processId));

        return new JsonResponse($res);
    }
}