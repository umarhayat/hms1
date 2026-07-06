<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table            = 'medical_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'record_id',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'chief_complaint',
        'history_of_present_illness',
        'past_medical_history',
        'family_history',
        'social_history',
        'review_of_systems',
        'physical_examination',
        'vital_signs',
        'diagnosis_primary',
        'diagnosis_secondary',
        'icd_codes',
        'treatment_plan',
        'prescription',
        'lab_orders',
        'lab_results',
        'imaging_orders',
        'imaging_results',
        'referrals',
        'follow_up_instructions',
        'ai_analysis',
        'ai_confidence_score',
        'ai_recommendations',
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
        'patient_id' => 'required|integer|is_natural_no_zero',
        'doctor_id'  => 'required|integer|is_natural_no_zero',
        'diagnosis_primary' => 'required|min_length[3]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateRecordId'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];

    protected function generateRecordId(array $data)
    {
        if (empty($data['data']['record_id'])) {
            $year = date('Y');
            $lastRecord = $this->orderBy('id', 'DESC')->first();
            $nextNumber = $lastRecord ? intval(substr($lastRecord['record_id'], -6)) + 1 : 1;
            $data['data']['record_id'] = 'REC-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    public function getPatientRecords($patientId, $limit = 50)
    {
        return $this->select('medical_records.*, 
                              u.name as doctor_name, 
                              d.specialization,
                              a.appointment_date')
                    ->join('doctors d', 'd.id = medical_records.doctor_id')
                    ->join('users u', 'u.id = d.user_id')
                    ->join('appointments a', 'a.id = medical_records.appointment_id', 'left')
                    ->where('medical_records.patient_id', $patientId)
                    ->orderBy('medical_records.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getRecentRecords($limit = 10)
    {
        return $this->select('medical_records.*, 
                              p.first_name, 
                              p.last_name,
                              u.name as doctor_name')
                    ->join('patients p', 'p.id = medical_records.patient_id')
                    ->join('doctors d', 'd.id = medical_records.doctor_id')
                    ->join('users u', 'u.id = d.user_id')
                    ->orderBy('medical_records.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function searchRecords($keyword)
    {
        return $this->groupStart()
                    ->like('chief_complaint', $keyword)
                    ->orLike('diagnosis_primary', $keyword)
                    ->orLike('treatment_plan', $keyword)
                    ->orLike('prescription', $keyword)
                    ->groupEnd()
                    ->findAll();
    }

    public function getRecordsWithAIAnalysis($limit = 20)
    {
        return $this->select('medical_records.*, 
                              p.first_name, 
                              p.last_name,
                              u.name as doctor_name')
                    ->join('patients p', 'p.id = medical_records.patient_id')
                    ->join('doctors d', 'd.id = medical_records.doctor_id')
                    ->join('users u', 'u.id = d.user_id')
                    ->where('medical_records.ai_analysis IS NOT NULL', null, false)
                    ->orderBy('medical_records.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
