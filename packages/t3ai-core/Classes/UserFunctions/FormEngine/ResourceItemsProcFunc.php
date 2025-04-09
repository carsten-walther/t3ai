<?php

namespace CarstenWalther\T3aiCore\UserFunctions\FormEngine;

use Doctrine\DBAL\Exception;
use CarstenWalther\T3aiCore\Resource\ResourceRegistry;

class ResourceItemsProcFunc
{
    /**
     * @throws Exception
     */
    public function itemsProcFunc(array &$params): void
    {
        foreach (ResourceRegistry::getResources() as $item) {
            $params['items'][] = [
                $item['title'],
                $item['identifier']
            ];
        }
    }
}
