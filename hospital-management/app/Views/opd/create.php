<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('opd') ?>">OPD</a></li>
                    <li class="breadcrumb-item active">New Visit</li>
                </ol>
            </nav>
            <h3><i class="fas fa-plus-circle me-2"></i><?= esc($page_title) ?></h3>
        </div>
    </div>

    <?= view('partials/alerts') ?>

    <form action="<?= site_url('opd/store') ?>" method="post" id="opdForm">
        <?= csrf_field() ?>
        
        <div class="row">
            <!-- Left Column: Patient & Doctor Selection -->
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-user-injured me-2"></i>Patient Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Select Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select select2" required>
                                <option value="">-- Search and Select Patient --</option>
                                <?php foreach ($patients as $patient) : ?>
                                    <option value="<?= $patient['id'] ?>" 
                                            data-phone="<?= esc($patient['phone']) ?>"
                                            data-age="<?= esc($patient['age']) ?>"
                                            data-gender="<?= esc($patient['gender']) ?>">
                                        <?= esc($patient['name']) ?> (<?= esc($patient['gender']) ?>, <?= esc($patient['age']) ?>y) - <?= esc($patient['phone']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a patient</div>
                        </div>

                        <div class="alert alert-info" id="patientInfo" style="display:none;">
                            <strong>Selected Patient:</strong> <span id="selectedPatientName"></span><br>
                            <small>
                                Age: <span id="selectedPatientAge"></span> | 
                                Gender: <span id="selectedPatientGender"></span> | 
                                Phone: <span id="selectedPatientPhone"></span>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctor Assignment</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Consulting Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="form-select select2" required>
                                <option value="">-- Select Doctor --</option>
                                <?php foreach ($doctors as $doctor) : ?>
                                    <option value="<?= $doctor['id'] ?>">
                                        Dr. <?= esc($doctor['name']) ?> - <?= esc($doctor['specialization']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a doctor</div>
                        </div>

                        <div class="mb-3">
                            <label for="visit_date" class="form-label">Visit Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="visit_date" id="visit_date" class="form-control" 
                                   value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Chief Complaints & History -->
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Clinical Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="complaint_main" class="form-label">Chief Complaints <span class="text-danger">*</span></label>
                            <textarea name="complaint_main" id="complaint_main" class="form-control" rows="3" 
                                      placeholder="e.g., Fever since 3 days, headache, cough" required></textarea>
                            <div class="invalid-feedback">Chief complaints are required</div>
                        </div>

                        <div class="mb-3">
                            <label for="complaint_history" class="form-label">History of Present Illness</label>
                            <textarea name="complaint_history" id="complaint_history" class="form-control" rows="4" 
                                      placeholder="Detailed history including onset, duration, progression..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vitals Section -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Vital Signs (Triage)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="vital_bp_sys" class="form-label">BP Systolic (mmHg)</label>
                        <input type="number" name="vital_bp_sys" id="vital_bp_sys" class="form-control" 
                               min="0" max="300" placeholder="120">
                    </div>
                    <div class="col-md-3">
                        <label for="vital_bp_dia" class="form-label">BP Diastolic (mmHg)</label>
                        <input type="number" name="vital_bp_dia" id="vital_bp_dia" class="form-control" 
                               min="0" max="200" placeholder="80">
                    </div>
                    <div class="col-md-3">
                        <label for="vital_pulse" class="form-label">Pulse Rate (/min)</label>
                        <input type="number" name="vital_pulse" id="vital_pulse" class="form-control" 
                               min="0" max="300" placeholder="72">
                    </div>
                    <div class="col-md-3">
                        <label for="vital_temp" class="form-label">Temperature (°F)</label>
                        <input type="step" step="0.1" name="vital_temp" id="vital_temp" class="form-control" 
                               placeholder="98.6">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="vital_weight" class="form-label">Weight (kg)</label>
                        <input type="step" step="0.01" name="vital_weight" id="vital_weight" class="form-control" 
                               placeholder="70">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="vital_height" class="form-label">Height (cm)</label>
                        <input type="step" step="0.01" name="vital_height" id="vital_height" class="form-control" 
                               placeholder="170">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="vital_spo2" class="form-label">SpO2 (%)</label>
                        <input type="number" name="vital_spo2" id="vital_spo2" class="form-control" 
                               min="0" max="100" placeholder="98">
                    </div>
                </div>
            </div>
        </div>

        <!-- Examination, Diagnosis & Plan (Can be filled later during consultation) -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Examination & Plan (Optional at Registration)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="examination_general" class="form-label">General Examination</label>
                        <textarea name="examination_general" id="examination_general" class="form-control" rows="2" 
                                  placeholder="Conscious, oriented, no pallor, no icterus..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="examination_systemic" class="form-label">Systemic Examination</label>
                        <textarea name="examination_systemic" id="examination_systemic" class="form-control" rows="2" 
                                  placeholder="CVS: S1S2+, RS: BAE+, P/A: Soft..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="diagnosis_provisional" class="form-label">Provisional Diagnosis</label>
                        <input type="text" name="diagnosis_provisional" id="diagnosis_provisional" class="form-control" 
                               placeholder="e.g., Acute Upper Respiratory Tract Infection">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="diagnosis_icd_code" class="form-label">ICD Code</label>
                        <input type="text" name="diagnosis_icd_code" id="diagnosis_icd_code" class="form-control" 
                               placeholder="e.g., J06.9">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="plan_medication" class="form-label">Medications (One per line: Name, Dosage, Frequency, Duration)</label>
                        <textarea name="plan_medication" id="plan_medication" class="form-control" rows="3" 
                                  placeholder="Paracetamol 500mg, 1-0-1, 5 days&#10;Amoxicillin 500mg, 0-0-1, 7 days"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="plan_investigation" class="form-label">Investigations Advised</label>
                        <textarea name="plan_investigation" id="plan_investigation" class="form-control" rows="2" 
                                  placeholder="CBC, Chest X-ray PA view"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="plan_advice" class="form-label">Advice / Follow-up Instructions</label>
                        <textarea name="plan_advice" id="plan_advice" class="form-control" rows="2" 
                                  placeholder="Take rest, drink plenty of fluids, review after 3 days"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="follow_up_date" class="form-label">Follow-up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= site_url('opd') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> Register OPD Visit
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Patient selection handler
    $('#patient_id').on('change', function() {
        var option = $(this).find('option:selected');
        if ($(this).val()) {
            $('#selectedPatientName').text(option.text().split('(')[0].trim());
            $('#selectedPatientAge').text(option.data('age'));
            $('#selectedPatientGender').text(option.data('gender'));
            $('#selectedPatientPhone').text(option.data('phone'));
            $('#patientInfo').fadeIn();
        } else {
            $('#patientInfo').fadeOut();
        }
    });

    // Form validation
    $('#opdForm').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
});
</script>
<?= $this->endSection() ?>
