<?php

namespace App\Models;

use CodeIgniter\Model;

class OpdMedicineModel extends Model
{
    protected $table            = 'opd_medicines';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'opd_visit_id',
        'medicine_name',
        'strength',
        'dosage',
        'frequency',
        'duration',
        'instructions',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'opd_visit_id'  => 'required|integer',
        'medicine_name' => 'required|max_length[255]',
        'dosage'        => 'required|max_length[100]',
        'frequency'     => 'required|max_length[100]',
        'duration'      => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'medicine_name' => [
            'required' => 'Medicine name is required'
        ],
        'dosage' => [
            'required' => 'Dosage is required'
        ],
        'frequency' => [
            'required' => 'Frequency is required'
        ],
        'duration' => [
            'required' => 'Duration is required'
        ],
    ];

    /**
     * Get medicines for a specific OPD visit
     */
    public function getMedicinesByOpdId($opdId)
    {
        return $this->where('opd_visit_id', $opdId)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Save multiple medicines for an OPD visit
     */
    public function saveMedicines($opdId, array $medicines)
    {
        // First, delete existing medicines for this visit (soft delete or hard delete based on requirement)
        // For simplicity, we'll hard delete old entries when updating
        $this->where('opd_visit_id', $opdId)->delete();

        if (empty($medicines)) {
            return true;
        }

        $batchData = [];
        foreach ($medicines as $med) {
            $batchData[] = [
                'opd_visit_id'  => $opdId,
                'medicine_name' => $med['medicine_name'],
                'strength'      => $med['strength'] ?? null,
                'dosage'        => $med['dosage'],
                'frequency'     => $med['frequency'],
                'duration'      => $med['duration'],
                'instructions'  => $med['instructions'] ?? null,
            ];
        }

        return $this->insertBatch($batchData);
    }
}
