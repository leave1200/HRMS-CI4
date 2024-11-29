<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityHeaders implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
         // Set security headers
         $response->setHeader('X-Content-Type-Options', 'nosniff');
         $response->setHeader('X-Frame-Options', 'DENY');
         $response->setHeader('X-XSS-Protection', '1; mode=block');
         $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
         $response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self';");
         $response->setHeader('Referrer-Policy', 'no-referrer-when-downgrade');
         $response->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
         return $response;
    }
}
