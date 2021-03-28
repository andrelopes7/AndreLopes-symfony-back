<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/api/user")
     */
    class ApiUserController extends AbstractController
    {
        /**
         * @Route("/", name="api_user_browse", methods={"GET"})
         */
        public function browse(UserRepository $userRepository): Response
        {
            $usersForApi = [];

            foreach($userRepository->findAll() as $user)
            {
                $obj = [];
                $obj['id'] = $user->getId();
                $obj['name'] = $user->getName();
                $obj['email'] = $user->getEmail();
                $obj['password'] = $user->getPassword();
                $obj['roles'] = $user->getRoles();
                $obj['picture'] = $user->getPicture();
                $obj['friend'] = $user->getFriend();
                $obj['diet'] = $user->getDiet();
                $obj['last_login'] = $user->getLastlogin();    
                $obj['created_at'] = $user->getCreatedAt();
                $obj['updated_at'] = $user->getUpdatedAt();

                foreach($user->getRecipes() as $recipes ){

                    $obj1 = [];
                    $obj1['name'] = $recipes->getName();
                    $obj['recipes'][] = $obj1;
                 }

                 foreach($user->getIngredient()->getValues() as $ingredient)
                {
                    $obj1 = [];
                    $obj1['name'] = $ingredient->getName();
                    $obj['ingredient'][] = $obj1;
                }

                  foreach($user->getComment()->getValues() as $user)
                        {
                        $obj1 = [];
                        $obj1['title'] = $user->getTitle();
                        
                        $obj['comment'][] = $obj1;
                        }  

                $usersForApi[] = $obj;
            }
            // dd($usersForApi);
             return $this->json($usersForApi, 200, []);
        }
    
    
        /**
         * @Route("/{slug}", name="api_user_read", methods={"GET"}, requirements={"slug"="\S+"}))
         */
        public function read(User $user): Response
        {       
            $usersForApi = [];

            $obj = [];
            $obj['id'] = $user->getId();
            $obj['email'] = $user->getEmail();
            $obj['roles'] = $user->getRoles();
            $obj['password'] = $user->getPassword();
            $obj['name'] = $user->getName();
            $obj['picture'] = $user->getPicture();
            $obj['friend'] = $user->getFriend();
            $obj['diet'] = $user->getDiet();
            $obj['last_login'] = $user->getLastLogin();
            $obj1['recipes'] = $user;          
            $obj['created_at'] = $user->getCreatedAt();

            foreach($user->getRecipes() as $recipes ){

                $obj1 = [];
                $obj1['name'] = $recipes->getName();
                $obj['recipes'][] = $obj1;
             }

            foreach($user->getIngredient()->getValues() as $ingredient)
                {
                    $obj1 = [];
                    $obj1['name'] = $ingredient->getName();
                    $obj['ingredient'][] = $obj1;
                }

                foreach($user->getComment()->getValues() as $user)
                 {
                $obj1 = [];
                $obj1['title'] = $user->getTitle();
                $obj['comment'][] = $obj1;
                }  
        
            $usersForApi[] = $obj;
        
        // dd($usersForApi);
         return $this->json($usersForApi, 200, []);
    }
    

    /**
    * @Route("/edit/{id}", name="api_user_edit", methods={"GET","POST"})
    */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $user->setUpdatedAt(new \DateTime());
            $em->flush();

            

            return $this->redirectToRoute('api_user_browse');
        }
    }

         /**
         * @Route("/", name="api_user_add", methods={"POST"})
         */
        public function add(Request $request, EntityManagerInterface $em): Response
        {
    
        $infoFromClientAsArray = json_decode($request->getContent(), true);
    
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);
        
        $form->submit($infoFromClientAsArray);

        if ($form->isValid())
        {
            $em->persist($user);
            $em->flush();


            return $this->json($user);
        }
        else 
        {
            return $this->json((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
        }

        }

        /**
    * @Route("/delete/{id}", name="api_user_delete", methods="GET")
    */
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);

        $em->flush();
        // ajouter un flash message

        return $this->redirectToRoute('api_user_browse');
    }

    }