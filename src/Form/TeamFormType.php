<?php
namespace App\Form;

use App\Entity\Team;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom'
            ])
            ->add('creationDate', DateType::class, [
                'label' => "Date de création"
                ])
            ->add('stade', options: [
                'label' => 'Stade'
            ])
            ->add('president', options: [
                'label' => 'Président'
            ])
            ->add('coach', options: [
                'label' => 'Entraîneur'
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un pays',
            ])
            ->add('logo', FileType::class, [
                'label' => "Logo",
                'mapped' => false,
                'required' => false
                ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
            'attr' => [
                'enctype' => "multipart/form-data"
            ]
        ]);
    }
}
