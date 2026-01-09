<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in before accessing protected routes
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in via session
        if (!session()->get('isLoggedIn')) {
            // Store intended URL for redirect after login
            session()->set('redirect_url', current_url());
            
            // Set flash message
            session()->setFlashdata('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
            
            // Redirect to login page
            return redirect()->to('/login');
        }
    }

    /**
     * After filter - not used
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the request
    }
}
