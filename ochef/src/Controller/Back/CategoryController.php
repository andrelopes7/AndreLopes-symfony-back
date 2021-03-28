<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */

class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category_browse", methods="GET")
     */


    public function browse(CategoryRepository $categoryRepo): Response
    {
        $allCategory= $categoryRepo->findBy([], ['name' => 'ASC']);
        return $this->render('back/category/browse.html.twig', [
            'category_list' => $allCategory,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_category_read", methods="GET", requirements={"id"="\d+"})
     */

    public function read(Category $category): Response
    {
     

        return $this->render('back/category/read.html.twig', [
            'category' => $category,
        ]);
    }
      /**
     * @Route("/new", name="admin_category_add", methods={"GET","POST"})
     */

        public function add(Request $request): Response
        {
            $category = new Category();
            
            $form = $this->createForm(CategoryType::class, $category);
       
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid())
            {
            
                $category = $form->getData();
                $category->setCreatedAt(new \DateTime());
                $category->setUpdatedAt(new \DateTime());
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($category);
                $entityManager->flush();
    

                return $this->redirectToRoute('admin_category_browse');
            }
    
            return $this->render('back/category/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }
     /**
     * @Route("/edit/{id}", name="admin_category_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $category->setUpdatedAt(new \DateTime());
            $em->flush();

            // TODO flash message

            return $this->redirectToRoute('admin_category_browse');
        }

        return $this->render('back/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
     }

    /**
     * @Route("/delete/{id}", name="admin_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
     // vérification du token généré dans twig par la fonction csrf_token
        $tokenFromForm = $request->request->get('_token');

    // ceci est la clef qui nous a permis de généré le token
        $tokenKey = 'delete-category' . $category->getId(); 

        if ($this->isCsrfTokenValid($tokenKey, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_category_browse');
    }
 }