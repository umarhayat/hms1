<?php

namespace App\Controllers;

use App\Models\PatientModel;
use CodeIgniter\Controller;

class Patients extends Controller
{
    protected $patientModel;
    protected $session;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    /**
     * Display list of all patients
     */
    public function index()
    {
        $data['title'] = 'Patient Management';
        $data['page_title'] = 'All Patients';
        
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $perPage = 10;
        
        $query = $this->patientModel;
        
        if ($search) {
            $query = $query->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('patient_id', $search)
                ->groupEnd();
        }
        
        if ($status && in_array($status, ['active', 'inactive'])) {
            $query = $query->where('status', $status);
        }
        
        $data['patients'] = $query->orderBy('created_at', 'DESC')->paginate($perPage);
        $data['pager'] = $this->patientModel->pager;
        
        // Statistics
        $data['total_patients'] = $this->patientModel->countAllResults(false);
        $data['active_patients'] = $this->patientModel->where('status', 'active')->countAllResults(false);
        $data['inactive_patients'] = $this->patientModel->where('status', 'inactive')->countAllResults(false);
        
        return view('patients/index', $data);
    }

    /**
     * Show form to create a new patient
     */
    public function create()
    {
        $data['title'] = 'Add New Patient';
        $data['page_title'] = 'Register Patient';
        $data['patient'] = null;
        
        return view('patients/form', $data);
    }

    /**
     * Store a new patient in database
     */
    public function store()
    {
        $validationRules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'date_of_birth' => 'required|valid_date',
            'gender' => 'required|in_list[male,female,other]',
            'blood_group' => 'permit_empty|in_list[A+,A-,B+,B-,AB+,AB-,O+,O-]',
            'email' => 'permit_empty|valid_email|is_unique[patients.email]',
            'phone' => 'required|max_length[20]',
            'address' => 'permit_empty|max_length[255]',
            'city' => 'permit_empty|max_length[100]',
            'state' => 'permit_empty|max_length[100]',
            'zip_code' => 'permit_empty|max_length[20]',
            'emergency_contact_name' => 'permit_empty|max_length[100]',
            'emergency_contact_phone' => 'permit_empty|max_length[20]',
            'medical_history' => 'permit_empty',
            'allergies' => 'permit_empty',
            'current_medications' => 'permit_empty',
            'insurance_provider' => 'permit_empty|max_length[100]',
            'insurance_policy_number' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = session()->get('user');
        
        $patientData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'blood_group' => $this->request->getPost('blood_group'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'state' => $this->request->getPost('state'),
            'zip_code' => $this->request->getPost('zip_code'),
            'country' => $this->request->getPost('country') ?? 'USA',
            'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'medical_history' => $this->request->getPost('medical_history'),
            'allergies' => $this->request->getPost('allergies'),
            'current_medications' => $this->request->getPost('current_medications'),
            'insurance_provider' => $this->request->getPost('insurance_provider'),
            'insurance_policy_number' => $this->request->getPost('insurance_policy_number'),
            'status' => 'active',
            'created_by' => $userData['id'] ?? null,
        ];

        try {
            $this->patientModel->insert($patientData);
            return redirect()->to('/patients')->with('success', 'Patient registered successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to register patient: ' . $e->getMessage());
        }
    }

    /**
     * Display a single patient's details
     */
    public function show($id)
    {
        $data['patient'] = $this->patientModel->find($id);
        
        if (!$data['patient']) {
            return redirect()->to('/patients')->with('error', 'Patient not found');
        }
        
        $data['title'] = 'Patient Details';
        $data['page_title'] = $data['patient']['first_name'] . ' ' . $data['patient']['last_name'];
        
        // Calculate age
        $birthDate = new \DateTime($data['patient']['date_of_birth']);
        $today = new \DateTime('today');
        $data['age'] = $birthDate->diff($today)->y;
        
        return view('patients/view', $data);
    }

    /**
     * Show form to edit an existing patient
     */
    public function edit($id)
    {
        $data['patient'] = $this->patientModel->find($id);
        
        if (!$data['patient']) {
            return redirect()->to('/patients')->with('error', 'Patient not found');
        }
        
        $data['title'] = 'Edit Patient';
        $data['page_title'] = 'Update Patient Information';
        
        return view('patients/form', $data);
    }

    /**
     * Update an existing patient in database
     */
    public function update($id)
    {
        $patient = $this->patientModel->find($id);
        
        if (!$patient) {
            return redirect()->to('/patients')->with('error', 'Patient not found');
        }

        $validationRules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'date_of_birth' => 'required|valid_date',
            'gender' => 'required|in_list[male,female,other]',
            'blood_group' => 'permit_empty|in_list[A+,A-,B+,B-,AB+,AB-,O+,O-]',
            'email' => 'permit_empty|valid_email|is_unique[patients.email,id,' . $id . ']',
            'phone' => 'required|max_length[20]',
            'address' => 'permit_empty|max_length[255]',
            'city' => 'permit_empty|max_length[100]',
            'state' => 'permit_empty|max_length[100]',
            'zip_code' => 'permit_empty|max_length[20]',
            'emergency_contact_name' => 'permit_empty|max_length[100]',
            'emergency_contact_phone' => 'permit_empty|max_length[20]',
            'medical_history' => 'permit_empty',
            'allergies' => 'permit_empty',
            'current_medications' => 'permit_empty',
            'insurance_provider' => 'permit_empty|max_length[100]',
            'insurance_policy_number' => 'permit_empty|max_length[50]',
            'status' => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $patientData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'blood_group' => $this->request->getPost('blood_group'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'state' => $this->request->getPost('state'),
            'zip_code' => $this->request->getPost('zip_code'),
            'country' => $this->request->getPost('country'),
            'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'medical_history' => $this->request->getPost('medical_history'),
            'allergies' => $this->request->getPost('allergies'),
            'current_medications' => $this->request->getPost('current_medications'),
            'insurance_provider' => $this->request->getPost('insurance_provider'),
            'insurance_policy_number' => $this->request->getPost('insurance_policy_number'),
            'status' => $this->request->getPost('status'),
        ];

        try {
            $this->patientModel->update($id, $patientData);
            return redirect()->to('/patients/' . $id)->with('success', 'Patient updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update patient: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete a patient
     */
    public function delete($id)
    {
        $patient = $this->patientModel->find($id);
        
        if (!$patient) {
            return redirect()->to('/patients')->with('error', 'Patient not found');
        }

        try {
            $this->patientModel->delete($id);
            return redirect()->to('/patients')->with('success', 'Patient deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->to('/patients')->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }

    /**
     * Search patients via AJAX
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword || strlen($keyword) < 2) {
            return $this->response->setJSON(['results' => []]);
        }
        
        $results = $this->patientModel->groupStart()
            ->like('first_name', $keyword)
            ->orLike('last_name', $keyword)
            ->orLike('email', $keyword)
            ->orLike('phone', $keyword)
            ->groupEnd()
            ->limit(10)
            ->findAll();
        
        $formatted = [];
        foreach ($results as $patient) {
            $formatted[] = [
                'id' => $patient['id'],
                'text' => $patient['first_name'] . ' ' . $patient['last_name'] . ' (' . $patient['patient_id'] . ')',
                'patient_id' => $patient['patient_id'],
            ];
        }
        
        return $this->response->setJSON(['results' => $formatted]);
    }
}
