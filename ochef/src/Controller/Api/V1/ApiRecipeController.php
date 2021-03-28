<?php

namespace App\Controller\Api\V1;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/api/recipe")
     */
    class ApiRecipeController extends AbstractController
    {
        /**
         * @Route("/", name="api_recipe_browse", methods={"GET"})
         */
        public function browse(RecipeRepository $recipeRepository): Response
        {
            $recipesForApi = [];

            foreach($recipeRepository->findAll() as $recipe)
            {
                $obj = [];
                $obj['id'] = $recipe->getId();
                $obj['picture'] = $recipe->getPicture();
                $obj['name'] = $recipe->getName();
                $obj['category'] = $recipe->getCategory()->getName();
                $obj['country'] = $recipe->getCountry()->getName();
                $obj['author'] = $recipe->getUser()->getName();
                $obj['time'] = $recipe->getTime();
                $obj['portions'] = $recipe->getPortions();
                $obj['cookingTime'] = $recipe->getCookingTime();
                $obj['préparation'] = $recipe->getPreparation();
                $obj['ingredientList'] = $recipe->getIngredientList();
                $obj['slug'] = $recipe->getSlug();
                $obj['steps'] = $recipe->getsteps();      
                $obj['created_at'] = $recipe->getCreatedAt();

                 foreach($recipe->getIngredient()->getValues() as $ingredient)
                {
                    $obj1 = [];
                    $obj1['name'] = $ingredient->getName();
                    $obj['ingredient'][] = $obj1;
                }

                foreach($recipe->getComment()->getValues() as $recipe)
                        {
                        $obj1 = [];
                        $obj1['title'] = $recipe->getTitle();
                        $obj1['id'] = $recipe->getId();
                        $obj1['author'] = $recipe->getAuthor();
                        $obj1['description'] = $recipe->getDescription();
                        $obj1['created_at'] = $recipe->getCreatedAt();
                        $obj['comment'][] = $obj1;
                        }  

                $recipesForApi[] = $obj;
            }
            // dd($recipesForApi);
             return $this->json($recipesForApi, 200, []);
        }
    
    
        /**
         * @Route("/{slug}", name="api_recipe_read", methods={"GET"}, requirements={"slug"="\S+"}))
         */
        public function read(Recipe $recipe): Response
        {       
            $recipesForApi = [];

            $obj = [];
            $obj['id'] = $recipe->getId();
            $obj['picture'] = $recipe->getPicture();
            $obj['author'] = $recipe->getUser()->getName();
            $obj['category'] = $recipe->getCategory()->getName();
            $obj['country'] = $recipe->getCountry()->getName();
            $obj['name'] = $recipe->getName();
            $obj['time'] = $recipe->getTime();
            $obj['portions'] = $recipe->getPortions();
            $obj['cookingTime'] = $recipe->getCookingTime();
            $obj['préparation'] = $recipe->getPreparation();
            $obj['ingredientList'] = $recipe->getIngredientList();

            
             foreach($recipe->getIngredient()->getValues() as $ingredient)
            {
                $obj1 = [];
                $obj1['name'] = $ingredient->getName();
                $obj['ingredient'][] = $obj1;
            }
            $obj['slug'] = $recipe->getSlug();
            $obj['steps'] = $recipe->getsteps();          
            $obj['created_at'] = $recipe->getCreatedAt();


            foreach($recipe->getComment()->getValues() as $recipe)
                        {
                        $obj1 = [];
                        $obj1['title'] = $recipe->getTitle();
                        $obj1['id'] = $recipe->getId();
                        $obj1['author'] = $recipe->getAuthor();
                        $obj1['description'] = $recipe->getDescription();
                        $obj1['created_at'] = $recipe->getCreatedAt();

                        $obj['comment'][] = $obj1;
                        }  
        
            $recipesForApi[] = $obj;
        
        // dd($recipesForApi);
         return $this->json($recipesForApi, 200, []);
    }
    

    /**
    * @Route("/edit/{id}", name="api_recipe_edit", methods={"GET","POST"})
    */
    public function edit(Recipe $recipe, Request $request): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $recipe->setUpdatedAt(new \DateTime());
            $em->flush();

            

            return $this->redirectToRoute('api_recipe_browse');
        }
    }

         /**
         * @Route("/", name="api_recipe_add", methods={"POST"})
         */
        public function add(Request $request, EntityManagerInterface $em): Response
        {
    
        $infoFromClientAsArray = json_decode($request->getContent(), true);
    
        $recipe = new Recipe();
        
        $form = $this->createForm(RecipeType::class, $recipe, ['csrf_protection' => false]);
        
        $form->submit($infoFromClientAsArray);

        if ($form->isValid())
        {
            $em->persist($recipe);
            $em->flush();


            return $this->json($recipe);
        }
        else 
        {
            return $this->json((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
        }

        }

        /**
    * @Route("/delete/{id}", name="api_recipe_delete", methods="GET")
    */
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);

        $em->flush();
        // ajouter un flash message

        return $this->redirectToRoute('api_recipe_browse');
    }

    }