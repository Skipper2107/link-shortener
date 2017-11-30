<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:10 PM
 */

namespace LetyGroup\LetyLink\Http;


use LetyGroup\LetyLink\Factory\ResponseFactory;
use LetyGroup\LetyLink\Views;
use Psr\Http\Message\ServerRequestInterface as Request;
use React\Http\Response;

class ExtensionController
{
    private const LS_HOST = 'https://letyshops.com';

    /** @var Views $views */
    protected $views;

    /**
     * ExtensionController constructor.
     * @param Views $views
     */
    public function __construct(Views $views)
    {
        $this->views = $views;
    }

    public function __invoke(Request $request): Response
    {
        $location = sprintf('%s%s', self::LS_HOST, $request->getUri()->getPath());
        return ResponseFactory::createRedirectResponse($location);
    }
}