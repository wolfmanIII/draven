<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('description', TextType::class, [
                'label' => 'Descrizione',
                'required' => false,
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Attivo',
                'required' => false,
                'attr' => ['class' => 'm-1'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
