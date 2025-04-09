<?php

namespace CarstenWalther\T3aiRte\Controller;

use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use CarstenWalther\T3aiCore\Service\ResourceService;
use JsonException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AjaxController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly ResourceService $resourceService,
    ) {}

    /**
     * @throws NotRegisteredException
     * @throws JsonException
     */
    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $arguments = $request->getParsedBody();

        $arguments['prompt']
            ?? throw new \InvalidArgumentException(
                'Please provide a prompt',
                1580585107,
            );

        $arguments['identifier']
            ?? throw new \InvalidArgumentException(
                'Please provide a identifier',
                1580585107,
            );

        $result = $this->resourceService->createTextStream($arguments);

        $response = $this->responseFactory
            ->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');

        $response->getBody()->write(json_encode([
            'result' => $result,
        ], JSON_THROW_ON_ERROR));

        return $response;
    }
}