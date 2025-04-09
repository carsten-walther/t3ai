<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'T3ai Core',
    'description' => 'GenAI for TYPO3',
    'category' => 'misc',
    'state' => 'stable',
    'author' => 'Carsten Walther',
    'author_email' => 'walther.carsten@web.de',
    'author_company' => '',
    'version' => '0.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '^13.4',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
