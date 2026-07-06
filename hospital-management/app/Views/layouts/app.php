<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-hospital me-2"></i>
        HMS Pro
    </div>
    <ul class="sidebar-menu mt-3">
        <li>
            <a href="<?= site_url('dashboard') ?>" class="<?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= site_url('patients') ?>" class="<?= strpos(uri_string(), 'patients') === 0 ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Patients
            </a>
        </li>
        <li>
            <a href="<?= site_url('doctors') ?>" class="<?= strpos(uri_string(), 'doctors') === 0 ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Doctors
            </a>
        </li>
        <li>
            <a href="<?= site_url('appointments') ?>" class="<?= strpos(uri_string(), 'appointments') === 0 ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
        </li>
        <li>
            <a href="<?= site_url('medical-records') ?>" class="<?= strpos(uri_string(), 'medical-records') === 0 ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-medical"></i> Medical Records
            </a>
        </li>
        <li>
            <a href="<?= site_url('reports/analytics') ?>" class="<?= strpos(uri_string(), 'reports') === 0 ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i> Analytics & AI Reports
            </a>
        </li>
        <li class="mt-4 pt-3 border-top border-light border-opacity-25">
            <small class="text-white-50 text-uppercase px-3" style="font-size: 0.75rem;">Settings</small>
        </li>
        <li>
            <a href="<?= site_url('users') ?>">
                <i class="bi bi-person-gear"></i> Users
            </a>
        </li>
        <li>
            <a href="<?= site_url('settings') ?>">
                <i class="bi bi-gear"></i> Settings
            </a>
        </li>
        <li>
            <a href="<?= site_url('logout') ?>" class="text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><?= esc($title ?? 'Dashboard') ?></h4>
            <nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($title ?? 'Dashboard') ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        3
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#"><small><strong>New appointment</strong> - John Doe at 2:00 PM</small></a></li>
                    <li><a class="dropdown-item" href="#"><small><strong>Lab results ready</strong> - Patient #PAT-2024-000123</small></a></li>
                    <li><a class="dropdown-item" href="#"><small><strong>Prescription refill</strong> - Request from Mary Smith</small></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="bi bi-person"></i>
                    </div>
                    <span class="d-none d-md-inline">Admin</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= site_url('profile') ?>"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="<?= site_url('settings') ?>"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('page-content') ?>
    </div>
</div>
<?= $this->endSection() ?>
