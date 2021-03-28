<?php

namespace App\Controller\Back;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
* @Route("/admin/comment")
*/
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="admin_comment_browse", methods="GET")
     */
    public function browse(CommentRepository $commentRepo): Response
    {
        $allComment = $commentRepo->findBy([], ['title' => 'ASC']);
        return $this->render('back/comment/browse.html.twig', [
            'controller_name' => 'CommentController',
            'comments' => $allComment,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_comment_read", methods="GET", requirements={"id"="\d+"})
     */
    public function read(Comment $comment): Response
    {
        return $this->render('back/comment/read.html.twig', [
            'comment' => $comment,
            
        ]);
    }
    

    /**
     * @Route("/new", name="admin_comment_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(commentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('admin_comment_browse');
        }

        return $this->render('back/comment/add.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_comment_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Comment $comment, Request $request): Response
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $comment->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('admin_comment_browse');
        }

        return $this->render('back/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
     }

    /**
     * @Route("/delete/{id}", name="admin_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
     // vérification du token généré dans twig par la fonction csrf_token
        $tokenFromForm = $request->request->get('_token');

    // ceci est la clef qui nous a permis de généré le token
        $tokenKey = 'delete-comment' . $comment->getId(); 

        if ($this->isCsrfTokenValid($tokenKey, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_comment_browse');
    }
 }
