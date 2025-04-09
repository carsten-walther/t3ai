<?php

return [
    't3ai_rte_create' => [
        'path' => '/t3ai-rte/create',
        'target' => \CarstenWalther\T3aiRte\Controller\AjaxController::class . '::createAction',
    ],
];
