<?php

namespace App\Models;

use CodeIgniter\Model;

class OpdVisitModel extends Model
{
    protected $table            = 'opd_visits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields = [
        'opd_number',
        'patient_id',
        'doctor_id',
        'visit_date',
        'complaint_main',
        'complaint_history',
        // Vitals
        'vital_bp_sys',
        'vital_bp_dia',
        'vital_pulse',
        'vital_temp',
        'vital_weight',
        'vital_height',
        'vital_spo2',
        // Examination
        'examination_general',
        'examination_systemic',
        // Diagnosis
        'diagnosis_provisional',
        'diagnosis_icd_code',
        // Plan
        'plan_medication',
        'plan_investigation',
        'plan_advice',
        'status',
        'follow_up_date',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'patient_id' => 'required|integer',
        'doctor_id'  => 'required|integer',
        'visit_date' => 'permit_empty|valid_date',
        'complaint_main' => 'required|min_length[5]',
        'vital_bp_sys' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[300]',
        'vital_bp_dia' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[200]',
        'vital_pulse'  => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[300]',
        'vital_temp'   => 'permit_empty|decimal',
        'vital_weight' => 'permit_empty|decimal',
        'vital_height' => 'permit_empty|decimal',
        'vital_spo2'   => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'status'       => 'permit_empty|in_list[waiting,triage,consulting,completed,cancelled]',
        'follow_up_date' => 'permit_empty|valid_date',
    ];

    protected $validationMessages = [
        'patient_id' => [
            'required' => 'Patient selection is required.'
        ],
        'doctor_id' => [
            'required' => 'Doctor selection is required.'
        ],
        'complaint_main' => [
            'required' => 'Chief complaint is required.',
            'min_length' => 'Complaint must be at least 5 characters long.'
        ]
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo('App\Models\PatientModel', 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Models\DoctorModel', 'doctor_id', 'id');
    }

    // Helper Methods
    public function generateOpdNumber($patientId)
    {
        $prefix = 'OPD-' . date('Ymd') . '-';
        
        // Get count of visits today for this patient or general sequence
        $todayStart = date('Y-m-d 00:00:00');
        $count = $this->where('visit_date >=', $todayStart)->countAllResults();
        
        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getVisitsByPatient($patientId, $limit = 10)
    {
        return $this->select('opd_visits.*, patients.name as patient_name, doctors.name as doctor_name')
                    ->join('patients', 'patients.id = opd_visits.patient_id')
                    ->join('doctors', 'doctors.id = opd_visits.doctor_id')
                    ->where('opd_visits.patient_id', $patientId)
                    ->orderBy('opd_visits.visit_date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getTodayQueue($doctorId = null)
    {
        $today = date('Y-m-d');
        $builder = $this->select('opd_visits.*, patients.name as patient_name, patients.age, patients.gender, doctors.name as doctor_name')
                        ->join('patients', 'patients.id = opd_visits.patient_id')
                        ->join('doctors', 'doctors.id = opd_visits.doctor_id')
                        ->where('DATE(opd_visits.visit_date)', $today)
                        ->where('opd_visits.status !=', 'cancelled')
                        ->orderBy('opd_visits.visit_date', 'ASC');
        
        if ($doctorId) {
            $builder->where('opd_visits.doctor_id', $doctorId);
        }
        
        return $builder->findAll();
    }

    public function calculateBMI($weight, $height)
    {
        if (!$weight || !$height || $height == 0) return null;
        $heightInMeters = $height / 100;
        return round($weight / ($heightInMeters * $heightInMeters), 2);
    }
}
