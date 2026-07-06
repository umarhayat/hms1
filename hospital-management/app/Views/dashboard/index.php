<?= $this->extend('layouts/app') ?>

<?= $this->section('page-content') ?>
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Patients</h6>
                    <h3 class="mb-0"><?= number_format($totalPatients ?? 0) ?></h3>
                    <small class="text-success"><i class="bi bi-arrow-up"></i> 12% from last month</small>
                </div>
                <div class="stat-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Doctors</h6>
                    <h3 class="mb-0"><?= number_format($totalDoctors ?? 0) ?></h3>
                    <small class="text-success"><i class="bi bi-arrow-up"></i> 5% from last month</small>
                </div>
                <div class="stat-icon success">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Today's Appointments</h6>
                    <h3 class="mb-0"><?= number_format($todayAppointments ?? 0) ?></h3>
                    <small class="text-warning"><i class="bi bi-clock"></i> 8 pending</small>
                </div>
                <div class="stat-icon warning">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Medical Records</h6>
                    <h3 class="mb-0"><?= count($recentRecords ?? []) ?>+</h3>
                    <small class="text-info"><i class="bi bi-file-earmark"></i> Updated today</small>
                </div>
                <div class="stat-icon info">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Appointment Trends</span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Last 30 Days
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <canvas id="appointmentChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <span><i class="bi bi-pie-chart me-2"></i>Appointment Status</span>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Medical Records with AI Analysis -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-medical me-2"></i>Recent Medical Records</span>
                <a href="<?= site_url('medical-records') ?>" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Record ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>AI Analysis</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentRecords)): ?>
                                <?php foreach ($recentRecords as $record): ?>
                                <tr>
                                    <td><code><?= esc($record['record_id']) ?></code></td>
                                    <td><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></td>
                                    <td><?= esc($record['doctor_name']) ?></td>
                                    <td><?= esc(substr($record['diagnosis_primary'], 0, 50)) ?>...</td>
                                    <td>
                                        <?php if (!empty($record['ai_analysis'])): ?>
                                            <span class="badge ai-badge">
                                                <i class="bi bi-stars"></i> AI Analyzed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No AI Analysis</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($record['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= site_url('medical-records/view/' . $record['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No medical records found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-lightning-charge me-2"></i>Quick Actions</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <a href="<?= site_url('patients/create') ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-plus d-block mb-1"></i>
                            <small>Add Patient</small>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('appointments/create') ?>" class="btn btn-outline-success w-100">
                            <i class="bi bi-calendar-plus d-block mb-1"></i>
                            <small>New Appointment</small>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('doctors/create') ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-person-badge-plus d-block mb-1"></i>
                            <small>Add Doctor</small>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('medical-records/create') ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-file-earmark-plus d-block mb-1"></i>
                            <small>New Record</small>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('reports/analytics') ?>" class="btn btn-outline-purple w-100">
                            <i class="bi bi-bar-chart-line d-block mb-1"></i>
                            <small>AI Reports</small>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?= site_url('settings') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-gear d-block mb-1"></i>
                            <small>Settings</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Appointment Trend Chart
const appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
new Chart(appointmentCtx, {
    type: 'line',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            label: 'Scheduled',
            data: [45, 52, 48, 60],
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Completed',
            data: [40, 48, 45, 55],
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Cancelled',
            data: [5, 4, 3, 5],
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Appointment Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Scheduled', 'Confirmed', 'Cancelled', 'No Show'],
        datasets: [{
            data: [<?= $appointmentStats[0]['count'] ?? 45 ?>, <?= $appointmentStats[1]['count'] ?? 20 ?>, <?= $appointmentStats[2]['count'] ?? 15 ?>, <?= $appointmentStats[3]['count'] ?? 10 ?>, <?= $appointmentStats[4]['count'] ?? 5 ?>],
            backgroundColor: [
                '#198754',
                '#0d6efd',
                '#0dcaf0',
                '#dc3545',
                '#ffc107'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
<?= $this->endSection() ?>
