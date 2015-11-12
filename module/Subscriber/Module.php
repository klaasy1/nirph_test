<?php

 namespace Subscriber;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 
  // Add these import statements:
 use Subscriber\Model\Subscriber;
 use Subscriber\Model\SubscriberTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;

 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }
     
      // Add this method:
     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Subscriber\Model\SubscriberTable' =>  function($sm) {
                     $tableGateway = $sm->get('SubscriberTableGateway');
                     $table = new SubscriberTable($tableGateway);
                     return $table;
                 },
                 'SubscriberTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Subscriber());
                     return new TableGateway('subscriber', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }
 }
 
 ?>