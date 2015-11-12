<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'SubscriberRest\Controller\SubscriberRest' => 'SubscriberRest\Controller\SubscriberRestController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'Subscriber-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/subscriber-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'SubscriberRest\Controller\SubscriberRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);