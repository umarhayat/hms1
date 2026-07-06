<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($page_title) ?></h1>
        <div>
            <a href="<?= site_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-warning btn-icon-split me-2">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Edit</span>
            </a>
            <a href="<?= site_url('patients') ?>" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Back to List</span>
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Patient Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-circle"></i> Patient Profile</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; font-size: 48px;">
                            <?= strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)) ?>
                        </div>
                    </div>
                    <h4 class="mb-1"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></h4>
                    <p class="text-muted mb-2"><?= esc($patient['patient_id']) ?></p>
                    
                    <?php if ($patient['blood_group']): ?>
                    <div class="mb-3">
                        <span class="badge bg-danger fs-6"><?= esc($patient['blood_group']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <span class="badge <?= $patient['status'] == 'active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= ucfirst(esc($patient['status'])) ?>
                        </span>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-birthday-cake"></i> Age:</small>
                            <span class="float-end fw-bold"><?= $age ?> years</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-venus-mars"></i> Gender:</small>
                            <span class="float-end fw-bold"><?= ucfirst(esc($patient['gender'])) ?></span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-phone"></i> Phone:</small>
                            <span class="float-end"><?= esc($patient['phone']) ?></span>
                        </div>
                        <?php if ($patient['email']): ?>
                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-envelope"></i> Email:</small>
                            <span class="float-end text-truncate" style="max-width: 150px;"><?= esc($patient['email']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="mb-2">
                            <small class="text-muted"><i class="fas fa-calendar"></i> Registered:</small>
                            <span class="float-end"><?= date('M d, Y', strtotime($patient['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Card -->
            <?php if ($patient['emergency_contact_name'] || $patient['emergency_contact_phone']): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Emergency Contact</h6>
                </div>
                <div class="card-body">
                    <?php if ($patient['emergency_contact_name']): ?>
                    <div class="mb-2">
                        <strong>Name:</strong><br>
                        <?= esc($patient['emergency_contact_name']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($patient['emergency_contact_phone']): ?>
                    <div>
                        <strong>Phone:</strong><br>
                        <a href="tel:<?= esc($patient['emergency_contact_phone']) ?>" class="text-decoration-none">
                            <?= esc($patient['emergency_contact_phone']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Insurance Card -->
            <?php if ($patient['insurance_provider'] || $patient['insurance_policy_number']): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-shield-alt"></i> Insurance Information</h6>
                </div>
                <div class="card-body">
                    <?php if ($patient['insurance_provider']): ?>
                    <div class="mb-2">
                        <strong>Provider:</strong><br>
                        <?= esc($patient['insurance_provider']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($patient['insurance_policy_number']): ?>
                    <div>
                        <strong>Policy Number:</strong><br>
                        <?= esc($patient['insurance_policy_number']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Address Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map-marker-alt"></i> Address Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Street Address</label>
                            <p class="mb-0"><?= esc($patient['address'] ?? 'Not provided') ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">City</label>
                            <p class="mb-0"><?= esc($patient['city'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">State/Province</label>
                            <p class="mb-0"><?= esc($patient['state'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">ZIP/Postal Code</label>
                            <p class="mb-0"><?= esc($patient['zip_code'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Country</label>
                            <p class="mb-0"><?= esc($patient['country'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-stethoscope"></i> Medical Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Medical History</h6>
                        <?php if ($patient['medical_history']): ?>
                            <p class="mb-0"><?= nl2br(esc($patient['medical_history'])) ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">No medical history recorded.</p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Allergies</h6>
                        <?php if ($patient['allergies']): ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> <?= nl2br(esc($patient['allergies'])) ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No known allergies.</p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Current Medications</h6>
                        <?php if ($patient['current_medications']): ?>
                            <p class="mb-0"><?= nl2br(esc($patient['current_medications'])) ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">No current medications listed.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?= site_url('appointments/create?patient_id=' . $patient['id']) ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-plus"></i><br>
                                <small>New Appointment</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= site_url('medical-records/create?patient_id=' . $patient['id']) ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-medical"></i><br>
                                <small>Add Medical Record</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="fas fa-print"></i><br>
                                <small>Print Summary</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-outline-danger w-100" onclick="confirmDelete(<?= $patient['id'] ?>)">
                                <i class="fas fa-trash"></i><br>
                                <small>Delete Patient</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="post" action="<?= site_url('patients/' . $patient['id']) ?>" style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</form>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

<style>
.avatar-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<?= $this->endSection() ?>
