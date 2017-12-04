<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 12/4/17
 * Time: 2:14 PM
 */

namespace LetyGroup\LetyLink\Http\Controller;


use LetyGroup\LetyLink\Config;
use LetyGroup\LetyLink\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;

class FileController
{
    protected $config;
    protected $factory;

    public function __construct(Config $config, ResponseFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }


    /**
     * @param ServerRequestInterface $request
     * @return \React\Http\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $filename = basename($request->getUri()->getPath());
        $file = $this->config->getPublicFile($filename);
        if (!file_exists($file)) {
            return $this->factory->render('404', [], 404);
        }
        return $this->factory->createFileResponse($file);
    }


}