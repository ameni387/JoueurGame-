<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Game;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('born_at')
            ->add('score')
            ->add('game',EntityType::class, [
                'class'=>Game::class,
                'choice_label' => 'titre',
            ])
            ->add('Valider',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
