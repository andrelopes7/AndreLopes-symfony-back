<?php

namespace App\Controller\Back;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @Route("/admin/recipe")
 * @Vich\Uploadable
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="admin_recipe_browse", methods="GET")
     */
    public function browse(RecipeRepository $recipeRepo): Response
    {
        $allRecipe= $recipeRepo->findBy([], ['name' => 'ASC']);
        return $this->render('back/recipe/browse.html.twig', [
            'recipe_list' => $allRecipe,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_recipe_read", methods="GET", requirements={"id"="\d+"})
     */
    public function read(Recipe $recipe): Response
    {
        return $this->render('back/recipe/read.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * @Route("/new", name="admin_recipe_add", methods={"GET","POST"})
     */
        public function add(Request $request , FileUploader $fileUploader): Response
        {
            $recipe = new Recipe();
            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);
            // $pictureFile = $form->get('picture')->getData();

            if ($form->isSubmitted() && $form->isValid()) {
                $recipe->setCreatedAt(new \DateTime());
                $recipe->setUpdatedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recipe);
                $entityManager->flush();

                return $this->redirectToRoute('admin_recipe_browse');
            }

            return $this->render('back/recipe/add.html.twig', [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]);
        }

     /**
     * @Route("/edit/{id}", name="admin_recipe_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Recipe $recipe, Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        /* $pictureFile = $form->get('picture')->getData(); */

        if ($form->isSubmitted() && $form->isValid())
        {
            /* if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $recipe->setPicture($pictureFileName);
            } */

            $em = $this->getDoctrine()->getManager();

            $recipe->setUpdatedAt(new \DateTime());
            $em->flush();

            // TODO flash message

            return $this->redirectToRoute('admin_recipe_browse');
        }

        return $this->render('back/recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
     }

    /**
     * @Route("/delete/{id}", name="admin_recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
         // vérification du token généré dans twig par la fonction csrf_token
          $tokenFromForm = $request->request->get('_token');

         // ceci est la clef qui nous a permis de généré le token
          $tokenKey = 'delete-recipe' . $recipe->getId(); 
 
        if ($this->isCsrfTokenValid($tokenKey, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_recipe_browse');
    }
}
    

