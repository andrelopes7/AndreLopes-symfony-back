<?php

namespace App\Controller\Api\V1;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route("/api/ingredient")
     */
    class ApiIngredientController extends AbstractController
    {
        /**
         * @Route("/", name="api_ingredient_browse", methods={"GET"})
         */
        public function browse(IngredientRepository $ingredientRepository): Response
        {
            return $this->json($ingredientRepository->findAll(), 200, [], []);
        }
    
        /**
         * @Route("/{id}", name="api_ingredient_read", methods={"GET"})
         */
        public function read(Ingredient $ingredient): Response
        {
            $ingredientForApi = [];

            $obj = [];
            $obj['id'] = $ingredient->getId();
            $obj['name'] = $ingredient->getName();
            $obj['created_at'] = $ingredient->getCreatedAt();
            
        
            foreach($ingredient->getRecipes() as $ingredients ){

                    $obj1 = [];
                    $obj1['id'] = $ingredients->getId();
                    $obj1['name'] = $ingredients->getName();
                    $obj['recipes'][] = $obj1;
            }
                
            $ingredientForApi[] = $obj;
            
        

        return $this->json($ingredientForApi, 200, []);

        }


    /**
    * @Route("/edit/{id}", name="api_recipe_edit", methods={"GET","POST"})
    */
    public function edit(Ingredient $ingredient, Request $request): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $ingredient->setUpdatedAt(new \DateTime());
            $em->flush();

            

            return $this->redirectToRoute('api_ingredient_browse');
        }
    }

         /**
         * @Route("/{id}", name="api_ingredient_add", methods={"POST"})
         */
        public function add(Request $request, EntityManagerInterface $em): Response
        {
    
        $infoFromClientAsArray = json_decode($request->getContent(), true);
    
        $ingredient = new Ingredient();
        
        $form = $this->createForm(IngredientType::class, $ingredient, ['csrf_protection' => false]);
        
        $form->submit($infoFromClientAsArray);

        if ($form->isValid())
        {
            

            $em->persist($ingredient);
            $em->flush();

            
            return $this->json($ingredient);
        }
        else 
        {
            return $this->json((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
        }

        }

    /**
    * @Route("/delete/{id}", name="api_ingredient_delete", methods="GET")
    */
    public function delete(Ingredient $ingredient, EntityManagerInterface $em): Response
    {
        $em->remove($ingredient);

        $em->flush();
        // ajouter un flash message

        return $this->redirectToRoute('api_ingredient_browse');
    }

    }
    
