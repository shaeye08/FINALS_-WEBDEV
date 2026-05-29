<?php

namespace App\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AssetAccessTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * Test 1: Ensure unauthenticated requests are redirected safely to the login screen.
     */
    public function testUnauthenticatedUsersAreRedirected()
    {
        $response = $this->get('/dashboard');
        
        // Assert that the middleware blocks access and forces a redirect
        $response->assertRedirectTo('/auth/login');
    }

    /**
     * Test 2: Ensure logged-in Staff members can access the main asset ledger view.
     */
    public function testStaffCanAccessAssetLedger()
    {
        // Mocking an active session payload array representing a standard Staff member
        $staffSession = [
            'id'         => 3,
            'name'       => 'Test Staff Operator',
            'email'      => 'staff@assetflow.com',
            'role_id'    => 3, // Role: Staff
            'isLoggedIn' => true
        ];

        $response = $this->withSession($staffSession)->get('/assets');
        
        // Assert that the page loads with an HTTP 200 OK status
        $response->assertOK();
        $response->assertSee('Hardware Inventory Ledger');
    }

    /**
     * Test 3: Ensure Staff users are blocked if they try to reach administrative creation endpoints.
     */
    public function testStaffAreBlockedFromCreatingAssets()
    {
        $staffSession = [
            'id'         => 3,
            'name'       => 'Test Staff Operator',
            'email'      => 'staff@assetflow.com',
            'role_id'    => 3, // Role: Staff
            'isLoggedIn' => true
        ];

        $response = $this->withSession($staffSession)->get('/assets/create');
        
        // Assert that the RoleFilter intercepts the request and redirects back to the dashboard with an error
        $response->assertRedirectTo('/dashboard');
        
        // Follow the redirect to verify the access warning is displayed on the dashboard layout screen
        $dashboardResponse = $this->withSession($staffSession)->get('/dashboard');
        $dashboardResponse->assertSee('Access Denied');
    }
}