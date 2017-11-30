<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:10 PM
 */

namespace LetyGroup\LetyLink\Http;


use LetyGroup\LetyLink\Config;
use LetyGroup\LetyLink\Factory\ResponseFactory;
use LetyGroup\LetyLink\Repository\LinkRepository;
use LetyGroup\LetyLink\Views;
use React\Http\Response;
use Symfony\Component\HttpFoundation\Request;

class ShortenerController
{
    /** @var LinkRepository $links */
    protected $links;
    /** @var Views $views */
    protected $views;


    public function __construct(Config $config, Views $views)
    {
        $this->views = $views;
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

        $key = substr($request->getRequestUri(), 1);
        $link = $this->links->fetchLinkByKey($key);
        return ResponseFactory::createSuccessResponse($this->views->render('redirect', [
            'link' => $link,
        ]));
    }
}