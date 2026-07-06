<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMedicalRecordsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'record_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'appointment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'chief_complaint' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'history_of_present_illness' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'past_medical_history' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'family_history' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'social_history' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'review_of_systems' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'physical_examination' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'vital_signs' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Store as JSON: {"bp": "120/80", "temperature": "98.6F", "pulse": "72", "respiratory": "16"}',
            ],
            'diagnosis_primary' => [
                'type'       => 'TEXT',
            ],
            'diagnosis_secondary' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'icd_codes' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Store ICD-10 codes as JSON array',
            ],
            'treatment_plan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'prescription' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Store prescriptions as JSON array',
            ],
            'lab_orders' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'lab_results' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'imaging_orders' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'imaging_results' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'referrals' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'follow_up_instructions' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'ai_analysis' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'AI-generated analysis',
            ],
            'ai_confidence_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,4',
                'null'       => true,
            ],
            'ai_recommendations' => [
                'type'       => 'JSON',
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('record_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('medical_records');
    }

    public function down()
    {
        $this->forge->dropTable('medical_records');
    }
}
