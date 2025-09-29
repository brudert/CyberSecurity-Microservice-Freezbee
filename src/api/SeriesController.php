<?php

namespace App\api;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Entity\Series;

class SeriesController extends AbstractController 
{
    #[Route('/series/addSerie', name: 'add_serie')]
    public function AddSerie(Request $request, EntityManagerInterface $entityManager) : Response 
    {
        $payload = json_decode($request->getContent(), True);

        $serie = new Series();
        $serie->setName($payload['serie_name']);
        
        
        

        // tell doctrine you eventually want to 
        $entityManager->persist($serie);
        // actually execute the query
        $entityManager->flush();

        // optional 
        return new Response('Saved new serie with id '.$serie->getId());
    }
}