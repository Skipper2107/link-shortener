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
    protected $loader;

    public function __construct(string $pathToViews, string $cache)
    {
        $this->loader = new \Twig_Loader_Filesystem($pathToViews);
        $this->twig = new \Twig_Environment($this->loader, [
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
        if (!$this->loader->exists($name)) {
            return sprintf('View %s was not found', $name);
        }
        return $this->twig->render($name, $options);
    }

}