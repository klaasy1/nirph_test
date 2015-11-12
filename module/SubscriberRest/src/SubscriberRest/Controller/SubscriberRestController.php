<?php

namespace SubscriberRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Subscriber\Model\Subscriber;          // <-- Add this import
use Subscriber\Form\SubscriberForm;       // <-- Add this import
use Zend\View\Model\JsonModel;

class SubscriberRestController extends AbstractRestfulController
{
    protected $subscriberTable;

    public function getList()
    {
        $results = $this->getSubscriberTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function get($id)
    {
        $subscriber = $this->getSubscriberTable()->getSubscriber($id);

        return new JsonModel(array(
            'data' => $subscriber,
        ));
    }

    public function create($data)
    {
        $form = new SubscriberForm();
        $album = new Subscriber();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $subscriber->exchangeArray($form->getData());
            $id = $this->getSubscriberTable()->saveSubscriber($subscriber);
        }
        
        return $this->get($id);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $subscriber = $this->getSubscriberTable()->getSubscriber($id);
        $form  = new SubscriberForm();
        $form->bind($subscriber);
        $form->setInputFilter($subscriber->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getSubscriberTable()->saveSubscriber($form->getData());
        }

        return $this->get($id);
    }

    public function delete($id)
    {
        $this->getSubscriberTable()->deleteSubscriber($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }

    public function getSubscriberTable()
    {
        if (!$this->subscriberTable) {
            $sm = $this->getServiceLocator();
            $this->subscriberTable = $sm->get('Subscriber\Model\SubscriberTable');
        }
        return $this->subscriberTable;
    }
}
