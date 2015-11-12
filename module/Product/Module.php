<?php

 namespace Product;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 
  // Add these import statements:
 use Product\Model\Product;
 use Product\Model\ProductTable;
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
                 'Product\Model\ProductTable' =>  function($sm) {
                     $tableGateway = $sm->get('ProductTableGateway');
                     $table = new ProductTable($tableGateway);
                     return $table;
                 },
                 'ProductTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Product());
                     return new TableGateway('product', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }
 }
 
 ?>