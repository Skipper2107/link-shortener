<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 12:25 PM
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

#Config
$conf = new \LetyGroup\LetyLink\Config(__DIR__);
$conf->configure();

#Views
$views = new \LetyGroup\LetyLink\Views($conf->getViewsStorage(), $conf->getViewCache());

#Loop setup
$loop = \React\EventLoop\Factory::create();
#Router
$router = new \LetyGroup\LetyLink\Http\Router($views, $conf, $loop);
$server = new \React\Http\Server($router);
$socket = new \React\Socket\Server($conf->getListenedUrl(), $loop);
$server->listen($socket);
$loop->run();