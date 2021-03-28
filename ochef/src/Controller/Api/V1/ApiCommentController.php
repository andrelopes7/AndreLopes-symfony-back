<?php

namespace App\Controller\Api\V1;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/api/comment")
*/
class ApiCommentController extends AbstractController
{
/**
* @Route("/", name="api_comment_browse", methods={"GET"})
*/
public function browse(CommentRepository $commentRepository): Response
{
        $commentForApi = [];

        foreach($commentRepository->findAll() as $comment){
            
            $obj = [];

        $obj['id'] = $comment->getId();
        $obj['title'] = $comment->getTitle();
        $obj['author'] = $comment->getAuthor();
        $obj['description'] = $comment->getDescription();
        $obj['user'] = $comment->getUser()->getName();
        $obj['created_at'] = $comment->getCreatedAt();
        $obj['recipe'] = $comment->getRecipe()->getName();

         /*  foreach($comment->getRecipe() as $recipe){

            $obj1 = [];
            $obj1['title'] = $recipe->getName();
            $obj['recipe'][] = $obj1;
         }  */ 

        $commentForApi[] = $obj;
        
        }
        
// dd($commentForApi);
return $this->json($commentForApi, 200, []);
}


/**
* @Route("/{slug}", name="api_comment_read", methods={"GET"}, requirements={"slug"="\S+"}))
*/
public function read(Comment $comment): Response
{ 
$commentForApi = [];

$obj = [];
$obj['id'] = $comment->getId();
$obj['title'] = $comment->getTitle();
$obj['author'] = $comment->getAuthor();
$obj['description'] = $comment->getDescription();
$obj['user'] = $comment->getUser();
foreach ($comment->getRecipe() as $recipe) {
$obj1 = [];
$obj1['name'] = $recipe->getName();
$obj['recipe'][] = $obj1;
} 
$obj['created_at'] = $comment->getCreatedAt();
$commentForApi[] = $obj;
// dd($recipesForApi);
return $this->json($commentForApi, 200, []);
}

/**
* @Route("/edit/{id}", name="api_comment_edit", methods={"GET","POST"})
*/
public function edit(Comment $comment, Request $request): Response
{
$form = $this->createForm(RecipeType::class, $comment);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid())
{
$em = $this->getDoctrine()->getManager();

$comment->setUpdatedAt(new \DateTime());
$em->flush();

return $this->redirectToRoute('api_recipe_browse');
}
}

/**
* @Route("/", name="api_comment_add", methods={"POST"})
*/
public function add(Request $request, EntityManagerInterface $em): Response
{
$infoFromClientAsArray = json_decode($request->getContent(), true);
$comment = new Comment();
$form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);
$form->submit($infoFromClientAsArray);

if ($form->isValid())
{
$em->persist($comment);
$em->flush();

return $this->json($comment);
}
else 
{
return $this->json((string) $form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
}

}

/**
* @Route("/delete/{id}", name="api_comment_delete", methods="GET")
*/
public function delete(Comment $comment, EntityManagerInterface $em): Response
{
$em->remove($comment);

$em->flush();
// ajouter un flash message

return $this->redirectToRoute('api_comment_browse');
}

}
