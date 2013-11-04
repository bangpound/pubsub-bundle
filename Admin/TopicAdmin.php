<?php

namespace Bangpound\Bundle\PubsubBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sputnik\Bundle\PubsubBundle\Hub\HubSubscriberInterface;
use Sputnik\Bundle\PubsubBundle\Model\Topic;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TopicAdmin
 * @package Bangpound\Bundle\PubsubBundle\Admin
 */
class TopicAdmin extends Admin
{
    private $subscriber;

    /**
     * @param HubSubscriberInterface $subscriber
     */
    public function setHubSubscriber(HubSubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('hubName', 'hub_name', array('required' => true))
            ->add('topicUrl', 'url', array('required' => true))
        ;

        $subscriber = $this->subscriber;

        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($subscriber) {
            $data = $event->getData();

            /* @var $topic Topic */
            $topic = $subscriber->subscribe($data['topicUrl'], $data['hubName']);

            if ($topic === false) {
                $event->getForm()->addError(new FormError('Topic was not created'));
            }
        });
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('hubName')
            ->add('topicUrl')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('topicUrl', null, array('route' => array('name' => 'show')))
            ->add('hubName')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param ShowMapper $filter
     */
    protected function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('topicUrl', null, array('template' => 'RshiefPubsubBundle:CRUD:show_orm_one_to_one.html.twig'))
            ->add('hubName')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param mixed $object
     */
    public function create($object)
    {
        $this->prePersist($object);
        $this->postPersist($object);
        $this->createObjectSecurity($object);
    }
}