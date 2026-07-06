<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('opd') ?>">OPD</a></li>
                    <li class="breadcrumb-item active"><?= esc($visit['opd_number']) ?></li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-medical me-2"></i><?= esc($page_title) ?></h3>
                <div>
                    <?php if ($visit['status'] != 'completed') : ?>
                        <a href="<?= site_url('opd/' . $visit['id'] . '/edit') ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Update Consultation
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('opd') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?= view('partials/alerts') ?>

    <!-- Patient Info Card -->
    <div class="card mb-3 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-injured me-2"></i>Patient Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Name:</strong><br>
                    <?= esc($patient['name']) ?>
                </div>
                <div class="col-md-2">
                    <strong>Age/Gender:</strong><br>
                    <?= esc($patient['age']) ?> / <?= esc(ucfirst($patient['gender'])) ?>
                </div>
                <div class="col-md-3">
                    <strong>Contact:</strong><br>
                    <?= esc($patient['phone']) ?><br>
                    <small class="text-muted"><?= esc($patient['email'] ?? '') ?></small>
                </div>
                <div class="col-md-4">
                    <strong>Address:</strong><br>
                    <small><?= esc($patient['address'] ?? 'N/A') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Details -->
    <div class="row">
        <!-- Left Column: Clinical Notes -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Clinical Notes</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Chief Complaints:</label>
                        <p><?= nl2br(esc($visit['complaint_main'])) ?></p>
                    </div>
                    
                    <?php if (!empty($visit['complaint_history'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">History of Present Illness:</label>
                        <p><?= nl2br(esc($visit['complaint_history'])) ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Vitals -->
                    <?php if (!empty($visit['vital_bp_sys']) || !empty($visit['vital_pulse'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold"><i class="fas fa-heartbeat me-1"></i>Vital Signs:</label>
                        <div class="row mt-2">
                            <?php if (!empty($visit['vital_bp_sys'])) : ?>
                            <div class="col-md-3">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">BP</small><br>
                                    <strong><?= $visit['vital_bp_sys'] ?>/<?= $visit['vital_bp_dia'] ?> mmHg</strong>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($visit['vital_pulse'])) : ?>
                            <div class="col-md-3">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">Pulse</small><br>
                                    <strong><?= $visit['vital_pulse'] ?> /min</strong>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($visit['vital_temp'])) : ?>
                            <div class="col-md-3">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">Temp</small><br>
                                    <strong><?= $visit['vital_temp'] ?> °F</strong>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($visit['vital_spo2'])) : ?>
                            <div class="col-md-3">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">SpO2</small><br>
                                    <strong><?= $visit['vital_spo2'] ?> %</strong>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($visit['vital_weight']) && !empty($visit['vital_height'])) : ?>
                            <div class="col-md-3 mt-2">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">BMI</small><br>
                                    <strong><?= $bmi ?? 'N/A' ?></strong>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Examination -->
                    <?php if (!empty($visit['examination_general']) || !empty($visit['examination_systemic'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">Examination Findings:</label>
                        <?php if (!empty($visit['examination_general'])) : ?>
                        <p><strong>General:</strong> <?= nl2br(esc($visit['examination_general'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($visit['examination_systemic'])) : ?>
                        <p><strong>Systemic:</strong> <?= nl2br(esc($visit['examination_systemic'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Diagnosis -->
                    <?php if (!empty($visit['diagnosis_provisional'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">Diagnosis:</label>
                        <p class="text-primary"><?= esc($visit['diagnosis_provisional']) ?>
                            <?php if (!empty($visit['diagnosis_icd_code'])) : ?>
                                <span class="badge bg-info"><?= esc($visit['diagnosis_icd_code']) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Plan & Medications -->
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-prescription me-2"></i>Treatment Plan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($medications)) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">Medications Prescribed:</label>
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>Medication</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medications as $med) : ?>
                                    <?php if (is_array($med)) : ?>
                                    <tr>
                                        <td><?= esc($med['name'] ?? '') ?></td>
                                        <td><?= esc($med['dosage'] ?? '') ?></td>
                                        <td><?= esc($med['frequency'] ?? '') ?></td>
                                        <td><?= esc($med['duration'] ?? '') ?></td>
                                    </tr>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="4"><?= esc($med) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($visit['plan_investigation'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">Investigations Advised:</label>
                        <p><?= nl2br(esc($visit['plan_investigation'])) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($visit['plan_advice'])) : ?>
                    <div class="mb-3">
                        <label class="fw-bold">Advice:</label>
                        <p><?= nl2br(esc($visit['plan_advice'])) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($visit['follow_up_date'])) : ?>
                    <div class="alert alert-info mb-0">
                        <strong>Follow-up Date:</strong> <?= date('d M Y', strtotime($visit['follow_up_date'])) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Metadata & History -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Visit Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>OPD Number:</th>
                            <td class="text-primary fw-bold"><?= esc($visit['opd_number']) ?></td>
                        </tr>
                        <tr>
                            <th>Consulting Doctor:</th>
                            <td>Dr. <?= esc($doctor['name']) ?><br><small class="text-muted"><?= esc($doctor['specialization']) ?></small></td>
                        </tr>
                        <tr>
                            <th>Visit Date:</th>
                            <td><?= date('d M Y, h:i A', strtotime($visit['visit_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <?php
                                $badgeClass = match($visit['status']) {
                                    'waiting' => 'bg-warning',
                                    'triage' => 'bg-info',
                                    'consulting' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($visit['status']) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td><?= date('d M Y, h:i A', strtotime($visit['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?= date('d M Y, h:i A', strtotime($visit['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Past Visits -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Past Visits</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($past_visits) || count($past_visits) <= 1) : ?>
                        <p class="text-muted mb-0">No previous visits found.</p>
                    <?php else : ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($past_visits as $pv) : ?>
                                <?php if ($pv['id'] != $visit['id']) : ?>
                                <li class="list-group-item px-0">
                                    <a href="<?= site_url('opd/' . $pv['id']) ?>" class="text-decoration-none">
                                        <strong><?= esc($pv['opd_number']) ?></strong><br>
                                        <small class="text-muted"><?= date('d M Y', strtotime($pv['visit_date'])) ?></small><br>
                                        <small><?= esc(substr($pv['diagnosis_provisional'] ?? $pv['complaint_main'], 0, 30)) ?>...</small>
                                    </a>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
