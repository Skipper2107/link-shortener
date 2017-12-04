<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:10 PM
 */

namespace LetyGroup\LetyLink\Http\Controller;


use LetyGroup\LetyLink\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use React\Http\Response;

class ExtensionController
{
    private const LS_HOST = 'https://letyshops.com';

    /** @var ResponseFactory $factory */
    protected $factory;

    /**
     * ExtensionController constructor.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->factory = $responseFactory;
    }

    public function __invoke(Request $request): Response
    {
        $location = sprintf(
            '%s%s?%s',
            self::LS_HOST,
            $request->getUri()->getPath(),
            $request->getUri()->getQuery()
        );
        return $this->factory->createRedirectResponse($location);
    }
}