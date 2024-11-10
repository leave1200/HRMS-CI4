<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        service('response')
            ->setHeader('Content-Security-Policy', "
                default-src 'self'; 
                script-src 'self' 'unsafe-inline' 'unsafe-eval' *.hrmo-lawis.com; 
                object-src 'none'; 
                base-uri 'self'; 
                style-src 'self' 'unsafe-inline'; 
                img-src 'self' data:; 
                font-src 'self'; 
                connect-src 'self'; 
                form-action 'self'; 
                frame-ancestors 'none'; 
                upgrade-insecure-requests;
            ");
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Set X-XSS-Protection header
        $response->setHeader('X-XSS-Protection', '1; mode=block');

        // Set X-Content-Type-Options header
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // Set Strict-Transport-Security header (HSTS)
        $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->setHeader('X-Frame-Options', 'DENY');
    }
}