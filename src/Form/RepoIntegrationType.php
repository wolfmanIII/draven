<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\RepoIntegration;
use App\Form\Transformer\JsonStringToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RepoIntegrationType extends AbstractType
{
    public function __construct(private JsonStringToArrayTransformer $jsonTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'label' => 'Project',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'select m-1 w-full'],
            ])
            ->add('provider', ChoiceType::class, [
                'label' => 'Provider',
                'choices' => [
                    'Bitbucket' => 'bitbucket',
                    'GitHub' => 'github',
                    'GitLab' => 'gitlab',
                ],
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'select m-1 w-full'],
            ])
            ->add('repoFullName', TextType::class, [
                'label' => 'Repo (org/repo o workspace/repo)',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('defaultBranch', TextType::class, [
                'label' => 'Branch di default',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('pipelineSelector', TextareaType::class, [
                'label' => 'Pipeline selector (JSON mapping env → workflow)',
                'required' => false,
                'attr' => [
                    'rows' => 6,
                    'placeholder' => '{"stage": "pipeline-stage", "demo": "pipeline-demo", "prod": "pipeline-prod"}',
                    'class' => 'input m-1 w-full',
                ],
            ])
            ->add('webhookSecret', TextType::class, [
                'label' => 'Webhook secret',
                'required' => false,
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('credentialRef', TextType::class, [
                'label' => 'Credential ref',
                'required' => false,
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Attivo',
                'required' => false,
                'attr' => ['class' => 'm-1'],
            ]);

        $builder->get('pipelineSelector')->addModelTransformer($this->jsonTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RepoIntegration::class,
        ]);
    }
}
