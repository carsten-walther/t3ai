<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_file_metadata',
    [
        'alternative_ai_generated' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.alternative_ai_generated',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'description_ai_generated' => [
            'exclude' => true,
            'label' => 'LLL:EXT:t3ai_core/Resources/Private/Language/locallang_db.xlf:sys_file_metadata.description_ai_generated',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
    ],
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_metadata',
    'alternative_ai_generated',
    '',
    'after:alternative'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_metadata',
    'description_ai_generated',
    '',
    'after:description'
);
