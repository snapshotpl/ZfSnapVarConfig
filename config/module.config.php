<?php

return [
    'service_manger' => [
        'factories' => [
            \ZfSnapVarConfig\VarConfigService::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
];
