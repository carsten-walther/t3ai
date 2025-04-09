<?php

namespace CarstenWalther\T3aiRte\Controller;

use Doctrine\DBAL\Exception;
use CarstenWalther\T3aiCore\Domain\Repository\ResourceRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\PageRendererBackendSetupTrait;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ChatbotController
{
    use PageRendererBackendSetupTrait;

    protected PageRenderer $pageRenderer;
    protected BackendViewFactory $backendViewFactory;
    protected ResourceRepository $resourceRepository;
    protected ExtensionConfiguration $extensionConfiguration;

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function injectBackendViewFactory(BackendViewFactory $backendViewFactory): void
    {
        $this->backendViewFactory = $backendViewFactory;
    }

    public function injectResourceRepository(ResourceRepository $resourceRepository): void
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function injectExtensionConfiguration(ExtensionConfiguration $extensionConfiguration): void
    {
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->setUpBasicPageRendererForBackend($this->pageRenderer, $this->extensionConfiguration, $request, $this->getLanguageService());

        $this->pageRenderer->addInlineLanguageLabelFile('EXT:t3ai_rte/Resources/Private/Language/locallang.xlf');
        $this->pageRenderer->addCssFile(GeneralUtility::getFileAbsFileName('EXT:backend/Resources/Public/Css/backend.css'));
        $this->pageRenderer->getJavaScriptRenderer()->addJavaScriptModuleInstruction(
            JavaScriptModuleInstruction::create('@carsten-walther/t3ai-rte/modal')
                ->invoke('initialize', '')
        );
        $this->pageRenderer->setTitle('Chatbot');

        $view = $this->backendViewFactory->create($request, ['typo3/cms-backend']);
        $view->assignMultiple([
            'extensionConfiguration' => $this->extensionConfiguration,
            'resource' => $this->resourceRepository->findByUid(2)
        ]);

        $this->pageRenderer->setBodyContent($view->render('Chatbot/Modal'));

        return $this->pageRenderer->renderResponse();
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}