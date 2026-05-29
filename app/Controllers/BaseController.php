<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 * class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
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
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * ====================================================================
     * 📨 ADVANCED FEATURE: ENGINE EMAIL NOTIFICATION DISPATCHER
     * ====================================================================
     * Fires asynchronous system alerts to administrators and managers.
     * Securely protected to ensure context isolation within child controllers.
     */
    protected function sendSystemAlert(string $recipient, string $subject, string $htmlMessageBody): bool
    {
        $email = \Config\Services::email();

        // Initialize variables dynamically
        $email->setTo($recipient);
        $email->setSubject($subject);
        $email->setMessage($htmlMessageBody);

        if (!$email->send()) {
            // Log compilation delivery errors to system file without halting user execution runtime
            log_message('error', 'Notification Pipeline Failure: ' . $email->printDebugger(['headers']));
            return false;
        }
        
        return true;
    }

    /**
     * ====================================================================
     * 🛡️ ADVANCED FEATURE: IMMUTABLE AUDIT TRAIL LOGGING HOOK
     * ====================================================================
     * Appends a secure user action footprint record to the system ledger.
     * Securely protected to ensure tracking across all core modules.
     */
    protected function logSecurityEvent(string $action, string $targetItem, array $detailsArray = []): void
    {
        $auditModel = new \App\Models\AuditLogModel();
        
        // Grab values directly out of active framework session matrices
        $userId   = session()->get('user_id') ?? null;
        $username = session()->get('username') ?? 'Anonymous/System Process';
        
        $auditModel->save([
            'user_id'       => $userId,
            'operator_name' => $username,
            'action'        => strtoupper($action),
            'target_item'   => $targetItem,
            'details'       => json_encode($detailsArray),
            'ip_address'    => $this->request->getIPAddress(),
            'created_at'    => date('Y-m-d H:i:s')
        ]);
    }
}