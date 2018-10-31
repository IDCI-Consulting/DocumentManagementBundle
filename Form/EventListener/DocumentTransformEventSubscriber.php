<?php

namespace IDCI\Bundle\DocumentManagementBundle\Form\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use IDCI\Bundle\DocumentManagementBundle\Model\Document;
use IDCI\Bundle\DocumentManagementBundle\Model\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Constructor.
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
            ),
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
     * Handle data.
     *
     * @param array|Document $data
     *
     * @return array
     */
    public function handleData($data)
    {
        $parameters = array();

        if ($data instanceof Document) {
            $data = $data->toArray();
        }

        $parameters['data'] = isset($data['data']) ? json_decode($data['data'], true) : array();
        $parameters['template'] = isset($data['template']) ? $this->getTemplateId($data['template']) : '';

        return array_merge($data, array_intersect_key($parameters, $data));
    }

    /**
     * Get template id.
     *
     * @param string $id
     *
     * @return int
     *
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
