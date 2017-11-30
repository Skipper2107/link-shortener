<?php
/**
 * Created by PhpStorm.
 * User: skipper
 * Date: 11/30/17
 * Time: 2:09 PM
 */

namespace LetyGroup\LetyLink\Factory;


use React\Http\Response;

class ResponseFactory
{
    /**
     * @param $content
     * @return Response
     */
    public static function createSuccessResponse($content): Response
    {
        return new Response(200, [
            'Content Type' => 'text/html',
        ], $content);
    }

    /**
     * @param string $location
     * @return Response
     */
    public static function createRedirectResponse(string $location): Response
    {
        return new Response(302, [
            'Location' => $location,
        ]);
    }
}