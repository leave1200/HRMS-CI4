<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No action before request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Set X-XSS-Protection header
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        // Set X-Content-Type-Options header
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // Set Strict-Transport-Security header (HSTS)
        $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
    
    }
}