<?php

namespace App\Form;

use App\Entity\Policy;
use App\Form\Transformer\JsonStringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PolicyType extends AbstractType
{
    public function __construct(private JsonStringToArrayTransformer $jsonTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome',
                'constraints' => [new Assert\NotBlank()],
                'attr' => ['class' => 'input m-1 w-full'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descrizione',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'class' => 'textarea m-1 w-full',
                ],
            ])
            ->add('rulesJson', TextareaType::class, [
                'label' => 'rules_json (JSON)',
                'required' => false,
                'attr' => [
                    'rows' => 12,
                    'placeholder' => 'Inserisci un oggetto JSON con le regole (vedi docs/DRAVEN_policy_rules.md)',
                    'class' => 'm-1 w-full',
                ],
            ]);

        $builder->get('rulesJson')->addModelTransformer($this->jsonTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Policy::class,
        ]);
    }
}
