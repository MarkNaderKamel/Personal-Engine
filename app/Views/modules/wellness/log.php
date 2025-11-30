<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Log Wellness Data - <?= date('F j, Y') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/wellness/log">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        <input type="hidden" name="recorded_at" value="<?= date('Y-m-d') ?>">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-droplet-fill text-primary me-2 fs-5"></i>
                                        Water Intake (Liters)
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="water_intake" 
                                           value="<?= $todayLog['water_intake'] ?? '2' ?>" step="0.1" min="0" max="10">
                                    <div class="water-quick-btns mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWater(0.25)">+250ml</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWater(0.5)">+500ml</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWater(1)">+1L</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-moon-stars-fill text-info me-2 fs-5"></i>
                                        Sleep Hours
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="sleep_hours" 
                                           value="<?= $todayLog['sleep_hours'] ?? '7' ?>" step="0.5" min="0" max="24">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-emoji-smile-fill text-success me-2 fs-5"></i>
                                        Mood Score (1-10)
                                    </label>
                                    <input type="range" class="form-range" name="mood_score" 
                                           value="<?= $todayLog['mood_score'] ?? '7' ?>" min="1" max="10" 
                                           oninput="document.getElementById('moodValue').textContent = this.value">
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted">Low</span>
                                        <span class="badge bg-success fs-6" id="moodValue"><?= $todayLog['mood_score'] ?? '7' ?></span>
                                        <span class="small text-muted">High</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-lightning-fill text-warning me-2 fs-5"></i>
                                        Energy Level (1-10)
                                    </label>
                                    <input type="range" class="form-range" name="energy_level" 
                                           value="<?= $todayLog['energy_level'] ?? '7' ?>" min="1" max="10"
                                           oninput="document.getElementById('energyValue').textContent = this.value">
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted">Low</span>
                                        <span class="badge bg-warning fs-6" id="energyValue"><?= $todayLog['energy_level'] ?? '7' ?></span>
                                        <span class="small text-muted">High</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-emoji-frown-fill text-danger me-2 fs-5"></i>
                                        Stress Level (1-10)
                                    </label>
                                    <input type="range" class="form-range" name="stress_level" 
                                           value="<?= $todayLog['stress_level'] ?? '5' ?>" min="1" max="10"
                                           oninput="document.getElementById('stressValue').textContent = this.value">
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted">Low</span>
                                        <span class="badge bg-danger fs-6" id="stressValue"><?= $todayLog['stress_level'] ?? '5' ?></span>
                                        <span class="small text-muted">High</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-activity text-danger me-2 fs-5"></i>
                                        Exercise Minutes
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="exercise_minutes" 
                                           value="<?= $todayLog['exercise_minutes'] ?? '30' ?>" min="0" max="300">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-person-walking text-secondary me-2 fs-5"></i>
                                        Steps Count
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="steps_count" 
                                           value="<?= $todayLog['steps_count'] ?? '0' ?>" min="0" max="100000">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-speedometer2 text-info me-2 fs-5"></i>
                                        Weight (kg)
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="weight" 
                                           value="<?= $todayLog['weight'] ?? '' ?>" step="0.1" min="20" max="300"
                                           placeholder="Optional">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-fire text-danger me-2 fs-5"></i>
                                        Calories Consumed
                                    </label>
                                    <input type="number" class="form-control form-control-lg" name="calories_consumed" 
                                           value="<?= $todayLog['calories_consumed'] ?? '' ?>" min="0" max="10000"
                                           placeholder="Optional">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="wellness-input-card p-3 border rounded">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="bi bi-journal-text me-2 fs-5"></i>
                                        Notes
                                    </label>
                                    <textarea class="form-control" name="notes" rows="3" 
                                              placeholder="How are you feeling today?"><?= htmlspecialchars($todayLog['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i>Save Log
                            </button>
                            <a href="/wellness" class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addWater(amount) {
    const input = document.querySelector('input[name="water_intake"]');
    input.value = (parseFloat(input.value) + amount).toFixed(1);
}
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
