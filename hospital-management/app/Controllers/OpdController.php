<?php

namespace App\Controllers;

use App\Models\OpdVisitModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\OpdMedicineModel;
use App\Models\UserModel;

class OpdController extends BaseController
{
    protected $opdModel;
    protected $patientModel;
    protected $doctorModel;
    protected $medicineModel;

    public function __construct()
    {
        $this->opdModel = new OpdVisitModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->medicineModel = new OpdMedicineModel();
    }

    /**
     * Display OPD Dashboard / Queue
     */
    public function index()
    {
        $data = [
            'title' => 'OPD Management',
            'page_title' => 'Outpatient Department',
        ];

        // Get today's queue
        $data['today_visits'] = $this->opdModel->getTodayQueue();
        
        // Statistics
        $today = date('Y-m-d');
        $data['stats'] = [
            'total' => $this->opdModel->where('DATE(visit_date)', $today)->countAllResults(),
            'completed' => $this->opdModel->where('DATE(visit_date)', $today)->where('status', 'completed')->countAllResults(),
            'waiting' => $this->opdModel->where('DATE(visit_date)', $today)->where('status', 'waiting')->countAllResults(),
            'consulting' => $this->opdModel->where('DATE(visit_date)', $today)->where('status', 'consulting')->countAllResults(),
        ];

        return view('opd/index', $data);
    }

    /**
     * Show form to create new OPD visit
     */
    public function create()
    {
        $data = [
            'title' => 'New OPD Visit',
            'page_title' => 'Register New OPD Visit',
            'patients' => $this->patientModel->orderBy('name', 'ASC')->findAll(),
            'doctors' => $this->doctorModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll(),
            'opd_number' => $this->opdModel->generateOpdNumber(0),
        ];

        return view('opd/create', $data);
    }

    /**
     * Store new OPD visit
     */
    public function store()
    {
        $rules = [
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'visit_date' => 'required|valid_date',
            'complaint_main' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost([
            'patient_id',
            'doctor_id',
            'visit_date',
            'complaint_main',
            'complaint_history',
            'vital_bp_sys',
            'vital_bp_dia',
            'vital_pulse',
            'vital_temp',
            'vital_weight',
            'vital_height',
            'vital_spo2',
            'examination_general',
            'examination_systemic',
            'diagnosis_provisional',
            'diagnosis_icd_code',
            'plan_medication',
            'plan_investigation',
            'plan_advice',
            'follow_up_date',
        ]);

        // Generate OPD Number
        $data['opd_number'] = $this->opdModel->generateOpdNumber($data['patient_id']);
        $data['status'] = 'triage'; // Start with triage

        // Handle medications as JSON if it's an array
        if (is_array($data['plan_medication'])) {
            $data['plan_medication'] = json_encode($data['plan_medication']);
        }

        if ($this->opdModel->insert($data)) {
            return redirect()->to('/opd/' . $this->opdModel->getInsertID())->with('success', 'OPD visit registered successfully. OPD Number: ' . $data['opd_number']);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to register OPD visit.');
    }

    /**
     * Display single OPD visit details
     */
    public function show($id)
    {
        $data = [
            'title' => 'OPD Visit Details',
            'page_title' => 'Visit Details',
        ];

        $data['visit'] = $this->opdModel->find($id);
        
        if (!$data['visit']) {
            return redirect()->to('/opd')->with('error', 'OPD visit not found.');
        }

        // Load related data
        $data['patient'] = $this->patientModel->find($data['visit']['patient_id']);
        $data['doctor'] = $this->doctorModel->find($data['visit']['doctor_id']);
        
        // Calculate BMI if vitals exist
        if ($data['visit']['vital_weight'] && $data['visit']['vital_height']) {
            $data['bmi'] = $this->opdModel->calculateBMI($data['visit']['vital_weight'], $data['visit']['vital_height']);
        }

        // Get medicines from the new opd_medicines table
        $data['medications'] = $this->medicineModel->getMedicinesByOpdId($id);
        
        // Fallback to old JSON format if no medicines found
        if (empty($data['medications']) && !empty($data['visit']['plan_medication'])) {
            $meds = json_decode($data['visit']['plan_medication'], true);
            $data['medications'] = is_array($meds) ? $meds : [$data['visit']['plan_medication']];
        }

        // Get patient history
        $data['past_visits'] = $this->opdModel->getVisitsByPatient($data['patient']['id'], 5);

        return view('opd/show', $data);
    }

    /**
     * Show form to edit OPD visit (Update consultation notes)
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Edit OPD Visit',
            'page_title' => 'Update Consultation',
        ];

        $data['visit'] = $this->opdModel->find($id);
        
        if (!$data['visit']) {
            return redirect()->to('/opd')->with('error', 'OPD visit not found.');
        }

        $data['patient'] = $this->patientModel->find($data['visit']['patient_id']);
        $data['doctor'] = $this->doctorModel->find($data['visit']['doctor_id']);
        
        // Get medicines from the new opd_medicines table
        $data['medications'] = $this->medicineModel->getMedicinesByOpdId($id);
        
        // Fallback to old JSON format if no medicines found
        if (empty($data['medications']) && !empty($data['visit']['plan_medication'])) {
            $meds = json_decode($data['visit']['plan_medication'], true);
            $data['medications'] = is_array($meds) ? $meds : [$data['visit']['plan_medication']];
        }

        return view('opd/edit', $data);
    }

    /**
     * Update OPD visit
     */
    public function update($id)
    {
        $data = $this->request->getPost([
            'complaint_history',
            'vital_bp_sys',
            'vital_bp_dia',
            'vital_pulse',
            'vital_temp',
            'vital_weight',
            'vital_height',
            'vital_spo2',
            'examination_general',
            'examination_systemic',
            'diagnosis_provisional',
            'diagnosis_icd_code',
            'plan_medication',
            'plan_investigation',
            'plan_advice',
            'status',
            'follow_up_date',
        ]);

        // Handle medications array to JSON
        if (isset($data['plan_medication']) && is_array($data['plan_medication'])) {
            // Filter empty entries
            $cleanMeds = array_filter($data['plan_medication'], function($med) {
                return !empty($med['name']);
            });
            $data['plan_medication'] = json_encode(array_values($cleanMeds));
        }

        if ($this->opdModel->update($id, $data)) {
            // Save medicines separately
            $medicineNames = $this->request->getPost('medicine_name');
            $medicineStrengths = $this->request->getPost('medicine_strength');
            $medicineDosages = $this->request->getPost('medicine_dosage');
            $medicineFrequencies = $this->request->getPost('medicine_frequency');
            $medicineDurations = $this->request->getPost('medicine_duration');
            $medicineInstructions = $this->request->getPost('medicine_instructions');

            $medicines = [];
            if (is_array($medicineNames)) {
                foreach ($medicineNames as $index => $name) {
                    if (!empty($name)) {
                        $medicines[] = [
                            'medicine_name' => $name,
                            'strength'      => $medicineStrengths[$index] ?? null,
                            'dosage'        => $medicineDosages[$index] ?? '',
                            'frequency'     => $medicineFrequencies[$index] ?? '',
                            'duration'      => $medicineDurations[$index] ?? '',
                            'instructions'  => $medicineInstructions[$index] ?? null,
                        ];
                    }
                }
            }

            $this->medicineModel->saveMedicines($id, $medicines);

            return redirect()->to('/opd/' . $id)->with('success', 'OPD visit updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update OPD visit.');
    }

    /**
     * Generate and display prescription
     */
    public function prescription($id)
    {
        $data['opd'] = $this->opdModel->find($id);
        if (!$data['opd']) {
            session()->setFlashdata('error', 'OPD visit not found');
            return redirect()->to('/opd');
        }

        $data['patient'] = $this->patientModel->find($data['opd']['patient_id']);
        $data['doctor'] = $this->doctorModel->find($data['opd']['doctor_id']);
        $data['medicines'] = $this->medicineModel->getMedicinesByOpdId($id);

        return view('opd/prescription', $data);
    }

    /**
     * Quick status update (for queue management)
     */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $allowed = ['waiting', 'triage', 'consulting', 'completed', 'cancelled'];
        
        if (!in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        if ($this->opdModel->update($id, ['status' => $status])) {
            return redirect()->back()->with('success', 'Status updated to ' . ucfirst($status));
        }

        return redirect()->back()->with('error', 'Failed to update status.');
    }

    /**
     * Search patients for AJAX autocomplete
     */
    public function searchPatients()
    {
        $term = $this->request->getGet('term');
        
        if (strlen($term) < 2) {
            return $this->response->setJSON([]);
        }

        $patients = $this->patientModel
            ->groupStart()
                ->like('name', $term)
                ->orLike('phone', $term)
                ->orLike('opd_number', $term)
            ->groupEnd()
            ->limit(10)
            ->findAll();

        $results = [];
        foreach ($patients as $p) {
            $results[] = [
                'id' => $p['id'],
                'label' => $p['name'] . ' (' . ($p['gender'] ?? 'N/A') . ', ' . ($p['age'] ?? 'N/A') . 'y) - ' . ($p['phone'] ?? ''),
                'value' => $p['name']
            ];
        }

        return $this->response->setJSON($results);
    }
}
