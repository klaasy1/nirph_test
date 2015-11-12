<?php

namespace Subscriber\Model;

 use Zend\Db\TableGateway\TableGateway;

 class SubscriberTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getSubscriber($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveSubscriber(Subscriber $subscriber)
     {
         $data = array(
             'username' => $subscriber->username,
             'email'  => $subscriber->email,
             'password' => $subscriber->password,
             'product_id'  => $subscriber->product_id,
         );

         $id = (int) $subscriber->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getSubscriber($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Subscriber id does not exist');
             }
         }
     }

     public function deleteSubscriber($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }

?>