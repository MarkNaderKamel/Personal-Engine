<?php 
$pageTitle = 'Weather'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <i class="bi bi-cloud-sun"></i>
        <h2 class="mb-0">Weather</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card weather-widget text-white mb-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-geo-alt me-2"></i>
                            <h5 class="mb-0"><?= Security::sanitizeOutput($data['weather']['location']) ?></h5>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <span class="display-1 fw-bold lh-1"><?= $data['weather']['temp'] ?></span>
                            <span class="fs-2 mt-2">°F</span>
                        </div>
                        <p class="mb-2 fs-5"><?= Security::sanitizeOutput($data['weather']['condition']) ?></p>
                        <p class="mb-0 opacity-75"><?= Security::sanitizeOutput($data['weather']['description']) ?></p>
                    </div>
                    <div class="col-md-6 text-center">
                        <i class="bi bi-<?= $data['weather']['icon'] ?> weather-icon" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-week me-2 text-primary"></i>7-Day Forecast</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Condition</th>
                                <th class="text-center">High</th>
                                <th class="text-center">Low</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['forecast'] as $day): ?>
                            <tr>
                                <td>
                                    <strong><?= $day['day'] ?></strong>
                                    <br><small class="text-muted"><?= $day['date'] ?></small>
                                </td>
                                <td>
                                    <i class="bi bi-<?= $day['icon'] ?> me-2 text-info"></i>
                                    <?= Security::sanitizeOutput($day['condition']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="text-danger fw-bold"><?= $day['high'] ?>°</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-primary"><?= $day['low'] ?>°</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-thermometer-half me-2 text-danger"></i>Current Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-thermometer text-warning fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">Feels Like</small>
                            <strong><?= $data['weather']['feels_like'] ?>°F</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-droplet text-info fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">Humidity</small>
                            <strong><?= $data['weather']['humidity'] ?>%</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-wind text-secondary fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">Wind</small>
                            <strong><?= $data['weather']['wind_speed'] ?> mph</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-sun text-warning fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">UV Index</small>
                            <strong><?= $data['weather']['uv_index'] ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-eye text-primary fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">Visibility</small>
                            <strong><?= $data['weather']['visibility'] ?> mi</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="bi bi-speedometer text-success fs-4 mb-2 d-block"></i>
                            <small class="text-muted d-block">Pressure</small>
                            <strong><?= $data['weather']['pressure'] ?> hPa</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-up-right me-2 text-danger"></i>High & Low</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <i class="bi bi-arrow-up text-danger fs-2 mb-2 d-block"></i>
                        <small class="text-muted d-block">High</small>
                        <span class="fs-4 fw-bold text-danger"><?= $data['weather']['temp_max'] ?>°</span>
                    </div>
                    <div class="border-start"></div>
                    <div>
                        <i class="bi bi-arrow-down text-primary fs-2 mb-2 d-block"></i>
                        <small class="text-muted d-block">Low</small>
                        <span class="fs-4 fw-bold text-primary"><?= $data['weather']['temp_min'] ?>°</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($data['isLive']): ?>
<div class="alert alert-success">
    <i class="bi bi-check-circle me-2"></i>
    <strong>Live Weather:</strong> Data last updated at <?= $data['lastUpdated'] ?>. Weather refreshes every 30 minutes.
</div>
<?php else: ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Offline Mode:</strong> Unable to fetch live weather data. Showing sample data. Please check your internet connection.
</div>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
