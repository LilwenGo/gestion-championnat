<?php
namespace App\Form;

use App\Entity\Day;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectDayFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', EntityType::class, [
                'class' => Day::class,
                'choices' => $options['days'],
                'choice_label' => 'number',
                'choice_value' => 'id',
                'placeholder' => 'Choisissez une journée',
            ])
            ->add('filtrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'days' => null,
            'attr' => [
                'class' => "flex-x",
                "data-turbo" => 'false'
            ]
        ]);
    }
}
