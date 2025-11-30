<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-heart-pulse me-2"></i>Wellness Tracker</h1>
            <p class="text-muted mb-0">Track your health and wellness metrics</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/wellness/history" class="btn btn-outline-primary">
                <i class="bi bi-clock-history me-1"></i>History
            </a>
            <a href="/wellness/log" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Log Today
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Streak</p>
                            <h3 class="mb-0"><?= $streak ?> days</h3>
                        </div>
                        <i class="bi bi-fire display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Sleep</p>
                            <h3 class="mb-0"><?= number_format($averages['avg_sleep'] ?? 0, 1) ?>h</h3>
                        </div>
                        <i class="bi bi-moon-stars display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Mood</p>
                            <h3 class="mb-0"><?= number_format($averages['avg_mood'] ?? 0, 1) ?>/10</h3>
                        </div>
                        <i class="bi bi-emoji-smile display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Water</p>
                            <h3 class="mb-0"><?= number_format($averages['avg_water'] ?? 0, 1) ?>L</h3>
                        </div>
                        <i class="bi bi-droplet display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($todayLog): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Today's Log</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-droplet-half text-primary fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= $todayLog['water_intake'] ?>L</h5>
                            <small class="text-muted">Water</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-moon text-info fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= $todayLog['sleep_hours'] ?>h</h5>
                            <small class="text-muted">Sleep</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-emoji-smile text-success fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= $todayLog['mood_score'] ?? '-' ?></h5>
                            <small class="text-muted">Mood</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-lightning text-warning fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= $todayLog['energy_level'] ?? '-' ?></h5>
                            <small class="text-muted">Energy</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-activity text-danger fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= $todayLog['exercise_minutes'] ?>m</h5>
                            <small class="text-muted">Exercise</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="wellness-metric">
                            <i class="bi bi-person-walking text-secondary fs-3"></i>
                            <h5 class="mt-2 mb-0"><?= number_format($todayLog['steps_count']) ?></h5>
                            <small class="text-muted">Steps</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            You haven't logged your wellness data today. <a href="/wellness/log" class="alert-link">Log now</a>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>30-Day Trends</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" onclick="updateChart('mood')">Mood</button>
                        <button class="btn btn-outline-primary" onclick="updateChart('energy')">Energy</button>
                        <button class="btn btn-outline-primary" onclick="updateChart('sleep')">Sleep</button>
                        <button class="btn btn-outline-primary" onclick="updateChart('water')">Water</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="wellnessChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>30-Day Averages</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>Mood</span>
                            <strong><?= number_format($averages['avg_mood'] ?? 0, 1) ?>/10</strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= ($averages['avg_mood'] ?? 0) * 10 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>Energy</span>
                            <strong><?= number_format($averages['avg_energy'] ?? 0, 1) ?>/10</strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: <?= ($averages['avg_energy'] ?? 0) * 10 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>Sleep</span>
                            <strong><?= number_format($averages['avg_sleep'] ?? 0, 1) ?>h</strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: <?= min(100, ($averages['avg_sleep'] ?? 0) * 12.5) ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>Stress Level</span>
                            <strong><?= number_format($averages['avg_stress'] ?? 0, 1) ?>/10</strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: <?= ($averages['avg_stress'] ?? 0) * 10 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="d-flex justify-content-between">
                            <span>Avg Exercise</span>
                            <strong><?= number_format($averages['avg_exercise'] ?? 0, 0) ?> min</strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: <?= min(100, ($averages['avg_exercise'] ?? 0) / 60 * 100) ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <label class="d-flex justify-content-between">
                            <span>Avg Steps</span>
                            <strong><?= number_format($averages['avg_steps'] ?? 0, 0) ?></strong>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-secondary" style="width: <?= min(100, ($averages['avg_steps'] ?? 0) / 10000 * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartData = <?= json_encode([
    'labels' => array_map(fn($l) => date('M j', strtotime($l['recorded_at'])), $logs),
    'mood' => array_column($logs, 'mood_score'),
    'energy' => array_column($logs, 'energy_level'),
    'sleep' => array_column($logs, 'sleep_hours'),
    'water' => array_column($logs, 'water_intake'),
    'exercise' => array_column($logs, 'exercise_minutes'),
    'stress' => array_column($logs, 'stress_level')
]) ?>;

let chart;
const ctx = document.getElementById('wellnessChart').getContext('2d');

function createChart(metric) {
    const colors = {
        mood: { bg: 'rgba(16, 185, 129, 0.1)', border: 'rgb(16, 185, 129)' },
        energy: { bg: 'rgba(245, 158, 11, 0.1)', border: 'rgb(245, 158, 11)' },
        sleep: { bg: 'rgba(59, 130, 246, 0.1)', border: 'rgb(59, 130, 246)' },
        water: { bg: 'rgba(99, 102, 241, 0.1)', border: 'rgb(99, 102, 241)' },
        exercise: { bg: 'rgba(239, 68, 68, 0.1)', border: 'rgb(239, 68, 68)' },
        stress: { bg: 'rgba(168, 85, 247, 0.1)', border: 'rgb(168, 85, 247)' }
    };

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: metric.charAt(0).toUpperCase() + metric.slice(1),
                data: chartData[metric],
                backgroundColor: colors[metric].bg,
                borderColor: colors[metric].border,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

function updateChart(metric) {
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    createChart(metric);
}

createChart('mood');
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
