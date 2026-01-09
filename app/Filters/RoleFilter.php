<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Check if user has the required role to access the route
     *
     * @param RequestInterface $request
     * @param array|null       $arguments Array of allowed roles (e.g., ['admin'] or ['admin', 'petugas'])
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // First check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        // Get user's role from session
        $userRole = session()->get('role');

        // If no specific roles are required, allow access
        if (empty($arguments)) {
            return;
        }

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $arguments)) {
            // Set flash message for unauthorized access
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            
            // Redirect to admin dashboard
            return redirect()->to('/admin/dashboard');
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
