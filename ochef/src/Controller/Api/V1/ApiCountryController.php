<?php

namespace App\Controller\Api\V1;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    /**
     * @Route("/api/country")
     */
class ApiCountryController extends AbstractController
{
    /**
     * @Route("/", name="api_country_browse", methods={"GET"})
     */
    public function browse(CountryRepository $countryRepository): Response
    {
        $countryForApi = [];

        foreach($countryRepository->findAll() as $country) {
        
            $obj = [];
            $obj['id'] = $country->getId();
            $obj['name'] = $country->getName();
            $obj['picture'] = $country->getPicture();
           
            $countryForApi[] = $obj;
        }
    
        return $this->json($countryForApi, 200, []);
    }

    /**
     * @Route("/{id}", name="api_country_read", methods={"GET"})
     */
    public function read(Country $country): Response
    {
        $countryForApi = [];

            $obj = [];
            $obj['id'] = $country->getId();
            $obj['name'] = $country->getName();
            $obj['created_at'] = $country->getCreatedAt();
            
        
            foreach($country->getRecipes() as $countries ){

                    $obj1 = [];
                    $obj1['id'] = $countries->getId();
                    $obj1['name'] = $countries->getName();
                    $obj['recipes'][] = $obj1;
            }
                
            $countryForApi[] = $obj;
            
        

        return $this->json($countryForApi, 200, []);

    }
}
