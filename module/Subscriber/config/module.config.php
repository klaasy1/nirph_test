<?php

 return array(
     'controllers' => array(
         'invokables' => array(
             'Subscriber\Controller\Subscriber' => 'Subscriber\Controller\SubscriberController',
         ),
     ),
     
     // This section is for routing
     'router' => array(
         'routes' => array(
             'subscriber' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/subscriber[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Subscriber\Controller\Subscriber',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     
     'view_manager' => array(
         'template_path_stack' => array(
             'subscriber' => __DIR__ . '/../view',
         ),
     ),
 );