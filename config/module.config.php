<?php

return [
    'service_manger' => [
        'factories' => [
            \ZfSnapVarConfig\VarConfigService::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
];