<?php
namespace App\Form;

use App\Entity\Day;
use App\Entity\Game;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', EntityType::class, options: [
                'label' => 'Journée',
                'class' => Day::class,
                'choices' => $options['days'],
                'choice_label' => 'number',
                'choice_value' => 'id',
                'placeholder' => 'Choisissez une journée',
            ])
            ->add('team1', EntityType::class, options: [
                'label' => 'Équipe 1',
                'class' => Team::class,
                'choices' => $options['teams'],
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Choisissez une équipe',
            ])
            ->add('team2', EntityType::class, options: [
                'label' => 'Équipe 2',
                'class' => Team::class,
                'choices' => $options['teams'],
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Choisissez une équipe',
            ])
            ->add('team1Point', NumberType::class, [
                'label' => "Nombre de points de l'équipe 1"
                ])
            ->add('team2Point', NumberType::class, [
                'label' => "Nombre de points de l'équipe 2"
                ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'days' => null,
            'teams' => null
        ]);
    }
}