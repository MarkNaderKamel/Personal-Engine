<?php 
$pageTitle = 'Admin Dashboard'; 
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Users</h6>
                    <h3><?= $stats['total_users'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Tasks</h6>
                    <h3><?= $stats['total_tasks'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Total Bills</h6>
                    <h3><?= $stats['total_bills'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Total Documents</h6>
                    <h3><?= $stats['total_documents'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="/admin/users" class="btn btn-primary me-2">Manage Users</a>
                    <a href="/admin/logs" class="btn btn-secondary">View Logs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
