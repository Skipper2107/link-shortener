<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 12:29 PM
 */

namespace LetyGroup\LetyLink;


use Dotenv\Dotenv;

class Config
{
    private const LETY_LINK_HOST = 'LETY_LINK_HOST';
    private const LETY_LINK_PORT = 'LETY_LINK_PORT';
    private const LINKS_FILE_LOCATION = 'LINKS_FILE_LOCATION';

    /** @var string $dir */
    protected $dir;

    public function __construct(string $root)
    {
        $this->dir = $root;
    }

    public function configure()
    {
        $env = new Dotenv($this->dir);
        $env->load();
        $env->required([
            self::LETY_LINK_HOST,
            self::LETY_LINK_PORT,
            self::LINKS_FILE_LOCATION,
        ]);
    }

    /**
     * @return string
     */
    public function getIniLocation(): string
    {
        return sprintf('%s/%s', $this->dir, getenv(self::LINKS_FILE_LOCATION));
    }

    /**
     * @return string
     */
    public function getListenedUrl(): string
    {
        return sprintf('%s:%s', getenv(self::LETY_LINK_HOST), getenv(self::LETY_LINK_PORT));
    }

    public function getViewsStorage(): string
    {
        return $this->dir . DIRECTORY_SEPARATOR . 'assets/views';
    }

    public function getViewCache(): string
    {
        return $this->dir . DIRECTORY_SEPARATOR . 'cache';
    }
}