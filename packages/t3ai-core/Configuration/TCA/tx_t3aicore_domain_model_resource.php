<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource',
        'label' => 'title',
        'label_alt' => 'description',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'rootLevel' => 1,
        'adminOnly' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'default_sortby' => 'crdate ASC',
        'iconfile' => 'EXT:t3ai_core/Resources/Public/Icons/tx_t3aicore_domain_model_resource.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;title_description_hidden, resource, configuration'
        ],
    ],
    'palettes' => [
        'title_description_hidden' => [
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource',
            'description' => '',
            'showitem' => 'title, hidden, --linebreak--, description',
        ]
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource.title',
            'config' => [
                'type' => 'input',
            ]
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
            ]
        ],
        'resource' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource.resource',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
                'items' => [],
                'itemsProcFunc' => \CarstenWalther\T3aiCore\UserFunctions\FormEngine\ResourceItemsProcFunc::class . '->itemsProcFunc'
            ],
        ],
        'configuration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:tx_t3aicore_domain_model_resource.configuration',
            'displayCond' => 'FIELD:resource:>:0',
            'config' => [
                'type' => 'flex',
                'ds_pointerField' => 'resource',
                'ds' => [
                    'default' => 'FILE:EXT:t3ai_core/Configuration/FlexForms/Resources/Default.xml',
                ],
            ],
        ],
    ],
];
