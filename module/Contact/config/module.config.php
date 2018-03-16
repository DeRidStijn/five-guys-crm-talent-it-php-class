<?php

namespace Contact;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Controller\ContactController::class => Controller\Factory\ContactControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'contact' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/contact',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'overview' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/overview[/page/:page]',
                            'defaults' => [
                                'action' => 'overview',
                                'page' => 1,
                            ],
                            'constraints' => [
                                'page' => '\d+',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit[/:id]',
                            'defaults' => [
                                'action' => 'edit', 
                            ],
                            'constrains' => [
                                'id' => '\d+',
                            ],  
                        ],    
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'contact' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Entity\Factory\ContactHydratorFactory::class => Entity\Factory\ContactHydratorFactory::class,
        ],
        'aliases' => [
            Model\ContactModelInterface::class => Model\ContactModel::class,
            Model\EmailAddressModelInterface::class => Model\EmailAddressModel::class,
            Model\AddressModelInterface::class => Model\AddressModel::class,
            Model\CountryModelInterface::class => Model\CountryModel::class,
            Model\ImageModelInterface::class => Model\ImageModel::class,
        ],
        'factories' => [
            Model\ContactModel::class => Model\Factory\ContactModelFactory::class,
            Model\EmailAddressModel::class => Model\Factory\EmailAddressModelFactory::class,
            Model\AddressModel::class => Model\Factory\AddressModelFactory::class,
            Model\CountryModel::class => Model\Factory\CountryModelFactory::class,
            Model\ImageModel::class => Model\Factory\ImageModelFactory::class,
            Service\ContactFormServiceInterface::class => Service\Factory\ContactFormServiceFactory::class,
        ],
    ],
];