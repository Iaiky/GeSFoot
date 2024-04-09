<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\National;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectNationalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('national', EntityType::class, [
            'class' =>National::class,
            'choice_label' => 'nom', // Champs à afficher dans la liste déroulante
            'placeholder' => 'Sélectionnez une équipe nationale', // Texte par défaut
            'mapped' => true, // Ensure this is set to true
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
