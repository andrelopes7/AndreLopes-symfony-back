<?php

namespace App\Controller\Back;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/country")
 */

class CountryController extends AbstractController
{
    /**
     * @Route("/", name="admin_country_browse", methods="GET")
     */


    public function browse(CountryRepository $countryRepo): Response
    {
        $allCountry= $countryRepo->findBy([], ['name' => 'ASC']);
        return $this->render('back/country/browse.html.twig', [
            'country_list' => $allCountry,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_country_read", methods="GET", requirements={"id"="\d+"})
     */

    public function read(Country $country): Response
    {
     

        return $this->render('back/country/read.html.twig', [
            'country' => $country,
        ]);
    }
      /**
     * @Route("/new", name="admin_country_add", methods={"GET","POST"})
     */

        public function add(Request $request): Response
        {
            $country = new Country();
            
            $form = $this->createForm(CountryType::class, $country);
       
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid())
            {
            
                $country = $form->getData();
                $country->setCreatedAt(new \DateTime());
                $country->setUpdatedAt(new \DateTime());
    
                $entityManager = $this->getDoctrine()->getManager();
    
                $entityManager->persist($country);
                $entityManager->flush();
    

                return $this->redirectToRoute('admin_country_browse');
            }
    
            return $this->render('back/country/add.html.twig', [
                'country'=>$country,
                'form' => $form->createView(),
            ]);
        }
     /**
     * @Route("/edit/{id}", name="admin_country_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Country $country, Request $request): Response
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $country->setUpdatedAt(new \DateTime());
            $em->flush();

            // TODO flash message

            return $this->redirectToRoute('admin_country_browse');
        }

        return $this->render('back/country/edit.html.twig', [
            'form' => $form->createView(),
            'country' => $country,
        ]);
     }

    /**
     * @Route("/delete/{id}", name="admin_country_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Country $country): Response
    {
     // vérification du token généré dans twig par la fonction csrf_token
        $tokenFromForm = $request->request->get('_token');

    // ceci est la clef qui nous a permis de généré le token
        $tokenKey = 'delete-country' . $country->getId(); 

        if ($this->isCsrfTokenValid($tokenKey, $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_country_browse');
    }
 }