<?php

namespace CarstenWalther\T3aiCore\Resource\Resources;

use CarstenWalther\T3aiCore\Resource\AbstractResource;
use Exception;
use LLPhant\Chat\OpenAIChat;
use LLPhant\OpenAIConfig;
use Psr\Http\Message\StreamInterface;
use Random\RandomException;

class OpenAI extends AbstractResource
{
    public const TITLE = "OpenAI";
    public array $arguments;
    public mixed $config;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;

        parent::__construct($this->arguments);

        $this->config = new OpenAIConfig();
        $this->config->apiKey = $this->arguments['configuration']['apiKey'];
    }

    /**
     * @throws Exception
     */
    public function createTextWithPrompt(string $prompt): string
    {
        return (new OpenAIChat($this->config))->generateText($prompt);
    }

    /**
     * @throws RandomException
     * @throws Exception
     */
    public function createTextStreamWithPrompt(string $prompt): array
    {
        $stream = (new OpenAIChat($this->config))->generateStreamOfText($prompt);

        /**
         * @throws RandomException
         */
        $streamToIterator = static function (StreamInterface $stream): \Generator {
            while (!$stream->eof()) {
                yield $stream->read(random_int(2, 8));
            }
        };

        $chunks = [];
        foreach ($streamToIterator($stream) as $chunk) {
            $chunks[] = $chunk;
        }

        return $chunks;
    }

    public function createTextFromImages(
        array $images = null,
        string $message = 'What is represented in these images?'
    ): ?string {
        // TODO: Implement createTextFromImages() method.
    }
}