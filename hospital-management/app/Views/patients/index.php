<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($page_title) ?></h1>
        <a href="<?= site_url('patients/create') ?>" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Add New Patient</span>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Patients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_patients) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Patients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($active_patients) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Patients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($inactive_patients) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="get" action="<?= site_url('patients') ?>" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by name, email, phone or ID..." value="<?= esc(service('request')->getGet('search')) ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" <?= service('request')->getGet('status') == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= service('request')->getGet('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <?php if (service('request')->getGet('search') || service('request')->getGet('status')): ?>
                <div class="col-md-2">
                    <a href="<?= site_url('patients') ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Patient List</h6>
            <span class="badge bg-primary"><?= count($patients) ?> records</span>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="patientsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Contact</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Last Visit</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No patients found. Add your first patient!</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $patient): ?>
                                <?php 
                                    $birthDate = new DateTime($patient['date_of_birth']);
                                    $age = $birthDate->diff(new DateTime('today'))->y;
                                ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info"><?= esc($patient['patient_id']) ?></span>
                                </td>
                                <td>
                                    <a href="<?= site_url('patients/' . $patient['id']) ?>" class="text-decoration-none">
                                        <strong><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></strong>
                                    </a>
                                </td>
                                <td>
                                    <?= $age ?> yrs / <?= ucfirst(esc($patient['gender'])) ?>
                                </td>
                                <td>
                                    <div><i class="fas fa-phone text-muted"></i> <?= esc($patient['phone']) ?></div>
                                    <?php if ($patient['email']): ?>
                                    <div class="small text-muted"><i class="fas fa-envelope"></i> <?= esc($patient['email']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($patient['blood_group']): ?>
                                        <span class="badge bg-danger"><?= esc($patient['blood_group']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($patient['status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($patient['created_at'])) ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('patients/' . $patient['id']) ?>" class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $patient['id'] ?>, '<?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pager && count($patients) > 0): ?>
            <div class="mt-3">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<form id="deleteForm" method="post" style="display:none;">
    <?= csrf_field() ?>
</form>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete patient "' + name + '"? This action cannot be undone.')) {
        document.getElementById('deleteForm').action = '<?= site_url('patients') ?>/' + id;
        document.getElementById('deleteForm').method = 'POST';
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?= $this->endSection() ?>
