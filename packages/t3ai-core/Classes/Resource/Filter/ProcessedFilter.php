<?php

namespace CarstenWalther\T3aiCore\Resource\Filter;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

class ProcessedFilter
{
    public static function filterProcessedFilesAndFolders(string $itemName, string $itemIdentifier, string $parentIdentifier, array $additionalInformation, DriverInterface $driverInstance)
    {
        $importExportFolderSubPath = '/_processed_/';
        if (str_ends_with($parentIdentifier, $importExportFolderSubPath) || str_contains($itemIdentifier, $importExportFolderSubPath)) {
            $backendUser = self::getBackendUser();
            if ($backendUser === null || !$backendUser->isExportEnabled()) {
                return -1;
            }
        }

        return true;
    }

    protected static function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'] ?? null;
    }
}
