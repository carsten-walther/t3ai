<?php

\CarstenWalther\T3aiCore\Resource\ResourceRegistry::addResource(
    \CarstenWalther\T3aiCore\Resource\Resources\OpenAI::class
);

\CarstenWalther\T3aiCore\Resource\ResourceRegistry::addResource(
    \CarstenWalther\T3aiCore\Resource\Resources\Ollama::class
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'T3aiCore',
    'Chat',
    [
        \CarstenWalther\T3aiCore\Controller\ChatController::class => 'index'
    ],
    [
        \CarstenWalther\T3aiCore\Controller\ChatController::class => 'index'
    ],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
