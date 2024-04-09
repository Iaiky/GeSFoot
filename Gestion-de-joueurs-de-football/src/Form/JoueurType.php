<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\National;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDeNaissance')
            ->add('nationalite')
            ->add('parcours')
            ->add('national', EntityType::class, [
                // looks for choices from this entity
                'class' => National::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nomClub'
            ])
            ->add('nombreDeBut')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
