<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 1:24 PM
 */

namespace LetyGroup\LetyLink;


class Views
{

    protected $twig;

    public function __construct(string $pathToViews, string $cache)
    {
        $loader = new \Twig_Loader_Filesystem($pathToViews);
        $this->twig = new \Twig_Environment($loader, [
            'cache' => $cache,
        ]);
    }

    /**
     * @param string $name
     * @param array $options
     * @return bool|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $name, array $options = [])
    {
        $name = sprintf('%s.html.twig', $name);
        return $this->twig->render($name, $options);
    }

}