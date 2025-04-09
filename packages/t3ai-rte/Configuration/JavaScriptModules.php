<?php

return [
    'dependencies' => [
        'backend'
    ],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@carsten-walther/t3ai-rte/plugin' => 'EXT:t3ai_rte/Resources/Public/JavaScript/Plugins/chatbot/plugin.js',
        '@carsten-walther/t3ai-rte/modal' => 'EXT:t3ai_rte/Resources/Public/JavaScript/modal.js'
    ]
];
