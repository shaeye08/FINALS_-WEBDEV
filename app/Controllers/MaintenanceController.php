<?php

namespace App\Controllers;

use App\Models\MaintenanceLogModel;
use App\Models\AssetModel;

class MaintenanceController extends BaseController
{
    protected $maintenanceModel;
    protected $assetModel;

    public function __construct()
    {
        $this->maintenanceModel = new MaintenanceLogModel();
        $this->assetModel = new AssetModel();
    }

    public function index()
    {
        $data = [
            'logs'  => $this->maintenanceModel->getLogsWithAssets()->paginate(10),
            'pager' => $this->maintenanceModel->pager
        ];
        return view('maintenance/index', $data);
    }

    public function create()
    {
        // Get all assets so staff can select which one needs repair
        $data['assets'] = $this->assetModel->findAll();
        return view('maintenance/create', $data);
    }

    public function store()
    {
        $rules = [
            'asset_id' => 'required|is_not_unique[assets.id]',
            'issue'    => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->maintenanceModel->save([
            'asset_id' => $this->request->getPost('asset_id'),
            'issue'    => $this->request->getPost('issue'),
            'status'   => 'Pending'
        ]);

        // Automatically flip asset status to 'Repair'
        $this->assetModel->update($this->request->getPost('asset_id'), ['status' => 'Repair']);

        return redirect()->to('/maintenance')->with('success', 'Maintenance log created successfully.');
    }

    public function edit($id)
    {
        $data['log'] = $this->maintenanceModel->getLogsWithAssets()->find($id);
        if (!$data['log']) {
            return redirect()->to('/maintenance')->with('error', 'Log record not found.');
        }
        return view('maintenance/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'status' => 'required|in_list[Pending,Ongoing,Completed]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $log = $this->maintenanceModel->find($id);

        $this->maintenanceModel->update($id, [
            'repair_notes' => $this->request->getPost('repair_notes'),
            'status'       => $status
        ]);

        // If repair is completed, automatically free up the asset back to 'Available'
        if ($status === 'Completed') {
            $this->assetModel->update($log['asset_id'], ['status' => 'Available']);
        }

        return redirect()->to('/maintenance')->with('success', 'Maintenance status revised.');
    }
}