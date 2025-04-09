<?php

namespace CarstenWalther\T3aiCore\Middleware;

use CarstenWalther\T3aiCore\Exceptions\NotRegisteredException;
use CarstenWalther\T3aiCore\Service\ResourceService;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;

readonly class ApiMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected ResourceService $resourceService,
    ) {}

    /**
     * @throws JsonException
     * @throws NotRegisteredException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if ($request->getRequestTarget() === '/t3ai-core/api') {
            $arguments = $request->getParsedBody()['tx_t3aicore_chat'];

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

            $response = $this->responseFactory
                ->createResponse()
                ->withHeader('Content-Type', 'application/json; charset=utf-8');

            $response->getBody()->write(json_encode([
                'result' => $this->resourceService->createTextStream($arguments)
            ], JSON_THROW_ON_ERROR|JSON_INVALID_UTF8_IGNORE));

            return $response;
        }

        return $handler->handle($request);
    }
}