<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;


class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        
        $builder


            ->add('name', TextType::class, [
                'label' => 'Nom de recette',
                ])
             ->add('portions', TextType::class, [
                'label' => 'Portions',
            ])
                //integer
            ->add('time', TextType::class, [
                    'label' => 'Temps Total  :'
                ])
                //integer
             ->add('preparation', TextType::class, [
                    'label' => 'Temps de Préparation :'
                ])
                //integer
            ->add('cookingTime', TextType::class, [
                    'label' => 'Temps de Cuisson :'
                ])

             ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'label' => 'Type de plat'
               ])
    
               ->add('ingredientList', TextareaType::class,[
                'label' => 'Liste des ingrédients'
               ])

             ->add('ingredient', EntityType::class, [
                   'class' => Ingredient::class,

                    'label' => 'Nom d\'ingredient :',
                 
                    'multiple' => true,
                     'expanded' => true 
                 ])

            ->add('steps', TextareaType::class, [
                  'label' => 'Etapes :'
            ])

                        
            ->add('pictureFile',  VichImageType::class, [
                'label' => 'Image de Recette',
                'required' => true,
                /* 'mapped' => false, */
               
            ])
 
            ->add('country', EntityType::class, [
                    'class' => Country::class,
                    'label' => 'Pays',
            ])
           
             ->add('user')
             ->add('validate', SubmitType::class)

        ;
    }
            
    public function configureOptions(OptionsResolver $resolver)
    { 
        $resolver->setDefaults([
            'data_class' => Recipe::class, 
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}