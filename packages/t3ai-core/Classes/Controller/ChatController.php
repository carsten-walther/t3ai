<?php

namespace CarstenWalther\T3aiCore\Controller;

use CarstenWalther\T3aiCore\Domain\Model\Resource;
use CarstenWalther\T3aiCore\Domain\Repository\ResourceRepository;
use CarstenWalther\T3aiCore\Service\ResourceService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ChatController extends ActionController
{
    public function __construct(
        protected readonly ResourceRepository $resourceRepository,
        protected readonly ResourceService $resourceService,
    ) {}

    protected function initializeView($view): void
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject');
        $view->assign('contentObjectData', $contentObjectData ? $contentObjectData->data : null);
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE'])) {
            $view->assign('pageData', $GLOBALS['TSFE']->page);
        }
    }

    protected function initializeAction(): void
    {
        $this->buildSettings();
    }

    public function indexAction(): ResponseInterface
    {
        if (!(int)$this->settings['resource']) {
            return $this->htmlResponse();
        }

        /** @var Resource $resource */
        $resource = $this->resourceRepository->findOneBy([
            'uid' => (int)$this->settings['resource']
        ]);

        $this->view->assignMultiple([
            'identifier' => $resource->getResource()
        ]);

        return $this->htmlResponse();
    }

    protected function buildSettings(): void
    {
        $typoScriptSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'T3aiCore',
            't3aicore_chat'
        );

        $this->settings = array_merge($this->settings, $typoScriptSettings);
    }
}