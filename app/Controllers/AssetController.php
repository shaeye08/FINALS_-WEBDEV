<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\CategoryModel;

class AssetController extends BaseController
{
    protected $assetModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // Core Optimization Plan Item: Implementing clean framework pagination
        $data = [
            'assets' => $this->assetModel->getAssetsWithCategory()->paginate(10),
            'pager'  => $this->assetModel->pager
        ];

        return view('assets/index', $data);
    }

    public function create()
    {
        $data['categories'] = $this->categoryModel->findAll();
        return view('assets/create', $data);
    }

    public function store()
    {
        $rules = [
            'asset_code'  => 'required|is_unique[assets.asset_code]',
            'name'        => 'required|min_length[3]',
            'category_id' => 'required|is_not_unique[categories.id]',
            'status'      => 'required|in_list[Available,In Use,Repair]',
            'asset_image' => 'is_image[asset_image]|max_size[asset_image,2048]|mime_in[asset_image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = null;
        $file = $this->request->getFile('asset_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            
            // Move file safely into public write directory
            $file->move(ROOTPATH . 'public/uploads/', $imageName);

            // Core Optimization Plan Item: Compress and resize file using the CI4 Image Library
            \Config\Services::image()
                ->withFile(ROOTPATH . 'public/uploads/' . $imageName)
                ->resize(300, 300, true, 'height')
                ->save(ROOTPATH . 'public/uploads/' . $imageName, 80);
        }

        $assetCode  = $this->request->getPost('asset_code');
        $assetName  = $this->request->getPost('name');
        $categoryId = $this->request->getPost('category_id');
        $status     = $this->request->getPost('status');

        $this->assetModel->save([
            'asset_code'  => $assetCode,
            'name'        => $assetName,
            'category_id' => $categoryId,
            'status'      => $status,
            'image'       => $imageName
        ]);

        // ====================================================================
        // 🛡️ ADVANCED FEATURE: AUDIT LOG CREATION HOOK
        // ====================================================================
        $this->logSecurityEvent('CREATE', "Asset: " . $assetCode, [
            'name'        => $assetName,
            'category_id' => $categoryId,
            'status'      => $status,
            'image'       => $imageName
        ]);

        // Clear dashboard metrics cache frame instantly so analytics update cleanly
        \Config\Services::cache()->delete('dashboard_metrics_summary');

        return redirect()->to('/assets')->with('success', 'Asset item integrated successfully.');
    }

    public function edit($id)
    {
        $data['asset'] = $this->assetModel->find($id);
        if (!$data['asset']) {
            return redirect()->to('/assets')->with('error', 'Asset not located in system ledger.');
        }

        $data['categories'] = $this->categoryModel->findAll();
        return view('assets/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'asset_code'  => "required|is_unique[assets.asset_code,id,{$id}]",
            'name'        => 'required|min_length[3]',
            'category_id' => 'required|is_not_unique[categories.id]',
            'status'      => 'required|in_list[Available,In Use,Repair]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $statusValue = $this->request->getPost('status');
        $assetName   = $this->request->getPost('name');
        $assetCode   = $this->request->getPost('asset_code');

        $this->assetModel->update($id, [
            'asset_code'  => $assetCode,
            'name'        => $assetName,
            'category_id' => $this->request->getPost('category_id'),
            'status'      => $statusValue
        ]);

        // ====================================================================
        // 📨 FEATURE TIER 3: REPAIR TRIGGER AUTOMATION EMAIL ALERT
        // ====================================================================
        if ($statusValue === 'Repair') {
            $adminEmail = 'admin@assetflow.com'; 
            $subject    = "⚠️ ALERT: Asset Node [{$assetCode}] Requiring Immediate Maintenance";
            
            $messageBody = "
                <h2>AssetFlow Core Infrastructure Alert</h2>
                <p>An asset item profile has broken standard parameter thresholds and requires inspection.</p>
                <hr style='border:0;border-top:1px solid #eee;'/>
                <ul>
                    <li><strong>Device Identity:</strong> {$assetName}</li>
                    <li><strong>Asset Serial Code:</strong> {$assetCode}</li>
                    <li><strong>Reported State:</strong> <span style='color: #dc3545; font-weight: bold;'>SYSTEM DOWN / REPAIR</span></li>
                    <li><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</li>
                </ul>
                <p><a href='" . base_url('/assets/edit/' . $id) . "' style='display:inline-block;padding:10px 15px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:4px;'>Open Operational Ticket Node</a></p>
            ";

            $this->sendSystemAlert($adminEmail, $subject, $messageBody);
        }

        // ====================================================================
        // 🛡️ ADVANCED FEATURE: AUDIT LOG MODIFICATION HOOK
        // ====================================================================
        $this->logSecurityEvent('UPDATE', "Asset: " . $assetCode, [
            'modified_to_name'   => $assetName,
            'modified_to_status' => $statusValue
        ]);

        // Clear dashboard metrics cache frame on structural changes
        \Config\Services::cache()->delete('dashboard_metrics_summary');

        return redirect()->to('/assets')->with('success', 'Asset metrics modified cleanly.');
    }

    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        $assetCode = $asset['asset_code'] ?? 'Unknown ID: ' . $id;

        $this->assetModel->delete($id);
        
        // ====================================================================
        // 🛡️ ADVANCED FEATURE: AUDIT LOG PURGE DELETION HOOK
        // ====================================================================
        $this->logSecurityEvent('DELETE', "Asset: " . $assetCode, [
            'purged_record_meta' => $asset
        ]);

        // Clear dashboard metrics cache frame on structural deletions
        \Config\Services::cache()->delete('dashboard_metrics_summary');

        return redirect()->to('/assets')->with('success', 'Asset purging cycle completed.');
    }

    /**
     * ====================================================================
     * 🏷️ ADVANCED FEATURE: PUBLIC QR SCAN LANDING MODULE
     * ====================================================================
     * Handles incoming hardware asset scans from third-party mobile web cameras.
     */
    public function viewTag($assetCode)
    {
        $asset = $this->assetModel->where('asset_code', $assetCode)->first();
        
        if (!$asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Asset signature tag invalid or purged from ledger system.");
        }

        // Gather database category tags mapping cleanly
        $categoryModel = new \App\Models\CategoryModel();
        $asset['category_name'] = $categoryModel->find($asset['category_id'])['category_name'] ?? 'Uncategorized';

        return view('assets/public_tag_card', ['asset' => $asset]);
    }

    /**
     * ====================================================================
     * 📷 ADVANCED FEATURE: BARCODE SCANNER INTERFACE
     * ====================================================================
     * Renders the HTML5 browser video streaming scanner node page layout.
     */
    public function scan()
    {
        return view('assets/scan');
    }

    /**
     * ====================================================================
     * 🔍 ADVANCED FEATURE: BARCODE ROUTING LOOKUP ENGINE
     * ====================================================================
     * Processes live camera scanner string decodes and returns asset node maps.
     */
    public function lookupBarcode($barcode)
    {
        $asset = $this->assetModel->where('asset_code', $barcode)->first();

        if (!$asset) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hardware asset signature target not found in ledger database.'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'success'   => true,
            'asset_id'  => $asset['id'],
            'redirect'  => base_url('/assets/edit/' . $asset['id'])
        ]);
    }

    /**
     * ====================================================================
     * 📊 ADVANCED FEATURE: HIGH-SPEED EXCEL SPREADSHEET ENGINE
     * ====================================================================
     * Streams raw inventory metrics directly out to XML/Excel layout formats.
     */
    public function exportExcel()
    {
        // Log the report compilation action to the security ledger
        $this->logSecurityEvent('EXPORT', "Excel Inventory Document Generation");

        $assets = $this->assetModel->getAssetsWithCategory()->findAll();

        $filename = "AssetFlow_Ledger_Export_" . date('Ymd_His') . ".xls";

        // Set explicit header rules to force standard browser document file streams
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");

        // Output structural table formatting so Excel reads it natively with clean layout columns
        echo "<table border='1'>";
        echo "<tr>
                <th style='background-color:#0d6efd; color:#fff;'>Asset Code</th>
                <th style='background-color:#0d6efd; color:#fff;'>Device Name</th>
                <th style='background-color:#0d6efd; color:#fff;'>Category Type</th>
                <th style='background-color:#0d6efd; color:#fff;'>Current Status Badge</th>
              </tr>";

        foreach ($assets as $item) {
            echo "<tr>";
            echo "<td>" . esc($item['asset_code']) . "</td>";
            echo "<td>" . esc($item['name']) . "</td>";
            echo "<td>" . esc($item['category_name']) . "</td>";
            echo "<td>" . esc($item['status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }

    /**
     * ====================================================================
     * 📄 ADVANCED FEATURE: PDF AUDIT SUMMARY PRINT VIEW
     * ====================================================================
     * Renders a highly polished print layout optimized for PDF generation.
     */
    public function exportPdfView()
    {
        // Log the PDF audit action to the security ledger
        $this->logSecurityEvent('EXPORT', "PDF Audit Summary Document Generation");

        $data['assets'] = $this->assetModel->getAssetsWithCategory()->findAll();
        $data['generation_time'] = date('F d, Y H:i:s');

        return view('assets/export_pdf', $data);
    }
}