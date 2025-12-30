<?php

namespace App\Form;

use App\Entity\Environment;
use App\Entity\Policy;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EnvironmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'label' => 'Project',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('name', ChoiceType::class, [
                'label' => 'Environment',
                'choices' => [
                    'Stage' => 'stage',
                    'Demo' => 'demo',
                    'Production' => 'prod',
                ],
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'select m-1 w-full'],
            ])
            ->add('policy', EntityType::class, [
                'class' => Policy::class,
                'choice_label' => 'name',
                'label' => 'Policy',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'select m-1 w-full'],
            ])
            ->add('isEnabled', CheckboxType::class, [
                'label' => 'Abilitato',
                'required' => false,
                'attr' => ['class' => 'm-1'],
            ])
            ->add('lockStrategy', ChoiceType::class, [
                'label' => 'Lock strategy',
                'choices' => [
                    'Exclusive' => 'exclusive',
                    'None' => 'none',
                ],
                'attr' => ['class' => 'select m-1 w-full'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Environment::class,
        ]);
    }
}
