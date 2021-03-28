<?php

namespace App\Controller\Back;


use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="homepage", methods="GET")
     */
    public function homepage(RecipeRepository $RecipeRepository): Response
    {

       
        $allRecipe= $RecipeRepository->findBy([], ['name' => 'ASC']);
        return $this->render('back/recipe/browse.html.twig', [
                'recipe_list' => $allRecipe,
            ]);
    }
}