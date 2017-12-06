<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:05 PM
 */

namespace LetyGroup\LetyLink\Http;


use LetyGroup\LetyLink\Config;
use LetyGroup\LetyLink\Factory\ResponseFactory;
use LetyGroup\LetyLink\Http\Controller\ExtensionController;
use LetyGroup\LetyLink\Http\Controller\FileController;
use LetyGroup\LetyLink\Http\Controller\ShortenerController;
use LetyGroup\LetyLink\Views;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Router
{
    private const EXTENSION = 'extension';
    private const SHORT = 'short';
    private const FILES = 'file';

    /** @var array $controllers */
    protected $controllers;
    /** @var $views Config */
    protected $views;
    /** @var ResponseFactory $responseFactory */
    protected $responseFactory;

    /**
     * Router constructor.
     * @param Views $views
     * @param Config $config
     * @param LoopInterface $loop
     */
    public function __construct(Views $views, Config $config, LoopInterface $loop)
    {
        $this->responseFactory = new ResponseFactory($loop, $views);
        $shortController = new ShortenerController($config, $this->responseFactory);
        $this->controllers = [
            self::EXTENSION => new ExtensionController($this->responseFactory),
            self::SHORT => $shortController,
            self::FILES => new FileController($config, $this->responseFactory),
        ];
        $loop->addPeriodicTimer($config->getIniFileRefreshRateInSeconds(), function () use ($shortController) {
            $shortController->refresh();
        });
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request): Response
    {
        try {
            $key = $this->getRouteKey($request);
            $response = $this->controllers[$key]($request);
        } catch (\Exception $exception) {
            try {
                return $this->responseFactory->render('404', [], 404);
            } catch (\Exception $e) {
                return $this->responseFactory->createResponse($e->getMessage(), 500);
            }
        }
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private function getRouteKey(ServerRequestInterface $request): string
    {
        $uri = $request->getUri()->getPath();
        return strpos($uri, '/view/') === 0 ? self::EXTENSION : (strpos($uri, '.') === false ? self::SHORT : self::FILES);
    }
}