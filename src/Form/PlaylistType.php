<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de gestion d'une playlist.
 * 
 * @author emds
 */
class PlaylistType extends AbstractType { /**
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
                    'label' => 'Titre'
                ])
                ->add('description')
                ->add('submit', SubmitType::class, ['label' => 'Enregistrer',
                    'attr' => ['class' => 'btn btn-info']]);
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
            'data_class' => Playlist::class,
        ]);
    }
}
