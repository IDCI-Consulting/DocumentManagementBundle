<?php

namespace IDCI\Bundle\DocumentManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\Common\Persistence\ObjectManager;
use IDCI\Bundle\DocumentManagementBundle\Form\EventListener\DocumentTransformEventSubscriber;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;

class ApiDocumentType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param TemplateManager $templateManager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('data')
            ->add('format')
            ->add('reference')
            ->add('template');

        $builder->addEventSubscriber(new DocumentTransformEventSubscriber($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Document::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'api_document';
    }
}
