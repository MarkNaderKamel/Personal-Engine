<?php 
$pageTitle = 'New Event'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Create New Event</h5>
                </div>
                <div class="card-body">
                    <form action="/events/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label for="event_title" class="form-label">Event Title *</label>
                            <input type="text" class="form-control" id="event_title" name="event_title" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_time" class="form-label">Time</label>
                                <input type="time" class="form-control" id="event_time" name="event_time">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="Event location...">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Event details..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/events" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
