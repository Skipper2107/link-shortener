<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 12:38 PM
 */

namespace LetyGroup\LetyLink\Repository;


use LetyGroup\LetyLink\Exception\LetyLinkException;

class LinkRepository
{
    /** @var array $iniLocation */
    protected $links;

    /**
     * LinkRepository constructor.
     * @param string $location
     */
    public function __construct(string $location)
    {
        $this->links = parse_ini_file($location);
    }

    /**
     * @param string $key
     * @return string
     * @throws LetyLinkException
     */
    public function fetchLinkByKey(string $key): string
    {
        if (!key_exists($key, $this->links)) {
            throw new LetyLinkException('LinkNotFound', 404);
        }
        return $this->links[$key];
    }
}