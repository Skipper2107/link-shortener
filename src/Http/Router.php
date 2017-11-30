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
use LetyGroup\LetyLink\Views;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private const EXTENSION = 'extension';
    private const SHORT = 'short';

    /** @var HttpFoundationFactory $requestFactory */
    protected $requestFactory;
    /** @var array $controllers */
    protected $controllers;
    /** @var $views Config */
    protected $views;

    /**
     * Router constructor.
     * @param Views $views
     * @param Config $config
     */
    public function __construct(Views $views, Config $config)
    {
        $this->views = $views;
        $this->requestFactory = new HttpFoundationFactory();
        $this->controllers = [
            self::EXTENSION => new ExtensionController($views),
            self::SHORT => new ShortenerController($config, $views),
        ];
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request): Response
    {
        try {
            $request = $this->requestFactory->createRequest($request);
            $key = $this->getRouteKey($request);
            $response = $this->controllers[$key]();
        } catch (\Exception $exception) {
            try {
                $content = $this->views->render('404');
            } catch (\Exception $e) {
                $content = $e->getMessage();
            }
            $response = ResponseFactory::createSuccessResponse($content);
        } finally {
            return $response;
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getRouteKey(Request $request): string
    {
        $uri = $request->getRequestUri();
        return strpos($uri, '/view/') === 0 ? self::EXTENSION : self::SHORT;
    }
}