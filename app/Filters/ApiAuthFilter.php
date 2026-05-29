<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $request->getHeaderLine('X-API-Token');
        
        if (empty($token)) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'error' => 'API Access Token is missing from headers. Key expected: X-API-Token']);
        }

        $db = \Config\Database::connect();
        $tokenFound = $db->table('api_tokens')->where('token', $token)->get()->getRow();

        if (!$tokenFound) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'error' => 'Invalid or expired API token payload access unauthorized.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}