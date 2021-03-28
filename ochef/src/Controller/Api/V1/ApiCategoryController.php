<?php

namespace App\Controller\Api\V1;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route("/api/category")
     */
    class ApiCategoryController extends AbstractController
    {
        /**
         * @Route("/", name="api_category_browse", methods={"GET"})
         */
        public function browse(CategoryRepository $categoryRepository): Response
        {
            $categoryForApi = [];

            foreach($categoryRepository->findAll() as $category) {
            
                $obj = [];
                $obj['id'] = $category->getId();
                $obj['name'] = $category->getName();
               
                $categoryForApi[] = $obj;
            }
        
            return $this->json($categoryForApi, 200, []);
    
        }
    
        /**
         * @Route("/{id}", name="api_category_read", methods={"GET"})
         */
        public function read(Category $category): Response
        {
            $categoryForApi = [];

            $obj = [];
            $obj['id'] = $category->getId();
            $obj['name'] = $category->getName();
            $obj['created_at'] = $category->getCreatedAt();
            
        
            foreach($category->getRecipes() as $categories ){

                    $obj1 = [];
                    $obj1['id'] = $categories->getId();
                    $obj1['name'] = $categories->getName();
                    $obj['recipes'][] = $obj1;
            }
                
            $categoryForApi[] = $obj;
            
        

        return $this->json($categoryForApi, 200, []);

    
        }
    }
    
