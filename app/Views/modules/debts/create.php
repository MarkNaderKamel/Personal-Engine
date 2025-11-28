<?php 
$pageTitle = 'Add Debt'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Add New Debt</h5>
                </div>
                <div class="card-body">
                    <form action="/debts/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="debt_name" class="form-label">Debt Name *</label>
                                <input type="text" class="form-control" id="debt_name" name="debt_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="debt_type" class="form-label">Debt Type</label>
                                <select class="form-select" id="debt_type" name="debt_type">
                                    <option value="">Select type</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="personal_loan">Personal Loan</option>
                                    <option value="mortgage">Mortgage</option>
                                    <option value="auto_loan">Auto Loan</option>
                                    <option value="student_loan">Student Loan</option>
                                    <option value="medical">Medical Debt</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="principal_amount" class="form-label">Original Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="principal_amount" name="principal_amount" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="current_balance" class="form-label">Current Balance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="current_balance" name="current_balance" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="minimum_payment" class="form-label">Minimum Payment</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="minimum_payment" name="minimum_payment">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="creditor" class="form-label">Creditor/Lender</label>
                            <input type="text" class="form-control" id="creditor" name="creditor">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/debts" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Debt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
