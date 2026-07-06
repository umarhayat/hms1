<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'patient_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
        'current_medications',
        'insurance_provider',
        'insurance_policy_number',
        'status',
        'created_by',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name'  => 'required|min_length[2]|max_length[50]',
        'date_of_birth' => 'required|valid_date',
        'gender'     => 'required|in_list[male,female,other]',
        'blood_group' => 'permit_empty|in_list[A+,A-,B+,B-,AB+,AB-,O+,O-]',
        'email'      => 'permit_empty|valid_email|is_unique[patients.email,id,{id}]',
        'phone'      => 'required|max_length[20]',
        'status'     => 'required|in_list[active,inactive]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generatePatientId'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];

    protected function generatePatientId(array $data)
    {
        if (empty($data['data']['patient_id'])) {
            $year = date('Y');
            $lastPatient = $this->orderBy('id', 'DESC')->first();
            $nextNumber = $lastPatient ? intval(substr($lastPatient['patient_id'], -6)) + 1 : 1;
            $data['data']['patient_id'] = 'PAT-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    public function getFullname()
    {
        return $this->select('first_name, last_name')
                    ->asArray()
                    ->findAll();
    }

    public function searchPatients($keyword)
    {
        return $this->groupStart()
                    ->like('first_name', $keyword)
                    ->orLike('last_name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('phone', $keyword)
                    ->orLike('patient_id', $keyword)
                    ->groupEnd()
                    ->findAll();
    }
}
