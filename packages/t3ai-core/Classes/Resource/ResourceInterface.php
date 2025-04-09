<?php

namespace CarstenWalther\T3aiCore\Resource;

interface ResourceInterface
{
    public const TITLE = __CLASS__;

    public function createTextWithPrompt(string $prompt): string;

    public function createTextStreamWithPrompt(string $prompt): array;

    public function createTextFromImages(array $images = null, string $prompt = 'What is represented in these images?'): ?string;
}
