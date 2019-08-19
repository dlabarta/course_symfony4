<?php

namespace App\Form;

use App\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['required' => false])
            ->add('email', null, ['label'=> 'Client email', 'required' => false])
            ->add('description', null, ['required' => false])
            ->add('solved',
                ChoiceType::class,
                [
                    'choices' => [
                        'No' => 0,
                        'Sí' => 1,
                    ],
                    'expanded' => true,
                    'required' => true,
                ])
            ->add(
                'solvedAt',
                DateTimeType::class,
                [
                    'required' => false,
                    'widget' => 'single_text'
                ]
            )
            ->add('category')
            ->add('tags',
                null ,
                [
                    'by_reference' => false
                ]
            )
            ->add('attachment',
                FileType::class,
                [
                    'label' => 'Attachment (PDF file)',
                    'required' => false,
                ]
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'issue';
    }
}
