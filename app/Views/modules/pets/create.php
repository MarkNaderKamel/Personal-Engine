<?php 
$pageTitle = 'Add Pet'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Add New Pet</h5>
                </div>
                <div class="card-body">
                    <form action="/pets/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pet_name" class="form-label">Pet Name *</label>
                                <input type="text" class="form-control" id="pet_name" name="pet_name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pet_type" class="form-label">Pet Type</label>
                                <select class="form-select" id="pet_type" name="pet_type">
                                    <option value="">Select type</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="bird">Bird</option>
                                    <option value="fish">Fish</option>
                                    <option value="rabbit">Rabbit</option>
                                    <option value="hamster">Hamster</option>
                                    <option value="reptile">Reptile</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="breed" class="form-label">Breed</label>
                                <input type="text" class="form-control" id="breed" name="breed">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vet_name" class="form-label">Vet Name/Clinic</label>
                                <input type="text" class="form-control" id="vet_name" name="vet_name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="next_checkup" class="form-label">Next Checkup</label>
                                <input type="date" class="form-control" id="next_checkup" name="next_checkup">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Medical history, allergies, special needs..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/pets" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Pet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
