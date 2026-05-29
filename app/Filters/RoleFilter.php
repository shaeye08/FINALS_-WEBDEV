<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Intercepts execution to match active session IDs against requested route tiers.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Enforce basic authentication layer check first
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Authentication session required.');
        }

        // If no strict roles are designated on the route, let it pass
        if (empty($arguments)) {
            return;
        }

        $userRoleId = (int) session()->get('role_id');

        // Look up mapping values: 1 = SuperAdmin, 2 = Manager, 3 = Staff
        $roleMap = [
            'SuperAdmin' => 1,
            'Manager'    => 2,
            'Staff'      => 3
        ];

        // Convert argument strings passed from routes config into integers
        $allowedRoleIds = [];
        foreach ($arguments as $roleName) {
            if (isset($roleMap[$roleName])) {
                $allowedRoleIds[] = $roleMap[$roleName];
            }
        }

        // Drop execution and reject route access if current user's role is not allowed
        if (!in_array($userRoleId, $allowedRoleIds, true)) {
            // Throw an HTTP 403 Forbidden exception or redirect smoothly with a warning flag
            return redirect()->to('/dashboard')->with('error', 'Access Denied: You do not possess structural clearance for that asset section.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}