<?php

namespace App\Controller\Back;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ingredient")
 */

class IngredientController extends AbstractController
{
    /**
     * @Route("/", name="admin_ingredient_browse", methods="GET")
     */


    public function browse(IngredientRepository $ingredientRepo): Response
    {
        $allIngredient= $ingredientRepo->findBy([], ['name' => 'ASC']);
        return $this->render('back/ingredient/browse.html.twig', [
            'ingredient_list' => $allIngredient,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_ingredient_read", methods="GET", requirements={"id"="\d+"})
     */

    public function read(Ingredient $ingredient): Response
    {
     

        return $this->render('back/ingredient/read.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }
      /**
     * @Route("/new", name="admin_ingredient_add", methods={"GET","POST"})
     */

        public function add(Request $request): Response
        {
            $ingredient = new Ingredient();
            
            $form = $this->createForm(IngredientType::class, $ingredient);
       
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid())
            {
            
                $ingredient = $form->getData();
                $ingredient->setCreatedAt(new \DateTime());
                $ingredient->setUpdatedAt(new \DateTime());
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($ingredient);
                $entityManager->flush();
    

                return $this->redirectToRoute('admin_ingredient_browse');
            }
    
            return $this->render('back/ingredient/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }
     /**
     * @Route("/edit/{id}", name="admin_ingredient_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
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

            // TODO flash message

            return $this->redirectToRoute('admin_ingredient_browse');
        }

        return $this->render('back/ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient,
        ]);
     }

    /**
     * @Route("/delete/{id}", name="admin_ingredient_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ingredient $ingredient): Response
    {
     // vérification du token généré dans twig par la fonction csrf_token
        $tokenFromForm = $request->request->get('_token');

    // ceci est la clef qui nous a permis de généré le token
        $tokenKey = 'delete-ingredient' . $ingredient->getId(); 

        if ($this->isCsrfTokenValid($tokenKey, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_ingredient_browse');
    }
 }
    

