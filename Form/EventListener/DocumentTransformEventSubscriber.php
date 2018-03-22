<?php

namespace IDCI\Bundle\DocumentManagementBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */
class DocumentTransformEventSubscriber implements EventSubscriberInterface
{
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

        return array_replace_recursive($data, $parameters);
    }
}