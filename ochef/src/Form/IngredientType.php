<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, [
                'label' => 'Nom de l\'ingrédient',
                // 'multiple' => true,
                'constraints'=> [
                    new NotBlank()
                ]
              ])

            ->add('type', ChoiceType::class,[
                'label' => 'Quel type d\'ingrédient',
                'expanded' => true,
                'choices' => [
                    'Légume' => "Légume",
                    'Fruit' => "Fruit",
                    'Poisson'=> 'Poisson',
                    'Fruit de mer' => 'Fruit de mer',
                    'Viande' => 'Meat',
                    'charcuterie'=>'charcuterie',
                    'Produit laitier'=> 'produit laitier',
                    'herbe' => 'Herbe',
                    'épice'=> 'épice',
                    'Céréales / féculents'=> 'Céréales / féculents',
                    'produit sucré'=>'produit sucré',
                ],
            ])
            
            ->add('type_name', ChoiceType::class, [
                'label'=> 'choisir la sous cathégorie',
                'expanded' => true,
                'choices' => [
                    'N/A'=>'N/A',
                    'Condiment'=>'condiment', 
                    'légume racine'=> 'légume racine',
                    'légume vert'=> 'légume vert',
                    'fruit oléagineux'=>'fruit oléagineux',
                    'Agrume'=>'Agrume',
                    'fruits rouges'=>'fruits rouges',
                    'Volaille' => "Volaille",
                    'Viande Rouge' => "Viande Rouge",
                    'Gibier'=> 'Gibier',
                    'Abats'=> 'Abats',
                    'Coquillages'=> 'Coquillages',
                    'Crustacés' => 'Crustacés',
                    'Pommes de terre'=> 'Pomme de terre',
                    'Riz' => 'Riz',
                    'pâte' => 'pâte',
                    
                ],
            ])
            ->add('description')
            // ->add('created_at',null)
            // ->add('updated_at',null)
            ->add('recipes', null)
            ->add('users')
            ->add('validate', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
