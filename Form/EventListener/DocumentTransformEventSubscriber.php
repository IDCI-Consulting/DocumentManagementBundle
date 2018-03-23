<?php

namespace IDCI\Bundle\DocumentManagementBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;

/**
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */
class DocumentTransformEventSubscriber implements EventSubscriberInterface
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

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => array(
                array('onPreSubmit', 999),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $data = $this->handleData($data);

        $event->setData($data);
    }

    /**
     * Handle data
     *
     * @param array $data
     *
     * @return array
     */
    public function handleData(array $data)
    {
        $parameters = array();

        $parameters['data'] = $data['data'] ? json_decode($data['data'], true) : array();
        $parameters['template'] = $this->getTemplateId($data['template']);

        return array_replace_recursive($data, $parameters);
    }

    /**
     * Get template id
     *
     * @param  string $id
     *
     * @return integer
     * @throws NotFoundHttpException
     */
    protected function getTemplateId($id)
    {
        $template = $this
            ->manager
            ->getRepository(Template::class)
            ->findByIdOrSlug($id);

        if (null === $template) {
            throw new NotFoundHttpException(sprintf(
                'Template with slug %s not found',
                $id
            ));
        }

        return $template->getId();
    }
}
