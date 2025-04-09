<?php

return [
    'frontend' => [
        'carsten-walther/t3ai-core/api' => [
            'target' => \CarstenWalther\T3aiCore\Middleware\ApiMiddleware::class,
            'before' => [
                'typo3/cms-frontend/maintenance-mode',
            ],
            'after' => [
                'typo3/cms-frontend/eid',
            ]
        ],
    ],
];