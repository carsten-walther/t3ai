<?php

namespace CarstenWalther\T3aiCore\Resource\Resources;

use CarstenWalther\T3aiCore\Resource\AbstractResource;
use LLPhant\Chat\OllamaChat;
use LLPhant\Chat\Vision\ImageSource;
use LLPhant\Chat\Vision\VisionMessage;
use LLPhant\Exception\MissingParameterException;
use LLPhant\OllamaConfig;
use Psr\Http\Message\StreamInterface;
use Random\RandomException;

class Ollama extends AbstractResource
{
    public const TITLE = "Ollama";
    public array $arguments;
    public mixed $config;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;

        parent::__construct($this->arguments);

        $this->config = new OllamaConfig();
        $this->config->url = $this->arguments['configuration']['host'];
        $this->config->model = $this->arguments['configuration']['model'];
    }

    /**
     * @throws MissingParameterException
     */
    public function createTextWithPrompt(string $prompt): string
    {
        return (new OllamaChat($this->config))->generateText($prompt);
    }

    /**
     * @throws MissingParameterException
     * @throws RandomException
     */
    public function createTextStreamWithPrompt(string $prompt): array
    {
        $stream = (new OllamaChat($this->config))->generateStreamOfText($prompt);

        $streamToIterator = static function (StreamInterface $stream): \Generator {
            while (!$stream->eof()) {
                yield $stream->read(random_int(2, 8));
            }
        };

        $chunks = [];
        foreach ($streamToIterator($stream) as $chunk) {
            $chunks[] = $chunk;
        }

//        return array_map(static function($chunk) {
//            return $chunk;
//        }, $streamToIterator($stream));

        return $chunks;
    }

    /**
     * @throws MissingParameterException
     */
    public function createTextFromImages(array $images = null, string $prompt = 'What is represented in these images?'): ?string
    {
        if (is_array($images)) {
            return (new OllamaChat($this->config))->generateChat([
                VisionMessage::fromImages(array_map(static function($image) {
                    return new ImageSource($image);
                }, $images), $prompt)
            ]);
        }
    }
}