<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'reason',
        'symptoms',
        'status',
        'notes',
        'diagnosis',
        'prescription',
        'follow_up_date',
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
        'patient_id'       => 'required|integer|is_natural_no_zero',
        'doctor_id'        => 'required|integer|is_natural_no_zero',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'status'           => 'required|in_list[scheduled,confirmed,completed,cancelled,no_show]',
        'duration_minutes' => 'permit_empty|integer',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateAppointmentId'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];

    protected function generateAppointmentId(array $data)
    {
        if (empty($data['data']['appointment_id'])) {
            $year = date('Y');
            $lastAppointment = $this->orderBy('id', 'DESC')->first();
            $nextNumber = $lastAppointment ? intval(substr($lastAppointment['appointment_id'], -6)) + 1 : 1;
            $data['data']['appointment_id'] = 'APT-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    public function getAppointmentsWithDetails($filters = [])
    {
        $builder = $this->select('appointments.*, 
                                  p.first_name as patient_first_name, 
                                  p.last_name as patient_last_name,
                                  p.phone as patient_phone,
                                  d.doctor_id as doc_code,
                                  u.name as doctor_name,
                                  d.specialization')
                        ->join('patients p', 'p.id = appointments.patient_id')
                        ->join('doctors d', 'd.id = appointments.doctor_id')
                        ->join('users u', 'u.id = d.user_id');

        if (!empty($filters['status'])) {
            $builder->where('appointments.status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $builder->where('appointments.appointment_date', $filters['date']);
        }

        if (!empty($filters['doctor_id'])) {
            $builder->where('appointments.doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['patient_id'])) {
            $builder->where('appointments.patient_id', $filters['patient_id']);
        }

        return $builder->orderBy('appointments.appointment_date', 'DESC')
                       ->orderBy('appointments.appointment_time', 'DESC')
                       ->findAll();
    }

    public function getTodayAppointments()
    {
        return $this->getAppointmentsWithDetails(['date' => date('Y-m-d')]);
    }

    public function getUpcomingAppointments($patientId)
    {
        return $this->select('appointments.*, u.name as doctor_name, d.specialization')
                    ->join('doctors d', 'd.id = appointments.doctor_id')
                    ->join('users u', 'u.id = d.user_id')
                    ->where('appointments.patient_id', $patientId)
                    ->where('appointments.appointment_date >=', date('Y-m-d'))
                    ->whereIn('appointments.status', ['scheduled', 'confirmed'])
                    ->orderBy('appointments.appointment_date', 'ASC')
                    ->orderBy('appointments.appointment_time', 'ASC')
                    ->findAll();
    }

    public function getAppointmentStats($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = date('Y-m-01');
        }
        if (!$endDate) {
            $endDate = date('Y-m-t');
        }

        return $this->select('status, COUNT(*) as count')
                    ->where('appointment_date >=', $startDate)
                    ->where('appointment_date <=', $endDate)
                    ->groupBy('status')
                    ->get()
                    ->getResultArray();
    }
}
