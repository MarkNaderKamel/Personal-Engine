<?php 
$pageTitle = 'Add Relationship'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Add Relationship</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/relationships/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="person_name" class="form-label">Person Name *</label>
                                <input type="text" class="form-control" id="person_name" name="person_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="relationship_type" class="form-label">Relationship Type</label>
                                <select class="form-select" id="relationship_type" name="relationship_type">
                                    <option value="">Select type...</option>
                                    <option value="Family">Family</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Partner">Partner</option>
                                    <option value="Colleague">Colleague</option>
                                    <option value="Mentor">Mentor</option>
                                    <option value="Acquaintance">Acquaintance</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Relationship Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="important_dates" class="form-label">Important Dates</label>
                                <input type="text" class="form-control" id="important_dates" name="important_dates" placeholder="Birthday: Jan 15, Anniversary: Mar 20...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Interests, memorable moments, things to remember..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Add Relationship
                            </button>
                            <a href="/relationships" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
