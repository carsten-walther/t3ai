<?php

namespace CarstenWalther\T3aiCore\Command;

use CarstenWalther\T3aiCore\Domain\Repository\ResourceRepository;
use CarstenWalther\T3aiCore\Exceptions\InvalidResourceException;
use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use CarstenWalther\T3aiCore\Resource\Filter\ProcessedFilter;
use CarstenWalther\T3aiCore\Service\ResourceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;

#[AsCommand(
    name: 't3ai-core:metadata',
    description: 'Generate file metadata by AI.'
)]
final class MetadataCommand extends Command
{
    public function __construct(
        protected readonly ResourceRepository $resourceRepository,
        protected readonly StorageRepository $storageRepository,
        protected readonly ResourceService $resourceService,
        protected readonly ImageService $imageService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Generate file metadata by AI.')
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'Resource uid for metadata generation'
            )
            ->addArgument(
                'storage',
                InputArgument::REQUIRED,
                'Storage uid for metadata generation'
            )
            ->addArgument(
                'maxNumberOfFiles',
                InputArgument::OPTIONAL,
                'Maximum number of files to process',
                5
            )
            ->addArgument(
                'prompt',
                InputArgument::OPTIONAL,
                'Prompt',
                'Act as an SEO expert: What can be seen in these images? Answer the question as briefly as possible in a maximum of one sentence for the alternative text and in a maximum of three sentences for the description text. Output the two results in a structured format.'
            );
    }

    /**
     * @throws InsufficientFolderAccessPermissionsException
     * @throws NotRegisteredException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $resource = (int)$input->getArgument('resource');
        $storage = (int)$input->getArgument('storage');
        $maxNumberOfFiles = (int)$input->getArgument('maxNumberOfFiles');
        $prompt = $input->getArgument('prompt');
        try {
            $this->doMagic($io, $resource, $storage, $maxNumberOfFiles, $prompt);
        } catch (InvalidResourceException) {
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    /**
     * @throws InsufficientFolderAccessPermissionsException
     * @throws NotRegisteredException
     */
    protected function doMagic(SymfonyStyle $io, int $resourceUid, int $storageUid, int $maxNumberOfFiles, string $prompt): void
    {
        $resource = $this->resourceRepository->findByUid($resourceUid);
        if ($resource === null) {
            throw new \RuntimeException(self::class . ' misconfiguration: "Resource to index" must be an existing resource.', 1615020910);
        }

        $storage = $this->storageRepository->findByUid($storageUid);
        if ($storage === null) {
            throw new \RuntimeException(self::class . ' misconfiguration: "Storage to index" must be an existing storage.', 1615020911);
        }

        $config = $this->array_map_assoc(function ($key, $value) {
            return [ucfirst($key), $value];
        }, $resource->getConfigurationAsArray());

        $io->title('T3aiCore: Metadata');
        $io->section('Settings');
        $io->table(
            ['Parameter', 'Value'],
            [
                ['Prompt', $prompt],
                ['Storage', $storage->getName()],
                ['Max. number of files', $maxNumberOfFiles],
                ['', ''],
                ['Resource', $resource->getTitle()],
                ['Description', $resource->getDescription()],
                ...$config
            ]
        );
        $io->section('Processing');

        $filter = GeneralUtility::makeInstance(ProcessedFilter::class);
        $storage->addFileAndFolderNameFilter([$filter, 'filterProcessedFilesAndFolders']);

        if ($availableFiles = $storage->getFilesInFolder($storage->getRootLevelFolder(true), 0, $maxNumberOfFiles, true, true)) {
            foreach ($io->progressIterate($availableFiles) as $availableFile) {
                if (($storage->getDriverType() === 'Local' && $fileObject = $storage->getFile($availableFile->getIdentifier()))) {
                    if ($fileObject->isImage()) {
                        $processedImage = $this->imageService->applyProcessingInstructions($fileObject, [
                            'minWidth' => 128,
                            'minHeight' => 128,
                            'maxWidth' => 256,
                            'maxHeight' => 256,
                        ]);

                        $message = $this->resourceService->createTextFromImages([
                            'identifier' => $resource->getResource(),
                            'images' => [
                                base64_encode($processedImage->getContents())
                            ],
                            'prompt' => $prompt,
                        ]);

                        $processedImage->getOriginalFile()->getMetaData()->add([
                            'alternative' => trim($message),
                            'alternative_ai_generated' => true,
                            'description' => trim($message),
                            'description_ai_generated' => true
                        ])->save();

                        $io->info([
                            sprintf("File: %s", $processedImage->getOriginalFile()->getPublicUrl()),
                            sprintf("Alternative text: %s", $message)
                        ]);
                    } else {
                        $io->info([
                            sprintf("File: %s skipped", $fileObject->getPublicUrl())
                        ]);
                    }
                }
            }
        }
    }

    private function array_map_assoc(callable $callback, array $array): array
    {
        return array_map(static function($key) use ($callback, $array) {
            return $callback($key, $array[$key]);
        }, array_keys($array));
    }
}