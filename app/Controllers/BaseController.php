<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        delete_cookie('csrf_cookie_name');
         // Set security headers
   // Set security headers
   $response->setHeader('X-Content-Type-Options', 'nosniff');
   $response->setHeader('X-Frame-Options', 'DENY');
   $response->setHeader('X-XSS-Protection', '1; mode=block');
   $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
   $response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self';");
   $response->setHeader('Referrer-Policy', 'no-referrer-when-downgrade');
   $response->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

   return $response;

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }
}
