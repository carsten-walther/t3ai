<?php

namespace CarstenWalther\T3aiCore\Service;

use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use CarstenWalther\T3aiCore\Resource\ResourceRegistry;

class ResourceService
{
    /**
     * @throws NotRegisteredException
     */
    public function createTextStream(array $arguments): array|string
    {
        return ResourceRegistry::getResourceInstance($arguments['identifier'])->createTextStreamWithPrompt($arguments['prompt']);
    }

    /**
     * @throws NotRegisteredException
     */
    public function createText(array $arguments): array|string
    {
        return ResourceRegistry::getResourceInstance($arguments['identifier'])->createTextWithPrompt($arguments['prompt']);
    }

    /**
     * @throws NotRegisteredException
     */
    public function createTextFromImages(array $arguments): array|string
    {
        return ResourceRegistry::getResourceInstance($arguments['identifier'])->createTextFromImages($arguments['images'], $arguments['prompt']);
    }
}