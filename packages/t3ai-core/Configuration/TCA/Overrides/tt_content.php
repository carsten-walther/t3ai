<?php

(static function (): void {
    $pluginKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'T3aiCore',
        'Chat',
        'Chat',
        null,
        't3ai-core',
        'A simple chat widget.'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:t3ai_core/Configuration/FlexForms/Chat.xml',
        $pluginKey
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
        $pluginKey,
        'after:palette:headers'
    );
})();