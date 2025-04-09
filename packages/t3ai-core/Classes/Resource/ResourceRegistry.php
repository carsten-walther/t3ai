<?php

namespace CarstenWalther\T3aiCore\Resource;

use Doctrine\DBAL\Exception;
use CarstenWalther\T3aiCore\Domain\Repository\ResourceRepository;
use CarstenWalther\T3aiCore\Exceptions\InvalidResourceException;
use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResourceRegistry
{
    private static array $resources = [];

    /**
     * @throws InvalidResourceException
     */
    public static function addResource(string $className): void
    {
        if (!is_subclass_of($className, ResourceInterface::class)) {
            throw new InvalidResourceException(sprintf('"%s" does not implement "%s" and is therefor invalid', $className, ResourceInterface::class), 1558815164);
        }

        self::$resources[$className] = [
            'className' => $className,
            'title' => $className::TITLE,
            'instance' => null,
        ];
    }

    public static function addPiFlexFormFile(string $identifier, string $flexFormFile): void
    {
        if (is_array($GLOBALS['TCA']['tx_t3aicore_domain_model_resource']['columns']) && is_array($GLOBALS['TCA']['tx_t3aicore_domain_model_resource']['columns']['configuration']['config']['ds'])) {
            $GLOBALS['TCA']['tx_t3aicore_domain_model_resource']['columns']['configuration']['config']['ds'][$identifier] = $flexFormFile;
        }
    }

    /**
     * @throws Exception
     */
    public static function getResources(bool $activeOnly = false): array
    {
        $available = [];
        if ($activeOnly) {
            $queryBuilder = (new ConnectionPool())->getQueryBuilderForTable('tx_t3aicore_domain_model_resource');
            $queryBuilder->getRestrictions()->removeAll();
            foreach (self::$resources as $identifier => $config) {
                $result = $queryBuilder
                    ->select('*')
                    ->from('tx_t3aicore_domain_model_resource')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'resource',
                            $queryBuilder->createNamedParameter($identifier)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if ($result && $result['configuration'] !== '') {
                    $available[] = [
                        'title' => $config['title'],
                        'identifier' => $identifier
                    ];
                }
            }
        } else {
            foreach (self::$resources as $identifier => $config) {
                $available[] = [
                    'title' => $config['title'],
                    'identifier' => $identifier
                ];
            }
        }

        return $available;
    }

    /**
     * @throws NotRegisteredException
     */
    public static function getResourceInstance(string $identifier): AbstractResource
    {
        if (!array_key_exists($identifier, self::$resources)) {
            throw new NotRegisteredException(sprintf('"%s" has not been registered as a Resource', $identifier), 1558815704);
        }

        $resource = GeneralUtility::makeInstance(ResourceRepository::class)?->findOneBy([
            'resource' => $identifier
        ]);

        if ($resource) {
            self::$resources[$identifier]['options']['arguments']['configuration'] = $resource->getConfigurationAsArray();
        }

        if (self::$resources[$identifier]['instance'] === null) {
            self::$resources[$identifier]['instance'] = GeneralUtility::makeInstance(
                self::$resources[$identifier]['className'],
                self::$resources[$identifier]['options']['arguments']
            );
        }

        return self::$resources[$identifier]['instance'];
    }
}