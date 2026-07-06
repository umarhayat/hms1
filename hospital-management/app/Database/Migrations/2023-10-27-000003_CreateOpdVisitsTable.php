<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOpdVisitsTable extends Migration
{
    public function up()
    {
        // OPD Visits Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'opd_number' => [
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
            'visit_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'complaint_main' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Chief Complaints',
            ],
            'complaint_history' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'History of Present Illness',
            ],
            // Vitals (Triage)
            'vital_bp_sys' => ['type' => 'INT', 'null' => true, 'comment' => 'Systolic BP'],
            'vital_bp_dia' => ['type' => 'INT', 'null' => true, 'comment' => 'Diastolic BP'],
            'vital_pulse'  => ['type' => 'INT', 'null' => true, 'comment' => 'Pulse Rate'],
            'vital_temp'   => ['type' => 'DECIMAL', 'constraint' => '4,1', 'null' => true, 'comment' => 'Temperature'],
            'vital_weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'comment' => 'Weight (kg)'],
            'vital_height' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'comment' => 'Height (cm)'],
            'vital_spo2'   => ['type' => 'INT', 'null' => true, 'comment' => 'SpO2 %'],
            
            // Examination
            'examination_general' => ['type' => 'TEXT', 'null' => true],
            'examination_systemic' => ['type' => 'TEXT', 'null' => true],
            
            // Diagnosis
            'diagnosis_provisional' => ['type' => 'TEXT', 'null' => true],
            'diagnosis_icd_code'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            // Plan
            'plan_medication' => ['type' => 'TEXT', 'null' => true, 'comment' => 'JSON or serialized array of medicines'],
            'plan_investigation' => ['type' => 'TEXT', 'null' => true, 'comment' => 'Lab/Radiology orders'],
            'plan_advice'       => ['type' => 'TEXT', 'null' => true],
            
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['waiting', 'triage', 'consulting', 'completed', 'cancelled'],
                'default'    => 'waiting',
            ],
            'follow_up_date' => ['type' => 'DATE', 'null' => true],
            'created_at' => new RawSql('TIMESTAMP DEFAULT CURRENT_TIMESTAMP'),
            'updated_at' => new RawSql('TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('status');
        
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('opd_visits');
    }

    public function down()
    {
        $this->forge->dropTable('opd_visits');
    }
}
