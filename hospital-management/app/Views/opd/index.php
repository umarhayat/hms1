<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-hospital-user me-2"></i><?= esc($page_title) ?>
                </h2>
                <a href="<?= site_url('opd/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> New OPD Visit
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Visits Today</h6>
                            <h3 class="mb-0"><?= $stats['total'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Waiting</h6>
                            <h3 class="mb-0"><?= $stats['waiting'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Consulting</h6>
                            <h3 class="mb-0"><?= $stats['consulting'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-user-md fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Completed</h6>
                            <h3 class="mb-0"><?= $stats['completed'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OPD Queue Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Today's OPD Queue</h5>
        </div>
        <div class="card-body">
            <?php if (empty($today_visits)) : ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No OPD visits scheduled for today.</p>
                    <a href="<?= site_url('opd/create') ?>" class="btn btn-sm btn-primary">Register First Visit</a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="opdQueueTable">
                        <thead class="table-light">
                            <tr>
                                <th>OPD No.</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Time</th>
                                <th>Complaint</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($today_visits as $visit) : ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary"><?= esc($visit['opd_number']) ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= esc($visit['patient_name']) ?></strong><br>
                                            <small class="text-muted"><?= esc($visit['gender']) ?>, <?= esc($visit['age']) ?> yrs</small>
                                        </div>
                                    </td>
                                    <td><?= esc($visit['doctor_name']) ?></td>
                                    <td><?= date('h:i A', strtotime($visit['visit_date'])) ?></td>
                                    <td>
                                        <small><?= esc(substr($visit['complaint_main'], 0, 30)) ?>...</small>
                                    </td>
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
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('opd/' . $visit['id']) ?>" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= site_url('opd/' . $visit['id'] . '/edit') ?>" class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#opdQueueTable').DataTable({
        "order": [[3, 'asc']], // Sort by time
        "pageLength": 25,
        "language": {
            "search": "Filter queue:",
            "zeroRecords": "No matching visits found"
        }
    });
});
</script>
<?= $this->endSection() ?>
