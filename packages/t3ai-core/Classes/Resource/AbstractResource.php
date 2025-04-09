<?php

namespace CarstenWalther\T3aiCore\Resource;

use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractResource implements ResourceInterface
{
    use LoggerAwareTrait;

    public AbstractResource $resource;

    public function __construct(array $arguments)
    {
        $this->logger = GeneralUtility::makeInstance(LogManager::class)?->getLogger(__CLASS__);
    }
}
