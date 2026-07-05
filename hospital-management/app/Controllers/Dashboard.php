<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\AppointmentModel;
use App\Models\MedicalRecordModel;
use App\Libraries\AIAnalysisService;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $patientModel;
    protected $doctorModel;
    protected $appointmentModel;
    protected $medicalRecordModel;
    protected $aiService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->aiService = new AIAnalysisService();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'totalPatients' => $this->patientModel->countAllResults(),
            'totalDoctors' => $this->doctorModel->countAllResults(),
            'todayAppointments' => count($this->appointmentModel->getTodayAppointments()),
            'recentRecords' => $this->medicalRecordModel->getRecentRecords(5),
        ];

        // Get appointment statistics for the current month
        $stats = $this->appointmentModel->getAppointmentStats();
        $data['appointmentStats'] = $stats;

        return view('dashboard/index', $data);
    }

    public function analytics()
    {
        $data = [
            'title' => 'Analytics & Reports',
        ];

        // Patient demographics
        $data['patientGrowth'] = $this->getPatientGrowthData();
        
        // Appointment trends
        $data['appointmentTrends'] = $this->getAppointmentTrends();
        
        // Common diagnoses (from medical records)
        $data['commonDiagnoses'] = $this->getCommonDiagnoses();
        
        // Doctor performance metrics
        $data['doctorMetrics'] = $this->getDoctorMetrics();

        return view('reports/analytics', $data);
    }

    protected function getPatientGrowthData(): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('patients')
                    ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                    ->groupBy('month')
                    ->orderBy('month', 'ASC')
                    ->limit(12)
                    ->get();
        
        return $query->getResultArray();
    }

    protected function getAppointmentTrends(): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('appointments')
                    ->select("appointment_date, status, COUNT(*) as count")
                    ->groupBy('appointment_date, status')
                    ->orderBy('appointment_date', 'DESC')
                    ->limit(30)
                    ->get();
        
        return $query->getResultArray();
    }

    protected function getCommonDiagnoses(): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('medical_records')
                    ->select('diagnosis_primary, COUNT(*) as count')
                    ->groupBy('diagnosis_primary')
                    ->orderBy('count', 'DESC')
                    ->limit(10)
                    ->get();
        
        return $query->getResultArray();
    }

    protected function getDoctorMetrics(): array
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('appointments')
                    ->select('appointments.doctor_id, users.name, COUNT(*) as total_appointments, 
                             SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
                    ->join('doctors', 'doctors.id = appointments.doctor_id')
                    ->join('users', 'users.id = doctors.user_id')
                    ->groupBy('appointments.doctor_id')
                    ->get();
        
        return $query->getResultArray();
    }
}
