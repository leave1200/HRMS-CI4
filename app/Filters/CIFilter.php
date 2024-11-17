<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;

class CIFilter implements FilterInterface
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
        // Check for guest access
        if ($arguments[0] == 'guest') {
            if (CIAuth::check()) {
                return redirect()->route('admin.home');
            }
        }

        // Check for authentication
        if ($arguments[0] == 'auth') {
            if (!CIAuth::check()) {
                return redirect()->route('admin.login.form')->with('fail', 'You must be logged in first');
            }
        }

        // Prevent access for EMPLOYEE and STAFF roles
        if ($arguments[0] == 'ADMIN') {
            $userStatus = session()->get('userStatus'); // Assuming you store user status in session

            // Allow access only if the user is an ADMIN
            if ($userStatus !== 'ADMIN') {
                return redirect()->route('admin.home')->with('fail', 'Access denied. Admins only.');
            }
        }
        if (CIAuth::check()) {
            $userStatus = session()->get('userStatus');
            $termsAccepted = session()->get('user_terms_accepted'); // Assuming you store the terms status in session

            // If the user is logged in but has not accepted the terms
            if ($userStatus && $termsAccepted !== 1) {
                // Log the user out
                session()->remove('user_id');
                session()->remove('username');
                session()->remove('userStatus');
                session()->remove('isLoggedIn');
                session()->remove('user_terms_accepted'); // Remove the terms acceptance status as well

                // Redirect to the terms page
                return redirect()->route('admin.terms')->with('fail', 'You must accept the terms and conditions to proceed.');
            }
        }
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
        //
    }
}
