<?php

namespace CarstenWalther\T3aiCore\Domain\Model;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Resource extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    protected string $resource = '';
    protected string $configuration = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Resource
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Resource
    {
        $this->description = $description;
        return $this;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setResource(string $resource): Resource
    {
        $this->resource = $resource;
        return $this;
    }

    public function getConfiguration(): string
    {
        return $this->configuration;
    }

    public function setConfiguration(string $configuration): Resource
    {
        $this->configuration = $configuration;
        return $this;
    }

    public function getConfigurationAsArray(): array
    {
        return GeneralUtility::makeInstance(FlexFormService::class)
            ?->convertFlexFormContentToArray($this->configuration);
    }
}
