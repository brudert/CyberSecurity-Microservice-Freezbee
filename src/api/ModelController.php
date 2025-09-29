<?php

namespace App\api;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Entity\Model;
use App\Model\Entity\Series;

class ModelController extends AbstractController 
{
    #[Route('/model/addModel', name: 'add_model')]
    public function AddModel(Request $request, EntityManagerInterface $entityManager) : Response 
    {
        $payload = json_decode($request->getContent(), True);


        $serie = new Series();
        $serie->setName('serie3');

        $model = new Model();
        $model->setName("product1");
        $model->setDescription("random description here");
        $model->setPUHT(3);
        $model->setSeries($serie);


        // tell doctrine you eventually want to 
        $entityManager->persist($serie);
        $entityManager->persist($model);
        // actually execute the query
        $entityManager->flush();

        // optional 
        return new Response('Saved new product with id '.$model->getId());
    }
}