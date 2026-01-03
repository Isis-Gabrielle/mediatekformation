<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de gestion d'une catégorie.
 * 
 * @author emds
 */
class CategorieType extends AbstractType {

    /**
     * Construction du formulaire.
     *
     * @param FormBuilderInterface $builder L'objet FormBuilder
     * @param array $options Options du formulaire
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Nom'
                ])
                ->add('submit', SubmitType::class, ['label' => 'Enregistrer',
                    'attr' => ['class' => 'btn btn-info']
        ]);
    }

    /**
     * Configuration des options du formulaire.
     *
     * @param OptionsResolver $resolver L'objet OptionsResolver
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
