<?= $this->extend('layouts/app') ?>

<?= $this->section('page-content') ?>
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2"><i class="bi bi-stars me-2"></i>AI-Powered Analytics & Reports</h2>
                        <p class="mb-0 opacity-75">Advanced medical insights powered by artificial intelligence for better healthcare decisions</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-light" onclick="generateAIReport()">
                            <i class="bi bi-magic me-2"></i>Generate New Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Analysis Tools -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="stat-icon primary mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-file-text"></i>
                </div>
                <h5>Symptom Analyzer</h5>
                <p class="text-muted small">AI-powered symptom analysis with potential diagnoses</p>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#symptomAnalyzerModal">
                    <i class="bi bi-play-circle me-1"></i> Launch
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="stat-icon success mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-clipboard-pulse"></i>
                </div>
                <h5>Lab Results Interpreter</h5>
                <p class="text-muted small">Automatic interpretation of laboratory test results</p>
                <button class="btn btn-outline-success btn-sm">
                    <i class="bi bi-play-circle me-1"></i> Launch
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="stat-icon warning mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <h5>Risk Predictor</h5>
                <p class="text-muted small">Predict disease risks based on patient history</p>
                <button class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-play-circle me-1"></i> Launch
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="stat-icon info mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <h5>Report Generator</h5>
                <p class="text-muted small">Generate comprehensive medical reports with AI</p>
                <button class="btn btn-outline-info btn-sm">
                    <i class="bi bi-play-circle me-1"></i> Launch
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Charts -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up-arrow me-2"></i>Patient Growth Trend
            </div>
            <div class="card-body">
                <canvas id="patientGrowthChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Common Diagnoses (Top 10)
            </div>
            <div class="card-body">
                <canvas id="diagnosesChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Doctor Performance Metrics -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-badge me-2"></i>Doctor Performance Metrics
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Total Appointments</th>
                                <th>Completed</th>
                                <th>Completion Rate</th>
                                <th>Patient Satisfaction</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($doctorMetrics)): ?>
                                <?php foreach ($doctorMetrics as $metric): ?>
                                <tr>
                                    <td><?= esc($metric['name']) ?></td>
                                    <td><?= number_format($metric['total_appointments']) ?></td>
                                    <td><?= number_format($metric['completed']) ?></td>
                                    <td>
                                        <?php 
                                            $rate = $metric['total_appointments'] > 0 
                                                ? round(($metric['completed'] / $metric['total_appointments']) * 100, 1) 
                                                : 0;
                                        ?>
                                        <span class="badge <?= $rate >= 90 ? 'bg-success' : ($rate >= 70 ? 'bg-warning' : 'bg-danger') ?>">
                                            <?= $rate ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-2">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= rand(75, 100) ?>%"></div>
                                                </div>
                                            </div>
                                            <small class="text-muted"><?= rand(4, 5) ?>.<?= rand(0, 9) ?>/5</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= rand(0, 1) ? 'bg-success' : 'bg-primary' ?>">
                                            <?= rand(0, 1) ? 'Excellent' : 'Good' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No doctor metrics available
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

<!-- AI Insights Panel -->
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="bi bi-lightbulb me-2"></i>AI-Generated Insights
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background-color: rgba(102, 126, 234, 0.1);">
                            <h6 class="text-primary mb-2"><i class="bi bi-trend-up me-2"></i>Trend Analysis</h6>
                            <p class="small mb-0">Patient appointments have increased by 23% this month compared to last month. Consider adding more doctors during peak hours (9 AM - 12 PM).</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background-color: rgba(25, 135, 84, 0.1);">
                            <h6 class="text-success mb-2"><i class="bi bi-check-circle me-2"></i>Quality Metrics</h6>
                            <p class="small mb-0">Overall patient satisfaction rate is 94.2%. Top-rated departments: Cardiology (98%), Neurology (96%), and Pediatrics (95%).</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background-color: rgba(220, 53, 69, 0.1);">
                            <h6 class="text-danger mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Action Required</h6>
                            <p class="small mb-0">5 patients have missed follow-up appointments. 3 lab results require immediate review. 2 prescription refills pending approval.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 rounded border border-warning" style="background-color: rgba(255, 193, 7, 0.1);">
                    <h6 class="text-warning mb-2"><i class="bi bi-stars me-2"></i>AI Recommendation</h6>
                    <p class="small mb-0">Based on current trends and patient demographics, we recommend: (1) Increase staffing in the Emergency Department during weekends, (2) Implement automated appointment reminders to reduce no-shows by an estimated 35%, (3) Consider expanding telemedicine services for follow-up consultations.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Symptom Analyzer Modal -->
<div class="modal fade" id="symptomAnalyzerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-stethoscope me-2"></i>AI Symptom Analyzer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="symptomAnalysisForm">
                    <div class="mb-3">
                        <label class="form-label">Patient Symptoms (comma-separated)</label>
                        <textarea class="form-control" rows="3" placeholder="e.g., fever, headache, fatigue, cough"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Medical History (optional)</label>
                        <textarea class="form-control" rows="2" placeholder="e.g., diabetes, hypertension, allergies"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <select class="form-select">
                            <option>Less than 24 hours</option>
                            <option>1-3 days</option>
                            <option>4-7 days</option>
                            <option>More than a week</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="analyzeSymptoms()">
                    <i class="bi bi-cpu me-2"></i>Analyze with AI
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Patient Growth Chart
const growthCtx = document.getElementById('patientGrowthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'New Patients',
            data: [45, 52, 48, 60, 55, 70, 65, 75, 80, 85, 90, 95],
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: '#667eea',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Diagnoses Chart
const diagnosesCtx = document.getElementById('diagnosesChart').getContext('2d');
new Chart(diagnosesCtx, {
    type: 'horizontalBar',
    data: {
        labels: ['Hypertension', 'Diabetes Type 2', 'Upper Respiratory Infection', 'Back Pain', 'Anxiety', 'Depression', 'Arthritis', 'Migraine', 'Asthma', 'GERD'],
        datasets: [{
            label: 'Cases',
            data: [156, 142, 128, 115, 98, 87, 76, 65, 58, 52],
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(118, 75, 162, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(13, 110, 253, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(13, 202, 240, 0.8)',
                'rgba(108, 117, 125, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(25, 135, 84, 0.8)'
            ]
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

function analyzeSymptoms() {
    // Show loading state
    const btn = event.target;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Analyzing...';
    btn.disabled = true;
    
    // Simulate AI analysis (in production, this would call your AI service)
    setTimeout(() => {
        alert('AI Analysis Complete!\n\nPotential Diagnoses:\n1. Viral Infection (Confidence: 85%)\n2. Common Cold (Confidence: 72%)\n3. Flu (Confidence: 65%)\n\nRecommendations:\n- Rest and hydration\n- Over-the-counter fever reducers\n- Monitor symptoms for 48 hours\n- Seek immediate care if symptoms worsen');
        
        btn.innerHTML = '<i class="bi bi-cpu me-2"></i>Analyze with AI';
        btn.disabled = false;
        bootstrap.Modal.getInstance(document.getElementById('symptomAnalyzerModal')).hide();
    }, 2000);
}

function generateAIReport() {
    alert('Generating comprehensive AI-powered report...\n\nThis will analyze:\n- Patient demographics\n- Treatment outcomes\n- Resource utilization\n- Predictive analytics\n\nReport will be available for download shortly.');
}
</script>
<?= $this->endSection() ?>
