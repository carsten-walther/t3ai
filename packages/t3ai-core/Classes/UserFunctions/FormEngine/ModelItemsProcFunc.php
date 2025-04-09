<?php

namespace CarstenWalther\T3aiCore\UserFunctions\FormEngine;

use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use CarstenWalther\T3aiCore\Resource\ResourceRegistry;

class ModelItemsProcFunc
{
    /**
     * @throws NotRegisteredException
     */
    public function itemsProcFunc(array &$params): void
    {
        if (isset($params['flexParentDatabaseRow']['resource'])) {
            $models = ResourceRegistry::getResourceInstance($params['flexParentDatabaseRow']['resource'])->retrieveModels();
            foreach ($models as $model) {
                $params['items'][] = [
                    'label' => $model['label'],
                    'value' => $model['identifier']
                ];
            }
        }
    }
}
