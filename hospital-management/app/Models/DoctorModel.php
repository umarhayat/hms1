<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table            = 'doctors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'doctor_id',
        'user_id',
        'specialization',
        'qualification',
        'experience_years',
        'consultation_fee',
        'bio',
        'image',
        'availability_schedule',
        'max_appointments_per_day',
        'status',
        'created_at',
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
        'user_id'          => 'required|integer|is_natural_no_zero',
        'specialization'   => 'required|min_length[3]|max_length[100]',
        'qualification'    => 'required|min_length[3]|max_length[200]',
        'experience_years' => 'permit_empty|integer',
        'consultation_fee' => 'permit_empty|decimal',
        'status'           => 'required|in_list[active,inactive]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateDoctorId'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];

    protected function generateDoctorId(array $data)
    {
        if (empty($data['data']['doctor_id'])) {
            $year = date('Y');
            $lastDoctor = $this->orderBy('id', 'DESC')->first();
            $nextNumber = $lastDoctor ? intval(substr($lastDoctor['doctor_id'], -6)) + 1 : 1;
            $data['data']['doctor_id'] = 'DOC-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    public function getDoctorsWithSpecialization()
    {
        return $this->select('doctors.*, users.name as doctor_name, users.email, users.phone')
                    ->join('users', 'users.id = doctors.user_id')
                    ->where('doctors.status', 'active')
                    ->findAll();
    }

    public function getAvailableDoctors($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $dayOfWeek = date('l', strtotime($date));
        
        return $this->select('doctors.*, users.name as doctor_name')
                    ->join('users', 'users.id = doctors.user_id')
                    ->like('doctors.availability_schedule', $dayOfWeek)
                    ->where('doctors.status', 'active')
                    ->findAll();
    }
}
