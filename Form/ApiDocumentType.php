<?php

namespace IDCI\Bundle\DocumentManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use IDCI\Bundle\DocumentManagementBundle\Form\EventListener\DocumentTransformEventSubscriber;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;

class ApiDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('data')
            ->add('format')
            ->add('reference');

        $builder->addEventSubscriber(new DocumentTransformEventSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
