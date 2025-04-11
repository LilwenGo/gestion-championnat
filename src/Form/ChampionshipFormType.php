<?php
namespace App\Form;

use App\Entity\Championship;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChampionshipFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom'
            ])
            ->add('startDate', DateType::class, [
                'label' => "Date de début"
                ])
            ->add('endDate', DateType::class, [
                'label' => "Date de fin"
                ])
            ->add('wonPoint', NumberType::class, [
                'label' => "Nombre de points du gagnant"
                ])
            ->add('lostPoint', NumberType::class, [
                'label' => "Nombre de points du perdant"
                ])
            ->add('drawPoint', NumberType::class, [
                'label' => "Nombre de points en cas de match nul"
                ])
            ->add('teams', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'label' => "Équipes",
                'multiple' => true,
                'required' => false,
                'placeholder' => 'Choisissez des équipes',
            ])
            ->add('typeRanking', options: [
                'label' => 'Type de classement'
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Championship::class,
        ]);
    }
}
