<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Formulaire à choix multiple
            ->add('name', TextType::class,[
                'label' => 'Nouvelle Catégorie',
                // 'multiple' => true,
                // 'expanded' => true,
                // 'choices' => [
                //     'Entrée' => "ENTREE",
                //     'Plat Principal' => "PLAT_PRINCIPAL",
                //     'Dessert' => "DESSERT",
                //     'Snack' => "Snack",
                //     'Brunch'=> "Brunch",

                ],
            
          )
              ->add('validate', SubmitType::class)
              ;
              
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
