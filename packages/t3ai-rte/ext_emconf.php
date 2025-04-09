<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'T3ai RTE',
    'description' => 'GenAI for TYPO3 RichTextEditor',
    'category' => 'misc',
    'state' => 'stable',
    'author' => 'Carsten Walther',
    'author_email' => 'walther.carsten@web.de',
    'author_company' => '',
    'version' => '0.0.0',
    'constraints' => [
        'depends' => [
            't3ai_core' => '*',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
