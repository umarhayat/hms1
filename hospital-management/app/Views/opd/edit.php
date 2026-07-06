<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('opd') ?>">OPD</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('opd/' . $visit['id']) ?>"><?= esc($visit['opd_number']) ?></a></li>
                    <li class="breadcrumb-item active">Edit Consultation</li>
                </ol>
            </nav>
            <h3><i class="fas fa-edit me-2"></i>Update Consultation - <?= esc($visit['opd_number']) ?></h3>
        </div>
    </div>

    <?= view('partials/alerts') ?>

    <form action="<?= site_url('opd/' . $visit['id'] . '/update') ?>" method="post" id="editOpdForm">
        <?= csrf_field() ?>
        
        <!-- Patient & Doctor Info (Read-only) -->
        <div class="card mb-3 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Patient:</strong> <?= esc($patient['name']) ?> 
                        <small class="text-muted">(<?= esc($patient['age']) ?>y, <?= esc(ucfirst($patient['gender'])) ?>)</small>
                    </div>
                    <div class="col-md-4">
                        <strong>Doctor:</strong> Dr. <?= esc($doctor['name']) ?> 
                        <small class="text-muted">(<?= esc($doctor['specialization']) ?>)</small>
                    </div>
                    <div class="col-md-4">
                        <strong>Visit Date:</strong> <?= date('d M Y, h:i A', strtotime($visit['visit_date'])) ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <strong>Chief Complaint:</strong> <?= esc($visit['complaint_main']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Vitals & Examination -->
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Vital Signs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vital_bp_sys" class="form-label">BP Systolic (mmHg)</label>
                                <input type="number" name="vital_bp_sys" id="vital_bp_sys" class="form-control" 
                                       value="<?= esc($visit['vital_bp_sys']) ?>" min="0" max="300">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_bp_dia" class="form-label">BP Diastolic (mmHg)</label>
                                <input type="number" name="vital_bp_dia" id="vital_bp_dia" class="form-control" 
                                       value="<?= esc($visit['vital_bp_dia']) ?>" min="0" max="200">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_pulse" class="form-label">Pulse Rate (/min)</label>
                                <input type="number" name="vital_pulse" id="vital_pulse" class="form-control" 
                                       value="<?= esc($visit['vital_pulse']) ?>" min="0" max="300">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_temp" class="form-label">Temperature (°F)</label>
                                <input type="step" step="0.1" name="vital_temp" id="vital_temp" class="form-control" 
                                       value="<?= esc($visit['vital_temp']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_weight" class="form-label">Weight (kg)</label>
                                <input type="step" step="0.01" name="vital_weight" id="vital_weight" class="form-control" 
                                       value="<?= esc($visit['vital_weight']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_height" class="form-label">Height (cm)</label>
                                <input type="step" step="0.01" name="vital_height" id="vital_height" class="form-control" 
                                       value="<?= esc($visit['vital_height']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vital_spo2" class="form-label">SpO2 (%)</label>
                                <input type="number" name="vital_spo2" id="vital_spo2" class="form-control" 
                                       value="<?= esc($visit['vital_spo2']) ?>" min="0" max="100">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Examination</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="examination_general" class="form-label">General Examination</label>
                            <textarea name="examination_general" id="examination_general" class="form-control" rows="3"><?= esc($visit['examination_general']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="examination_systemic" class="form-label">Systemic Examination</label>
                            <textarea name="examination_systemic" id="examination_systemic" class="form-control" rows="3"><?= esc($visit['examination_systemic']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Diagnosis & Plan -->
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-diagnoses me-2"></i>Diagnosis</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="diagnosis_provisional" class="form-label">Provisional Diagnosis</label>
                            <input type="text" name="diagnosis_provisional" id="diagnosis_provisional" class="form-control" 
                                   value="<?= esc($visit['diagnosis_provisional']) ?>" placeholder="e.g., Acute Bronchitis">
                        </div>
                        <div class="mb-3">
                            <label for="diagnosis_icd_code" class="form-label">ICD-10 Code</label>
                            <input type="text" name="diagnosis_icd_code" id="diagnosis_icd_code" class="form-control" 
                                   value="<?= esc($visit['diagnosis_icd_code']) ?>" placeholder="e.g., J20.9">
                        </div>
                        
                        <div class="mb-3">
                            <label for="complaint_history" class="form-label">History of Present Illness (Update)</label>
                            <textarea name="complaint_history" id="complaint_history" class="form-control" rows="4"><?= esc($visit['complaint_history']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-prescription-bottle-alt me-2"></i>Treatment Plan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Medications</label>
                            <div id="medicationsContainer">
                                <?php if (!empty($medications)) : ?>
                                    <?php foreach ($medications as $index => $med) : ?>
                                        <?php 
                                        $name = is_array($med) ? ($med['name'] ?? '') : $med;
                                        $dosage = is_array($med) ? ($med['dosage'] ?? '') : '';
                                        $frequency = is_array($med) ? ($med['frequency'] ?? '') : '';
                                        $duration = is_array($med) ? ($med['duration'] ?? '') : '';
                                        ?>
                                        <div class="medication-row mb-2 p-2 border rounded">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="plan_medication[<?= $index ?>][name]" class="form-control form-control-sm" 
                                                           placeholder="Medication Name" value="<?= esc($name) ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="plan_medication[<?= $index ?>][dosage]" class="form-control form-control-sm" 
                                                           placeholder="Dosage" value="<?= esc($dosage) ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="plan_medication[<?= $index ?>][frequency]" class="form-control form-control-sm" 
                                                           placeholder="Frequency" value="<?= esc($frequency) ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="plan_medication[<?= $index ?>][duration]" class="form-control form-control-sm" 
                                                           placeholder="Duration" value="<?= esc($duration) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="medication-row mb-2 p-2 border rounded">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <input type="text" name="medicine_name[]" class="form-control form-control-sm" placeholder="Medicine Name">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="medicine_strength[]" class="form-control form-control-sm" placeholder="Strength">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="medicine_dosage[]" class="form-control form-control-sm" placeholder="Dosage">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="medicine_frequency[]" class="form-control form-control-sm" placeholder="Frequency">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="medicine_duration[]" class="form-control form-control-sm" placeholder="Duration">
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" name="medicine_instructions[]" class="form-control form-control-sm" placeholder="Instructions (e.g., After food)">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addMedication">
                                <i class="fas fa-plus me-1"></i> Add Medicine
                            </button>
                        </div>

                        <div class="mb-3">
                            <label for="plan_investigation" class="form-label">Investigations Advised</label>
                            <textarea name="plan_investigation" id="plan_investigation" class="form-control" rows="2"><?= esc($visit['plan_investigation']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="plan_advice" class="form-label">Advice / Instructions</label>
                            <textarea name="plan_advice" id="plan_advice" class="form-control" rows="2"><?= esc($visit['plan_advice']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="follow_up_date" class="form-label">Follow-up Date</label>
                            <input type="date" name="follow_up_date" id="follow_up_date" class="form-control" 
                                   value="<?= esc($visit['follow_up_date']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Visit Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="triage" <?= $visit['status'] == 'triage' ? 'selected' : '' ?>>Triage</option>
                                <option value="consulting" <?= $visit['status'] == 'consulting' ? 'selected' : '' ?>>Consulting</option>
                                <option value="completed" <?= $visit['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $visit['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="<?= site_url('opd/' . $visit['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> Update Consultation
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let medIndex = <?= count($medications) > 0 ? count($medications) : 1 ?>;

    // Add medication row with new fields
    $('#addMedication').on('click', function() {
        const newRow = `
            <div class="medication-row mb-2 p-2 border rounded">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="medicine_name[]" class="form-control form-control-sm" placeholder="Medicine Name">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="medicine_strength[]" class="form-control form-control-sm" placeholder="Strength">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="medicine_dosage[]" class="form-control form-control-sm" placeholder="Dosage">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="medicine_frequency[]" class="form-control form-control-sm" placeholder="Frequency">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="medicine_duration[]" class="form-control form-control-sm" placeholder="Duration">
                    </div>
                    <div class="col-md-12">
                        <input type="text" name="medicine_instructions[]" class="form-control form-control-sm" placeholder="Instructions (e.g., After food)">
                    </div>
                </div>
            </div>
        `;
        $('#medicationsContainer').append(newRow);
        medIndex++;
    });

    // Auto-calculate BMI when weight/height changes
    $('#vital_weight, #vital_height').on('change', function() {
        const weight = parseFloat($('#vital_weight').val()) || 0;
        const height = parseFloat($('#vital_height').val()) || 0;
        
        if (weight > 0 && height > 0) {
            const heightInMeters = height / 100;
            const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(2);
            console.log('Calculated BMI:', bmi);
        }
    });
});
</script>
<?= $this->endSection() ?>
