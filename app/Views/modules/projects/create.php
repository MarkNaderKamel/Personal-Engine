<?php $pageTitle = 'Create Project'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Create Project</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/projects/create">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="mb-3">
                    <label for="project_name" class="form-label">Project Name *</label>
                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Project</button>
                <a href="/projects" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
