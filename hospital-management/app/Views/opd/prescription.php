<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - <?= esc($opd['opd_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card { border: none !important; box-shadow: none !important; }
            .table th, .table td { border: 1px solid #dee2e6 !important; }
        }
        .hospital-header { border-bottom: 3px solid #0d6efd; padding-bottom: 20px; margin-bottom: 20px; }
        .prescription-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9; }
        .doctor-sign { margin-top: 50px; text-align: right; padding-right: 20px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4 mb-5" style="max-width: 800px; background: white; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    
    <!-- Print Button -->
    <div class="d-flex justify-content-end no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Print Prescription</button>
        <a href="<?= site_url('opd/show/' . $opd['id']) ?>" class="btn btn-secondary ms-2">Back</a>
    </div>

    <!-- Hospital Header -->
    <div class="hospital-header text-center">
        <h2 class="fw-bold text-primary">CITY CARE HOSPITAL</h2>
        <p class="mb-0">123 Health Avenue, Medical District, City - 560001</p>
        <p class="mb-0">Phone: (555) 123-4567 | Email: info@citycare.com</p>
    </div>

    <!-- Patient & Visit Info -->
    <div class="row mb-4">
        <div class="col-6">
            <h5 class="fw-bold">Patient Details</h5>
            <p class="mb-1"><strong>Name:</strong> <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
            <p class="mb-1"><strong>Age/Sex:</strong> <?= esc($patient['age']) ?> / <?= esc($patient['gender']) ?></p>
            <p class="mb-1"><strong>OPD No:</strong> <?= esc($opd['opd_number']) ?></p>
            <p class="mb-1"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($opd['visit_date'])) ?></p>
        </div>
        <div class="col-6 text-end">
            <h5 class="fw-bold">Doctor Details</h5>
            <p class="mb-1"><strong>Dr. <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?></strong></p>
            <p class="mb-1"><?= esc($doctor['specialization']) ?></p>
            <p class="mb-1">Reg No: <?= esc($doctor['registration_number']) ?></p>
        </div>
    </div>

    <!-- Vitals -->
    <div class="prescription-box">
        <h6 class="fw-bold text-uppercase text-muted mb-3">Vitals</h6>
        <div class="row g-3">
            <div class="col-3"><strong>BP:</strong> <?= esc($opd['blood_pressure']) ?> mmHg</div>
            <div class="col-3"><strong>Pulse:</strong> <?= esc($opd['pulse_rate']) ?> bpm</div>
            <div class="col-3"><strong>Temp:</strong> <?= esc($opd['temperature']) ?> °F</div>
            <div class="col-3"><strong>Weight:</strong> <?= esc($opd['weight']) ?> kg</div>
            <?php if($opd['spo2']): ?><div class="col-3"><strong>SpO2:</strong> <?= esc($opd['spo2']) ?> %</div><?php endif; ?>
            <?php if($opd['bmi']): ?><div class="col-3"><strong>BMI:</strong> <?= esc($opd['bmi']) ?></div><?php endif; ?>
        </div>
    </div>

    <!-- Clinical Notes -->
    <?php if($opd['chief_complaints'] || $opd['diagnosis']): ?>
    <div class="prescription-box" style="background: white; border-left: 4px solid #0d6efd;">
        <h6 class="fw-bold text-uppercase text-muted mb-3">Clinical Assessment</h6>
        <?php if($opd['chief_complaints']): ?>
            <p class="mb-2"><strong>Complaints:</strong><br><?= nl2br(esc($opd['chief_complaints'])) ?></p>
        <?php endif; ?>
        <?php if($opd['diagnosis']): ?>
            <p class="mb-0"><strong>Diagnosis:</strong> <span class="text-danger fw-bold"><?= nl2br(esc($opd['diagnosis'])) ?></span></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Prescription (Medicines) -->
    <?php if(!empty($medicines)): ?>
    <div class="mb-4">
        <h6 class="fw-bold text-uppercase text-muted mb-3">Rx Medications</h6>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Medicine Name</th>
                    <th width="20%">Dosage</th>
                    <th width="20%">Frequency</th>
                    <th width="20%">Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($medicines as $index => $med): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td>
                        <strong><?= esc($med['medicine_name']) ?></strong><br>
                        <small class="text-muted"><?= esc($med['strength'] ?? '') ?></small>
                    </td>
                    <td><?= esc($med['dosage']) ?></td>
                    <td><?= esc($med['frequency']) ?></td>
                    <td><?= esc($med['duration']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Investigations & Advice -->
    <?php if($opd['investigations'] || $opd['advice']): ?>
    <div class="row">
        <?php if($opd['investigations']): ?>
        <div class="col-6">
            <h6 class="fw-bold text-uppercase text-muted">Investigations</h6>
            <ul class="list-group list-group-flush">
                <?php 
                $investigations = explode("\n", $opd['investigations']);
                foreach($investigations as $inv): 
                    if(trim($inv)): ?>
                        <li class="list-group-item px-0">• <?= esc(trim($inv)) ?></li>
                    <?php endif;
                endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if($opd['advice']): ?>
        <div class="col-6">
            <h6 class="fw-bold text-uppercase text-muted">Advice</h6>
            <ul class="list-group list-group-flush">
                <?php 
                $advices = explode("\n", $opd['advice']);
                foreach($advices as $adv): 
                    if(trim($adv)): ?>
                        <li class="list-group-item px-0">• <?= esc(trim($adv)) ?></li>
                    <?php endif;
                endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Footer / Signature -->
    <div class="doctor-sign">
        <p class="mb-0">__________________________</p>
        <p class="mb-0"><strong>Authorized Signature</strong></p>
    </div>

    <div class="text-center mt-5 pt-3 border-top">
        <small class="text-muted">This is a computer-generated prescription. No signature required if stamped.</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
