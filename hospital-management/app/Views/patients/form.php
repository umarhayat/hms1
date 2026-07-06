<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($page_title) ?></h1>
        <a href="<?= site_url('patients') ?>" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Back to List</span>
        </a>
    </div>

    <!-- Patient Form -->
    <div class="row">
        <div class="col-lg-12">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-triangle"></i> Validation Errors:</h6>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <form method="post" action="<?= isset($patient) && $patient ? site_url('patients/' . $patient['id']) : site_url('patients') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($patient) && $patient): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <!-- Personal Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="<?= old('first_name', $patient['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="<?= old('last_name', $patient['last_name'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="<?= old('date_of_birth', $patient['date_of_birth'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?= old('gender', $patient['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= old('gender', $patient['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= old('gender', $patient['gender'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select class="form-select" id="blood_group" name="blood_group">
                            <option value="">Select Blood Group</option>
                            <?php 
                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                            foreach ($bloodGroups as $bg): 
                            ?>
                                <option value="<?= $bg ?>" <?= old('blood_group', $patient['blood_group'] ?? '') == $bg ? 'selected' : '' ?>><?= $bg ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email', $patient['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?= old('phone', $patient['phone'] ?? '') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Address Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="address" class="form-label">Street Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2"><?= old('address', $patient['address'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="<?= old('city', $patient['city'] ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" 
                               value="<?= old('state', $patient['state'] ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" 
                               value="<?= old('zip_code', $patient['zip_code'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" 
                               value="<?= old('country', $patient['country'] ?? 'USA') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Emergency Contact</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="emergency_contact_name" class="form-label">Contact Name</label>
                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                               value="<?= old('emergency_contact_name', $patient['emergency_contact_name'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                        <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                               value="<?= old('emergency_contact_phone', $patient['emergency_contact_phone'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Medical Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="medical_history" class="form-label">Medical History</label>
                    <textarea class="form-control" id="medical_history" name="medical_history" rows="3" placeholder="List any chronic conditions, previous surgeries, or significant medical events..."><?= old('medical_history', $patient['medical_history'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="allergies" class="form-label">Allergies</label>
                    <textarea class="form-control" id="allergies" name="allergies" rows="2" placeholder="List any known allergies (medications, food, environmental)..."><?= old('allergies', $patient['allergies'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="current_medications" class="form-label">Current Medications</label>
                    <textarea class="form-control" id="current_medications" name="current_medications" rows="2" placeholder="List current medications with dosage..."><?= old('current_medications', $patient['current_medications'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="insurance_provider" class="form-label">Insurance Provider</label>
                        <input type="text" class="form-control" id="insurance_provider" name="insurance_provider" 
                               value="<?= old('insurance_provider', $patient['insurance_provider'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="insurance_policy_number" class="form-label">Policy Number</label>
                        <input type="text" class="form-control" id="insurance_policy_number" name="insurance_policy_number" 
                               value="<?= old('insurance_policy_number', $patient['insurance_policy_number'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($patient) && $patient): ?>
        <!-- Status (only for edit) -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Patient Status</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= old('status', $patient['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status', $patient['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Form Actions -->
        <div class="card shadow mb-4">
            <div class="card-body text-end">
                <a href="<?= site_url('patients') ?>" class="btn btn-secondary me-2">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($patient) && $patient ? 'Update Patient' : 'Register Patient' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
