<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 2:09 PM
 */

namespace LetyGroup\LetyLink\Factory;


use LetyGroup\LetyLink\Views;
use React\EventLoop\LoopInterface;
use React\Http\Response;
use React\Stream\ReadableResourceStream;

class ResponseFactory
{

    protected $loop;
    protected $views;

    public function __construct(LoopInterface $loop, Views $views)
    {
        $this->loop = $loop;
        $this->views = $views;
    }

    /**
     * @param string $template
     * @param array $options
     * @param int $code
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $template, array $options = [], int $code = 200): Response
    {
        return $this->createResponse($this->views->render($template, $options), $code);
    }

    /**
     * @param $content
     * @param int $code
     * @return Response
     */
    public function createResponse($content, int $code = 200): Response
    {
        return new Response($code, [
            'Content-Type' => 'text/html',
            'X-Powered-By' => 'lety-link',
        ], $content);
    }

    /**
     * @param string $location
     * @return Response
     */
    public function createRedirectResponse(string $location): Response
    {
        return new Response(302, [
            'Location' => $location,
            'X-Powered-By' => 'lety-link',
        ]);
    }

    public function createFileResponse(string $file): Response
    {
        $stream = new ReadableResourceStream(fopen($file, 'r'), $this->loop);
        return new Response(200, [
            'Content-Type' => mime_content_type($file),
            'X-Powered-By' => 'lety-link',
        ], $stream);
    }
}