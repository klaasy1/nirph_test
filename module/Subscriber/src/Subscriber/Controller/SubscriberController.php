<?php

namespace Subscriber\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Subscriber\Model\Subscriber;          
use Subscriber\Form\SubscriberForm; 

class SubscriberController extends AbstractActionController
{
     
    protected $subscriberTable;
     
    public function indexAction()
    {
        return new ViewModel(array(
            'subscribers' => $this->getSubscriberTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        
        $product_id = (int) $this->params()->fromRoute('id', 0);
        if (!$product_id) {
            //return $this->redirect()->toRoute('product');
        }
        
        $form = new SubscriberForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $subscriber = new Subscriber();
            $form->setInputFilter($subscriber->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                print_r($form->getData());
                $subscriber->exchangeArray($form->getData());
                $this->getSubscriberTable()->saveSubscriber($subscriber);

                // Redirect to list of subscribers
                return $this->redirect()->toRoute('subscriber');
            }
        }
        return array(
            'product_id' => $product_id,
            'form' => $form,
            );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('subscriber', array(
                'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $subscriber = $this->getSubscriberTable()->getSubscriber($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('subscriber', array(
                'action' => 'index'
            ));
        }

        $form  = new SubscriberForm();
        $form->bind($subscriber);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($subscriber->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSubscriberTable()->saveSubscriber($subscriber);

                // Redirect to list of subscriber
                return $this->redirect()->toRoute('subscriber');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('subscriber');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getSubscriberTable()->deleteSubscriber($id);
            }

            // Redirect to list of subscriber
            return $this->redirect()->toRoute('subscriber');
        }

        return array(
            'id'    => $id,
            'subscriber' => $this->getSubscriberTable()->getSubscriber($id)
        );
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