<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:10 PM
 */

namespace LetyGroup\LetyLink\Http\Controller;


use LetyGroup\LetyLink\Config;
use LetyGroup\LetyLink\Factory\ResponseFactory;
use LetyGroup\LetyLink\Repository\LinkRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use React\Http\Response;

class ShortenerController
{
    /** @var LinkRepository $links */
    protected $links;
    /** @var ResponseFactory $factory */
    protected $factory;


    public function __construct(Config $config, ResponseFactory $responseFactory)
    {
        $this->factory = $responseFactory;
        $this->links = new LinkRepository($config->getIniLocation());
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \LetyGroup\LetyLink\Exception\LetyLinkException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request): Response
    {
        $key = substr($request->getUri()->getPath(), 1);
        $link = $this->links->fetchLinkByKey($key);
        return $this->factory->render('redirect', [
            'link' => $link,
        ]);
    }
}