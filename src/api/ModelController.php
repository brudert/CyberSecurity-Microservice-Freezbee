<?php

namespace App\api;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Entity\Model;
use App\Model\Entity\Series;
use App\Model\Entity\Dosage;
use App\Model\Entity\Ingredient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModelController extends AbstractController 
{
    #[Route('/api/model/', name: 'add_model', methods:['POST'])]
    public function AddModel(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $payload = json_decode($request->getContent(), True);


        $serie = $entityManager->getRepository(Series::class)->find($payload['series_id']);

        $model = new Model();
        $model->setName($payload['name']);
        $model->setDescription($payload['description']);
        $model->setPUHT($payload['pUHT']);
        $model->setSeries($serie);
        $entityManager->persist($model);
        $ingredients = $payload['ingredients']; // array of jsons

        foreach($ingredients as $ingredient)
        {
            $dosage = new Dosage();
            $dosage->setModel($model);
            $ing = $entityManager->getRepository(Ingredient::class)->find($ingredient['ingredient']);
            $dosage->setIngredient($ing);
            $dosage->setGrams($ingredient['grams']);
            $entityManager->persist($dosage);

        }

        // actually execute the query
        $entityManager->flush();

        // optional 
        return new Response('Saved new product with id '.$model->getId());
    }
    #[Route('/api/model/{model_id}', name: 'get_model', methods:['GET'])]
    public function getModel($model_id, EntityManagerInterface $entityManager) : JsonResponse
    {
        $model = $entityManager->getRepository(Model::class)->find($model_id);
        return new JsonResponse($model);
    }
    #[Route('/api/model/{model_id}', name: 'update_model', methods:['PUT'])]
    public function updateModel(Request $request, EntityManagerInterface $entityManager, $model_id) : Response
    {
        $model = $entityManager->getRepository(Model::class)->find($model_id);
        if (!$model) {
            throw $this->createNotFoundException(
                'No product found for id '.$model_id
            );
        }
        $payload = json_decode($request->getContent(), True);
        if (isset($payload['name']))
        {
            $model->setName($payload['name']);
            $entityManager->persist($model);
        }
        elseif (isset($payload['description']))
        {
            $model->setDescription($payload['description']);
            $entityManager->persist($model);
        }
        elseif (isset($payload['pUHT']))
        {
            $model->setPUHT($payload['pUHT']);
            $entityManager->persist($model);
        }
        elseif (isset($payload['serie_id']))
        {
            $serie = $entityManager->getRepository(Series::class)->find($payload['series_id']);
            $model->setSeries($serie);
            $entityManager->persist($model);

        }
        elseif (isset($payload['ingredients']))
        {
            $ingredients = $payload['ingredients'];

            foreach($ingredients as $ingredient)
        {
            $dosage = new Dosage();
            $dosage->setModel($model);
           
            $dosage->setIngredient($ingredient['ingredient_id']);
            $dosage->setGrams($ingredient['dosage']);
            $entityManager->persist($dosage);

        }

        }
        $entityManager->flush();
        
        $model = $entityManager->getRepository(Model::class)->find($model_id);
        return new Response($model);


    }
    #[Route('/api/model/{model_id}', name: 'delete_model', methods: ['DELETE'])]
    public function deleteModel($model_id, EntityManagerInterface $entityManager) : Response
    {
        $model = $entityManager->getRepository(Model::class)->find($model_id);
        $entityManager->remove($model);
        $entityManager->flush();
        return new Response('Model deleted successfylly !! ');
    }

    #[Route('/api/models', name: 'get_models', methods: ['GET'])]
    public function getModels(EntityManagerInterface $entityManager) : JsonResponse 
    {
        $model = $entityManager->getRepository(Model::class)->findAll();

        return new JsonResponse($model);
    }




}