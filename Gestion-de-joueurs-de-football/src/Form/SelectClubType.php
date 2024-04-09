<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('club', EntityType::class, [
            'class' =>Club::class,
            'choice_label' => 'nomClub', // Champs à afficher dans la liste déroulante
            'placeholder' => 'Sélectionnez un club', // Texte par défaut
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
